<?php

namespace Database\Seeders;

use Carbon\Carbon;
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

        $activityTypes = [
            'website visit', 'email opened', 'phone call', 'meeting scheduled',
            'follow-up email', 'demo attended', 'social media engagement', 'product trial started',
            'content download', 'event registration', 'proposal sent', 'contract negotiation',
        ];

        $feedbackMessages = [
            'Interested in learning more about your services.',
            'Not sure if your product fits my needs.',
            'Your team provided excellent support!',
            'Price is higher than expected.',
            'Impressed with the demo presentation.',
            'Considering your product for our project.',
            'Looking for more customization options.',
            'Product features seem promising.',
            'Need more information about your pricing plans.',
            'Great user interface and ease of use.',
            'Concerned about data security.',
            'Interested in case studies and success stories.',
        ];

        $leadData = [];

        for ($i = 1; $i <= 50; $i++) {
            $activityCount = rand(0, count($activityTypes)); // Adjust the upper limit
            $selectedActivityIndices = ($activityCount > 0) ? array_rand($activityTypes, $activityCount) : [];
            $selectedActivityTypes = array_intersect_key($activityTypes, array_flip((array) $selectedActivityIndices));
            $activityString = implode(', ', $selectedActivityTypes);

            $feedbackCount = rand(0, min(3, count($feedbackMessages))); // Adjust the upper limit
            $selectedFeedbackIndices = ($feedbackCount > 0) ? array_rand($feedbackMessages, $feedbackCount) : [];
            $selectedFeedbackMessages = array_intersect_key($feedbackMessages, array_flip((array) $selectedFeedbackIndices));
            $feedbackString = implode(', ', $selectedFeedbackMessages);

            $leadData[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail(),
                'contact' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['Female', 'Male']),
                'status' => $faker->randomElement($status),
                'activity' => $activityString,
                'feedback' => $feedbackString,
                'activity_date' => Carbon::now()->subDays(rand(1, 30)), // Random date within the last month
                'feedback_date' => Carbon::now()->subDays(rand(1, 30)), // Random date within the last month
                'marketing_staff_id' => $faker->randomElement($mStaffIDs),
            ];
        }

        DB::table('leads')->insert($leadData);
    }
}
