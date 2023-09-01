<?php

namespace App\Auth;

use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\PasswordBroker;

class CustomPasswordBroker extends PasswordBroker
{
    public function sendTheResetLink(array $credentials)
    {
        // Validate the credentials or perform any necessary checks
        // For example, check if the email exists in the customers table

        $user = Customer::where('email', $credentials['email'])->first();

        if (!$user) {
            return static::INVALID_USER;
        }

        // Generate and save a password reset token
        $token = Str::random(64);
        $this->tokens->create($user, $token);

        // Send the reset link notification
        $user->sendPasswordResetNotification($token);

        return static::RESET_LINK_SENT;
    }
}
