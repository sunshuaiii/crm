<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
}
