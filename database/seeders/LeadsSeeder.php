<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $status = ['New', 'Contacted', 'Interested', 'Not interested'];
        $mStaffIDs = DB::table('marketing_staff')->pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('leads')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail(),
                'contact' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Female', 'Male']),
                'status' => $faker->randomElement($status),
                'activity' => implode(' ', $faker->words(500)),
                'marketing_staff_id' => $faker->randomElement($mStaffIDs),
            ]);
        }
    }
}
