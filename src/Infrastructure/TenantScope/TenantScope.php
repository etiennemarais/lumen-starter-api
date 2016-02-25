<?php namespace Infrastructure\TenantScope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
use Infrastructure\TenantScope\Exceptions\TenantColumnUnknownException;

class TenantScope implements ScopeInterface
{
    private $enabled = true;
    private $model;
    protected $tenants = [];

    /**
     * @return array
     */
    public function getTenants()
    {
        return $this->tenants;
    }

    /**
     * @param string $tenantColumn
     * @param mixed $tenantId
     *
     * @return void
     */
    public function addTenant($tenantColumn, $tenantId)
    {
        $this->enable();
        $this->tenants[$tenantColumn] = $tenantId;
    }

    /**
     * @param string $tenantColumn
     *
     * @return boolean
     */
    public function removeTenant($tenantColumn)
    {
        if ($this->hasTenant($tenantColumn)) {
            unset($this->tenants[$tenantColumn]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $tenantColumn
     *
     * @return boolean
     */
    public function hasTenant($tenantColumn)
    {
        return isset($this->tenants[$tenantColumn]);
    }

    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if ($this->enabled === false) {
            return;
        }

        foreach ($this->getModelTenants($model) as $tenantColumn => $tenantId) {
            $builder->whereRaw(
                $model->getTenantWhereClause($tenantColumn, $tenantId)
            );
        }
    }

    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();

        foreach ($this->getModelTenants($model) as $tenantColumn => $tenantId) {
            foreach ((array)$query->wheres as $key => $where) {
                if ($this->isTenantConstraint($model, $where, $tenantColumn, $tenantId)) {
                    unset($query->wheres[$key]);
                    $query->wheres = array_values($query->wheres);
                    break;
                }
            }
        }
    }

    /**
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if (!$model->hasGlobalScope($this)) {
            return;
        }
        foreach ($this->getModelTenants($model) as $tenantColumn => $tenantId) {
            $model->{$tenantColumn} = $tenantId;
        }
    }

    /**
     * @param Model $model
     *
     * @return array
     * @throws TenantColumnUnknownException
     */
    public function getModelTenants(Model $model)
    {
        $modelTenantColumns = (array)$model->getTenantColumns();
        $modelTenants = [];
        foreach ($modelTenantColumns as $tenantColumn) {
            if ($this->hasTenant($tenantColumn)) {
                $modelTenants[$tenantColumn] = $this->getTenantId($tenantColumn);
            }
        }
        return $modelTenants;
    }

    /**
     * @param $tenantColumn
     *
     * @return mixed
     * @throws TenantColumnUnknownException
     */
    public function getTenantId($tenantColumn)
    {
        if (!$this->hasTenant($tenantColumn)) {
            throw new TenantColumnUnknownException(
                get_class($this->model) . ': tenant column "' . $tenantColumn . '" NOT found in tenants scope "' . json_encode($this->tenants) . '"'
            );
        }
        return $this->tenants[$tenantColumn];
    }

    /**
     * @param Model $model
     * @param array $where
     * @param $tenantColumn
     * @param $tenantId
     *
     * @return bool
     */
    public function isTenantConstraint(Model $model, array $where, $tenantColumn, $tenantId)
    {
        return $where['type'] == 'raw' && $where['sql'] == $model->getTenantWhereClause($tenantColumn, $tenantId);
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function enable()
    {
        $this->enabled = true;
    }
}
