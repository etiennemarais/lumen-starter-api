<?php

$factory->define(Starter\Users\User::class, function($faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'api_key' => 'apikeyuser',
        'status' => 'enabled',
    ];
});
