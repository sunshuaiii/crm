<?php

namespace Database\Seeders;

use App\Models\Ticket;
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

        for ($i = 1; $i <= 50; $i++) {
            $responseTime = $faker->numberBetween(1, 10000);
            $resolutionTime = $faker->numberBetween($responseTime, 15000);
            $ticketStatus = $faker->randomElement($status);

            $ticketData = [
                'query_type' => $faker->randomElement($q_types),
                'message' => implode(' ', $faker->words(500)),
                'status' => $ticketStatus,
                'support_staff_id' => $faker->numberBetween(1, $numberOfStaffs),
                'customer_id' => $faker->randomElement($customerIDs),
                'response_time' => ($ticketStatus !== 'New') ? $responseTime : null, // Set response_time only for non-'New' tickets
                'resolution_time' => ($ticketStatus == 'Closed') ? $resolutionTime : null, // Set resolution_time only for 'Closed' tickets
                'created_at' => now(),
            ];

            DB::table('tickets')->insertGetId($ticketData); // Insert and get the inserted ID
        }
    }
}
