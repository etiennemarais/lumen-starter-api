<?php namespace Infrastructure\TenantScope\Facades;

use Illuminate\Support\Facades\Facade;

class TenantScopeFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Infrastructure\TenantScope\TenantScope';
    }
}