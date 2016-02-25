<?php

use Illuminate\Support\Facades\Config;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function turnOffMiddleware()
    {
        $this->app->instance('middleware.disable', true);
    }

    /**
     * @param $class
     * @return Mockery\MockInterface
     */
    protected function mock($class)
    {
        $mock = Mockery::mock($class);

        $this->app->instance($class, $mock);

        return $mock;
    }

    /**
     * Sets up the basic user dependency that gets used all over the app.
     */
    protected function setupCountryDependency()
    {
        factory(\Starter\Users\User::class)->create();
        // Set the testing country tenant identifier.
        app('Infrastructure\TenantScope\TenantScope')->addTenant('user_id', 1);
        Config::set('user_id', 1);
    }
}
