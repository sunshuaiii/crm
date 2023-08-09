<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MarketingStaffsSeeder::class);
        $this->call(SupportStaffsSeeder::class);
        $this->call(AdminsSeeder::class);
        $this->call(CustomersSeeder::class);
        $this->call(LeadsSeeder::class);
        $this->call(TicketsSeeder::class);
        $this->call(CouponsSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(CustomerCouponsSeeder::class);
        $this->call(CheckoutsSeeder::class);
        $this->call(CheckoutProductsSeeder::class);
    }
}
