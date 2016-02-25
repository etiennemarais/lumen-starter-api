<?php
namespace Starter\Users;

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
}
