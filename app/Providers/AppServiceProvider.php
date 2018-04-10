<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom validation
        Validator::extend('bd_mobile', function ($attribute, $value, $parameters, $validator) {
            $sub = substr($value, 0, 5);
            return in_array($sub, [88015, 88016, 88017, 88018, 88019]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
