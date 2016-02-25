<?php

$app->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($app) {
    // TODO Add Drivelog routes here

});

# Catch all
$app->get('/', ['uses' => 'ApiController@welcome', 'middleware' => 'auth']);
