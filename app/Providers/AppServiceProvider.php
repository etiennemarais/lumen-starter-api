<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Infrastructure\TenantScope\TenantScope;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('validator')->extend('region', 'Starter\Validation\Phone@validateRegion');
        app('validator')->replacer('region', function($message, $attribute, $rule, $parameters) {
            return str_replace(':region', Config::get('country_iso'), $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'local') {
            DB::enableQueryLog();
        }

        $this->app->singleton('Infrastructure\TenantScope\TenantScope', function ($app) {
            return new TenantScope();
        });
    }
}
