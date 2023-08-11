<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CheckoutsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $methods = ['Credit Card', 'Debit Card', 'Cash', 'E-wallet'];
        $customerIDs = DB::table('customers')->pluck('id');
        $redeemed = DB::table('customer_coupons')->where('status', 'Redeemed')->get();

        for ($i = 1; $i <= 18532; $i++) {
            $customerId = $faker->randomElement($customerIDs);
            if ($redeemed->contains('customer_id', $customerId)) {
                $firstRedeemed = $redeemed->first(function ($coupon) use ($customerId) {
                    return $coupon->customer_id == $customerId;
                });
                DB::table('checkouts')->insert([
                    'date' => $faker->dateTimeBetween('-30 days', 'now'),
                    'payment_method' => $faker->randomElement($methods),
                    'customer_id' => $customerId,
                    'customer_coupon_id' => $firstRedeemed->id,
                ]);
                $redeemed = $redeemed->reject(function ($coupon) use ($firstRedeemed) {
                    return $coupon->id == $firstRedeemed->id;
                });
            } else {
                DB::table('checkouts')->insert([
                    'date' => $faker->dateTimeBetween('-30 days', 'now'),
                    'payment_method' => $faker->randomElement($methods),
                    'customer_id' => $customerId,
                ]);
            }
        }
    }
}
