<?php namespace Infrastructure\Traits; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Infrastructure\TenantScope\Exceptions\TenantModelNotFoundException;
use Infrastructure\TenantScope\TenantScope;

trait MultiTenantScopable
{
    public $tenantDefaultField = 'user_id';

    public static function bootMultiTenantScopable()
    {
        $tenantScope = App::make('Infrastructure\TenantScope\TenantScope');

        static::addGlobalScope($tenantScope);

        static::creating(function (Model $model) use ($tenantScope) {
            $tenantScope->creating($model);
        });
    }

    /**
     * @return mixed
     */
    public static function allTenants()
    {
        return with(new static())->newQueryWithoutScope(new TenantScope());
    }
    /**
     * @return string
     */
    public function getTenantColumns()
    {
        return isset($this->tenantColumns) ? $this->tenantColumns : $this->tenantDefaultField;
    }

    /**
     * @param $tenantColumn
     * @param $tenantId
     *
     * @return string
     */
    public function getTenantWhereClause($tenantColumn, $tenantId)
    {
        return "{$this->getTable()}.{$tenantColumn} = '{$tenantId}'";
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'])
    {
        try {
            return parent::findOrFail($id, $columns);
        } catch (ModelNotFoundException $e) {
            throw with(new TenantModelNotFoundException())->setModel(get_called_class());
        }
    }
}