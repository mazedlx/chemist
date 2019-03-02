<?php

namespace Tests\Feature;

use App\User;
use App\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Category;
use Carbon\Carbon;

class AddInvoiceTest extends TestCase
{
    use RefreshDatabase;

    private function validParams($overrides = [])
    {
        return array_merge([
            'category_id' => factory(Category::class)->create()->id,
            'amount' => 1234,
            'date' => '2019-03-26',
        ], $overrides);
    }

    /** @test */
    public function users_can_add_invoices()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->post('/invoices', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/invoices');
        $this->assertEquals(1, Invoice::count());
        tap(Invoice::first(), function ($invoice) {
            $this->assertEquals(1234, $invoice->amount);
            $this->assertEquals(1, $invoice->category_id);
            $this->assertEquals(Carbon::createFromDate(2019, 03, 26)->format('Y-m-d'), $invoice->date->format('Y-m-d'));
        });
    }

    /** @test */
    public function amount_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'amount' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('amount');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function amount_must_be_numeric()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'amount' => 'not a number',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('amount');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function category_id_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'category_id' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('category_id');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function category_id_must_exist()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'category_id' => 1234567,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('category_id');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function date_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'date' => null,
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function date_must_be_a_date()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/invoice/create')->post('/invoices', $this->validParams([
            'date' => 'not a date',
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Invoice::count());
    }

    /** @test */
    public function guests_cannot_add_invoices()
    {
        $category = factory(Category::class)->create();

        $response = $this->post('/invoices', $this->validParams([
            'category_id' => $category->id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Invoice::count());
    }
}
