<?php

namespace App\Providers;

use Validator;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^\d{10}$/', $value);
        });
    }
}
