<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $user = Customer::where('google_id', $google_user->getId())->first();

            if (!$user) {
                // Create a new user with Google information
                $newUser = Customer::create([
                    'username' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => (string) $google_user->getId(),
                    'first_name' => $google_user->user['given_name'],
                    'last_name' => $google_user->user['family_name'],
                ]);

                $newUser->points = 300;
                $newUser->save();

                $couponCode = $this->generateCouponCode();

                // Calculate end date (30 days from now)
                $endDate = Carbon::now()->addDays(30);

                // Create a new customer_coupon record
                $customerCoupon = new CustomerCoupon();
                $customerCoupon->customer_id = $newUser->id;
                $customerCoupon->coupon_id = 8;
                $customerCoupon->status = 'Claimed';
                $customerCoupon->code = $couponCode;
                $customerCoupon->start_date = Carbon::now();
                $customerCoupon->end_date = $endDate;
                $customerCoupon->save();

                // Log in the newly created user
                Auth::guard('customer')->login($newUser);

                return redirect()->intended('/customer')->with('success', 'Login successful.');
            } else {
                // Log in the existing user
                Auth::guard('customer')->login($user);

                return redirect()->intended('/customer')->with('success', 'Login successful.');
            }
        } catch (\Throwable $th) {
            dd('Something went wrong! ' . $th->getMessage());
        }
    }

    // Helper function to generate a unique coupon code using a combination of random numbers and timestamp
    private function generateCouponCode()
    {
        $timestamp = now()->timestamp; // Get current timestamp
        $randomDigits = '';

        // Generate random digits
        for ($i = 0; $i < 5; $i++) {
            $randomDigits .= rand(0, 9);
        }

        // Combine timestamp and random digits to create a unique code
        $couponCode = $timestamp . $randomDigits;

        // Check if the coupon code already exists
        while (CustomerCoupon::where('code', $couponCode)->exists()) {
            // If it exists, regenerate the random digits
            $randomDigits = '';
            for ($i = 0; $i < 5; $i++) {
                $randomDigits .= rand(0, 9);
            }

            // Combine timestamp and new random digits
            $couponCode = $timestamp . $randomDigits;
        }

        return $couponCode;
    }
}
