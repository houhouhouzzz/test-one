<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::get('admin/orders/once-default-shipping-export', 'OrderController@OnceDefaultShippingExport');

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('warehouses', WarehouseController::class);
    $router->resource('sku-inventories', SkuInventoryController::class);
    $router->resource('purchases', PurchaseController::class);
    $router->resource('skus', SkuController::class);

    $router->resource('what-apps', WhatAppController::class);
    $router->resource('products', ProductController::class);
    $router->post('/products/edit/{id}', 'ProductController@update');
    $router->resource('categories', CategoryController::class);
    $router->resource('options', OptionContoller::class);
    $router->resource('orders', OrderController::class);
    $router->resource('owing', OwingController::class);
    $router->resource('sku-returns', SkuReturnController::class);
    $router->resource('term-services', TermServiceController::class);


    $router->post('orders/once-default-shipping', 'OrderController@OnceDefaultShipping');
    $router->get('orders/once-default-shipping-export', 'OrderController@OnceDefaultShippingExport');

    $router->get('export/template/{name}', 'ExportController@template');

    $router->get('api/category', 'CategoryController@category');
    $router->get('api/sku', 'SkuController@sku');
    $router->post('api/upload', 'FileController@upload');


});
