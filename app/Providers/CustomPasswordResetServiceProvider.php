<?php

namespace App\Providers;

use App\Auth\CustomPasswordBroker as AuthCustomPasswordBroker;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\ServiceProvider;

class CustomPasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Define your custom password broker for the "customers" table
        Password::broker('customers')->setProvider(function ($app, array $config) {
            return new AuthCustomPasswordBroker(
                $app['auth'],
                $app['request']->input('email'),
                $config['table']
            );
        });
    }
}
