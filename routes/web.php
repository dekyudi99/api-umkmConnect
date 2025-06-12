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
    $router->get('/users/getall', 'UsersController@index');
    $router->post('/users/save', 'UsersController@store');
    $router->get('/users/detail/{id}', 'UsersController@show');
    $router->post('/users/update/{id}', 'UsersController@update');
    $router->delete('/users/delete/{id}', 'UsersController@destroy');

    // Contents Management
    $router->get('/contents/getall', 'ContentsController@index');
    $router->post('/contents/save', 'ContentsController@store');
    $router->get('/contents/detail/{id}', 'ContentsController@show');
    $router->post('/contents/update/{id}', 'ContentsController@update');
    $router->delete('/contents/delete/{id}', 'ContentsController@delete');
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
    
    // Progress User
    $router->post('/progress/{id}', 'UserProgressController@index');
    
    // Get & Detail & Delete Product
    $router->delete('/product/delete/{id}', 'ProductsController@destroy');
    $router->get('/product/detail/{id}', 'ProductsController@show');
    $router->get('/product/getall', 'ProductsController@index');
});

$router->group(['middleware' => ['auth:api', 'role:normal']], function () use ($router) {
    // Management Product
    $router->get('/product/myproduct', 'ProductsController@myProducts');
    $router->post('/product/save', 'ProductsController@store');
    $router->post('/product/update', 'ProductsController@update');
});

$router->get('/profile/{filename}', function ($filename) {
    $path = storage_path('app/public/profile/'.$filename.'.jpg');

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->file($path);
});

$router->get('/product/{filename}', function ($filename) {
    $path = storage_path('app/public/product/' . $filename.'.jpg');

    if (!file_exists($path)) {
        abort(404, 'File not found');
    }

    return response()->file($path);
});