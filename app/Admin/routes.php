<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

//    $router->group(['prefix' => 'order'], function ($router) {
//        $router->get('/', 'OrderController@index');
//        $router->get('/{id}', 'OrderController@show');
//        $router->put('/{id}', 'OrderController@update');
//        $router->post('/', 'OrderController@store');
//    });

    $router->resource('warehouses', WarehouseController::class);
    $router->resource('sku-inventories', SkuInventoryController::class);
    $router->resource('purchases', PurchaseController::class);
    $router->resource('skus', SkuController::class);

    $router->group(['prefix' => 'purchase'], function ($router) {
        $router->get('/', 'PurchaseController@index');
        $router->get('/{id}', 'PurchaseController@show');
        $router->put('/{id}', 'PurchaseController@update');
        $router->post('/', 'PurchaseController@store');
    });

    $router->resource('what-apps', WhatAppController::class);
//    $router->post('/products', 'ProductController@');
//    $router->post('/products/create', 'ProductController@create');
//    $router->get('/products/{id}', 'ProductController@queryfhOrder');
//    $router->put('/products/{id}', 'ProductController@queryfhOrder');
    $router->resource('products', ProductController::class);
    $router->post('/products/edit/{id}', 'ProductController@update');
    $router->resource('categories', CategoryController::class);
    $router->resource('options', OptionContoller::class);
    $router->resource('orders', OrderController::class);
    $router->resource('owing', OwingController::class);
    $router->resource('sku-returns', SkuReturnController::class);



    $router->get('export/template/{name}', 'ExportController@template');

    $router->get('api/category', 'CategoryController@category');
    $router->get('api/sku', 'SkuController@sku');
    $router->post('api/upload', 'FileController@upload');

});
