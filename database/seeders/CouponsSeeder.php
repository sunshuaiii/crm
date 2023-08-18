<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions = '1. This voucher is valid for 30 days from the date of redemption.
        2. For one-time use only.
        3. This voucher is valid for a single transaction only. If final bill amount is lower than voucher amount, difference will not be refunded or carried forward.
        4. This voucher can be used at any store.
        5. Applicable for in-store redemption only and cannot be redeemed via online purchase.
        6. This voucher is not transferable and cannot be exchanged for cash in part or in full.
        7. We reserves the right to alter or suspend any terms and conditions with or without prior notice.';
        
        DB::table('coupons')->insert([
            'name' => 'RM2 OFF In-Store Coupon',
            'discount' => 2,
            'redemption_points' => 500,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM5 OFF In-Store Coupon',
            'discount' => 5,
            'redemption_points' => 1000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM10 OFF In-Store Coupon',
            'discount' => 10,
            'redemption_points' => 2000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM15 OFF In-Store Coupon',
            'discount' => 15,
            'redemption_points' => 3000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM20 OFF In-Store Coupon',
            'discount' => 20,
            'redemption_points' => 4000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM30 OFF In-Store Coupon',
            'discount' => 30,
            'redemption_points' => 6000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'RM50 OFF In-Store Coupon',
            'discount' => 50,
            'redemption_points' => 10000,
            'conditions' => $conditions,
        ]);

        DB::table('coupons')->insert([
            'name' => 'New Member Coupon',
            'discount' => 10,
            'redemption_points' => 0,
            'conditions' => $conditions,
        ]);
    }
}
