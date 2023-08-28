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
        // $this->call(CustomersSeeder::class); //updated
        $this->call(MarketingStaffSeeder::class);
        $this->call(SupportStaffSeeder::class);
        $this->call(AdminsSeeder::class);
        $this->call(LeadsSeeder::class);
        $this->call(TicketsSeeder::class);
        $this->call(CouponsSeeder::class);
        // $this->call(ProductsSeeder::class);
        // $this->call(CustomerCouponsSeeder::class);
        // $this->call(CheckoutsSeeder::class);  //updated
        // $this->call(CheckoutProductsSeeder::class);
    }
}
