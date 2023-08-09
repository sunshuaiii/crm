<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MarketingStaffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        //test user - marketing staff
        DB::table('marketing_staffs')->insert([
            'username' => "mstaff",
            'email' => "mstaff@email.com",
            'password' => bcrypt('12341234'),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            DB::table('marketing_staffs')->insert([
                'username' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
            ]);
        }
    }
}
