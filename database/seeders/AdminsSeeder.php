<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        //test user - admin
        DB::table('admins')->insert([
            'username' => "admin",
            'email' => "admin@email.com",
            'password' => bcrypt('12341234'),
        ]);

        for ($i = 1; $i <= 3; $i++) {
            DB::table('admins')->insert([
                'username' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password'),
            ]);
        }
    }
}
