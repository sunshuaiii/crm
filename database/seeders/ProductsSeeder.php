<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $categories = ['Beverages', 'Dairy', 'Cereals', 'Health', 'Fresh Food', 'Frozen', 
        'Delicatessen', 'Confectionary', 'Household Cleaning', 'Fabric Wash', 'Personal Wash', 'Hair Care',
        'Facial & Skin Care', 'Oral Care', 'Sanitary Napkins', 'Diapers'];

        for ($i = 1; $i <= 200; $i++) {
            DB::table('products')->insert([
                'name' => $faker->word,
                'price' => $faker->randomFloat(2, 1, 80000),
                'category' => $faker->randomElement($categories),
            ]);
        }
    }
}
