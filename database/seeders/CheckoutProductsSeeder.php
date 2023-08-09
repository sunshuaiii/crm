<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CheckoutProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $numberOfProducts = DB::table('products')->count();
        $numberOfCheckouts = DB::table('checkouts')->count();

        for ($i = 1; $i <= 500; $i++) {
            DB::table('checkout_products')->insert([
                'checkout_id' => $faker->numberBetween(1, $numberOfCheckouts),
                'product_id' => $faker->numberBetween(1, $numberOfProducts),
                'quantity' => $faker->numberBetween(1, 5),
            ]);
        }
    }
}
