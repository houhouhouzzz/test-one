<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/{spu}', function () {
//    return view('welcome');
//});

Route::get('/category/{id}', 'CategoryController@index');
Route::get('/product/{id}/{country?}', 'ProductController@index');
Route::get('/list/product/{id}', 'ProductController@product_list');
Route::get('/return/{country?}', 'ReturnController@index');

Route::post('/order', 'OrderController@store');

Route::get('/{service_name}', 'TermServiceController@index');


