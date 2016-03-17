<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Message\Alert\AlertMessage;
use Infrastructure\Message\AlertMessageProvider;
use Infrastructure\TenantScope\TenantScope;
use Maknz\Slack\Client;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('validator')->extend('region', 'Starter\Validation\Phone@validateRegion');
        app('validator')->replacer('region', function($message, $attribute, $rule, $parameters) {
            return str_replace(':region', env('HOME_REGION', 'ZA'), $message);
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

        $this->app->bind(AlertMessageProvider::class, function() {
            $client = new Client(env('SLACK_WEBHOOK_URL'));

            return new AlertMessage($client);
        });
    }
}
