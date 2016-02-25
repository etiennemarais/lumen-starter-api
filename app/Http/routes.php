<?php

$app->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($app) {

    // User resources
    $app->get('/user', ['uses' => 'App\Http\Controllers\UserController@index']);
    $app->post('/user', ['uses' => 'App\Http\Controllers\UserController@create']);

});

# Catch all
$app->get('/', ['uses' => 'ApiController@welcome', 'middleware' => 'auth']);
