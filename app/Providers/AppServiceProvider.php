<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Infrastructure\TenantScope\TenantScope;

class AppServiceProvider extends ServiceProvider
{
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
