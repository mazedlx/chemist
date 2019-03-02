<?php

use Illuminate\Database\Seeder;
use App\Invoice;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Invoice::class, 20)->create();
    }
}
