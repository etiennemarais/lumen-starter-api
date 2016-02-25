<?php
namespace Starter\Users;

use Illuminate\Support\Str;
use Starter\Repository;

class UsersRepository extends Repository
{
    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param $apiKey
     * @return User
     */
    public function findWithApiKey($apiKey)
    {
        return $this->model->where('api_key', $apiKey)->first();
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function createWithAttributes(array $attributes)
    {
        $attributes = array_merge([
            'api_key' => Str::random(25),
        ], $attributes);

        return parent::createWithAttributes($attributes);
    }
}
