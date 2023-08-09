<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CustomerCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $status = ['Claimed', 'Redeemed'];
        $numberOfCustomers = DB::table('customers')->count();
        $numberOfCoupons = DB::table('coupons')->count();

        for ($i = 1; $i <= 20; $i++) {
            $code = '';
            for ($j = 0; $j < 5; $j++) {
                $code .= $faker->randomNumber(4, true);
            }
            DB::table('customer_coupons')->insert([
                'status' => $faker->randomElement($status),
                'code' => $code,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(30),
                'customer_id' => $faker->numberBetween(1, $numberOfCustomers),
                'coupon_id' => $faker->numberBetween(1, $numberOfCoupons),
            ]);
        }
    }
}
