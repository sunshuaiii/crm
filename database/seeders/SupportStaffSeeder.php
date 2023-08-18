<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SupportStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        //test user - support staff
        DB::table('support_staff')->insert([
            'username' => "sstaff",
            'email' => "sstaff@email.com",
            'password' => bcrypt('12341234'),
        ]);

        for ($i = 1; $i <= 5; $i++) {
            DB::table('support_staff')->insert([
                'username' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
            ]);
        }
    }
}
