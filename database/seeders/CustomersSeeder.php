<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // test user - customer
        DB::table('customers')->insert([
            'username' => "Lalisa",
            'email' => "lisa@email.com",
            'password' => bcrypt('12341234'),
            'first_name' => "Lalisa",
            'last_name' => "Manobal",
            'contact' => '0123456789',
            'gender' => 'Female',
            'dob' => '1997-03-27',
            'points' => 1000,
        ]);

        for ($i = 1; $i <= 30; $i++) {
            DB::table('customers')->insert([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'contact' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Female', 'Male']),
                'dob' => $faker->dateTimeBetween('-53 years', '-18 years')->format('Y-m-d'),
                'points' => $faker->numberBetween(1, 10000),
                'spending_score' => $faker->numberBetween(1, 100),
            ]);
        }
    }
}
