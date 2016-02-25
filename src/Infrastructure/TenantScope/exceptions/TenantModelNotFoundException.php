<?php namespace Infrastructure\TenantScope\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TenantModelNotFoundException extends ModelNotFoundException
{
    /**
     * @param $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        $this->message = "No query results for model [{$model}] when scoped by tenant.";
        return $this;
    }
}