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
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/order', 'OrderController@index')->name('order');
    Route::post('/searchProducts','OrderController@searchProducts');
    Route::post('/myOrders','OrderController@myOrders');
    Route::post('/buyProduct','OrderController@buyProduct');
    Route::get('/endProcessPay','OrderController@endProcessPay');
    Route::group(['middleware' => ['AuthRol']], function(){
        Route::get('/ordershop', 'OrderShopController@index')->name('ordershop');
        Route::post('/myOrderShop', 'OrderShopController@orders');
    });
});

