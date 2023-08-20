<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $q_types = ['Feedback', 'Complaint', 'Query', 'Issue'];
        $status = ['New', 'Open', 'Pending', 'Solved', 'Closed'];
        $numberOfStaffs = DB::table('support_staff')->count();
        $customerIDs = DB::table('customers')->pluck('id');
       
        for ($i = 1; $i <= 20; $i++) {
            DB::table('tickets')->insert([
                'query_type' => $faker->randomElement($q_types),
                'message' => implode(' ', $faker->words(500)),
                'status' => $faker->randomElement($status),
                'support_staff_id' => $faker->numberBetween(1, $numberOfStaffs),
                'customer_id' => $faker->randomElement($customerIDs),
            ]);
        }
    }
}
