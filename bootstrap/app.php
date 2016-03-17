<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Infrastructure\Application(
    realpath(__DIR__.'/../')
);

$app->withEloquent();
$app->withFacades();

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);
$app->singleton('filesystem', function ($app) {
    return $app->loadComponent('filesystems', Illuminate\Filesystem\FilesystemServiceProvider::class, 'filesystem');
});
$app->bind(Illuminate\Contracts\Filesystem\Factory::class, function($app) {
    return $app['filesystem'];
});
$app->bind(Illuminate\Contracts\Logging\Log::class, function($app) {
    return $app['Psr\Log\LoggerInterface'];
});

# Middleware
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

# Service Providers
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(\Barryvdh\Cors\LumenServiceProvider::class);
$app->register(\Maknz\Slack\SlackServiceProvider::class);
$app->register(Spatie\Backup\BackupServiceProvider::class);
$app->configure('app');
$app->configure('cors');
$app->configure('filesystems');
$app->configure('laravel-backup');
$app->configure('queue');
$app->configure('slack');

# Routes
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
