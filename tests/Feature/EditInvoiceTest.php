<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Invoice;
use App\Category;
use Carbon\Carbon;

class EditInvoiceTest extends TestCase
{
    use RefreshDatabase;

    private function oldValues($overrides = [])
    {
        return array_merge([
            'amount' => 1234,
            'category_id' => factory(Category::class)->create()->id,
            'date' => '2019-03-26'
        ], $overrides);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'amount' => 4231,
            'category_id' => factory(Category::class)->create()->id,
            'date' => '2019-12-03'
        ], $overrides);
    }

    /** @test */
    public function users_can_edit_invoices()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->patch("/invoices/$invoice->id", $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/invoices');
        tap(Invoice::first(), function ($invoice) {
            $this->assertEquals(4231, $invoice->amount);
            $this->assertEquals(2, $invoice->category_id);
            $this->assertEquals(Carbon::createFromDate(2019, 12, 3)->format('Y-m-d'), $invoice->date->format('Y-m-d'));
        });
    }

    /** @test */
    public function amount_is_required()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'amount' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function amount_must_be_numeric()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'amount' => 'not a number',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function category_id_is_required()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'category_id' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function category_id_must_exist()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'category_id' => 12345678,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('category_id');
    }

    /** @test */
    public function date_is_required()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'date' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function date_must_be_a_date()
    {
        $user = factory(User::class)->create();
        $invoice = Invoice::create($this->oldValues());

        $response = $this->actingAs($user)->from("/invoices/$invoice->id/edit")->patch("/invoices/$invoice->id", $this->validParams([
            'date' => 'not a date',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function guests_cannot_edit_invoices()
    {
        $invoice = Invoice::create($this->oldValues());

        $response = $this->patch("/invoices/$invoice->id", $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        tap(Invoice::first(), function ($invoice) {
            $this->assertEquals(1234, $invoice->amount);
            $this->assertEquals(1, $invoice->category_id);
        });
    }
}
