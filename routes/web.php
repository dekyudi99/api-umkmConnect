<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Login & Register
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');

$router->group(['middleware' => ['auth:api', 'role:admin']], function () use ($router) {
    // User Management
    $router->get('/users/getAll', 'UsersController@index');
    $router->post('/users/save', 'UsersController@store');
    $router->get('/users/detail/{id}', 'UsersController@show');
    $router->post('/users/update/{id}', 'UsersController@update');
    $router->delete('/users/delete/{id}', 'UsersController@destroy');
});

$router->group(['middleware' => ['auth:api', 'role:normal,admin']], function () use ($router) {
    // Profile User
    $router->post('/user-profile', 'AuthController@me');
    $router->post('/users/updateProfile', 'UsersController@updateMe');
    $router->delete('/users/deleteProfile', 'UsersController@deleteMe');

    //Refresh Token
    $router->post('/refresh', 'AuthController@refresh');

    //Logout
    $router->post('/logout', 'AuthController@logout');
});