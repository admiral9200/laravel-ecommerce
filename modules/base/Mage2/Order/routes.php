<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
Route::group(['middleware' => ['web', 'adminauth', 'website'], 'namespace' => "Mage2\Order\Controllers"], function () {


    Route::resource('/admin/order-status', 'OrderStatusController', ['names' => [
            'index' => 'admin.order-status.index',
            'create' => 'admin.order-status.create',
            'store' => 'admin.order-status.store',
            'edit' => 'admin.order-status.edit',
            'update' => 'admin.order-status.update',
            'destroy' => 'admin.order-status.destroy',
    ]]);

    Route::get('/admin/order', ['as' => 'admin.order.index', 'uses' => 'OrderController@adminindex']);
    Route::get('/admin/order/{id}', ['as' => 'admin.order.view', 'uses' => 'OrderController@view']);
});


Route::group(['middleware' => ['web', 'frontauth', 'website'], 'namespace' => "Mage2\Order\Controllers"], function () {

    Route::get('/order', ['as' => 'order.index', 'uses' => 'OrderController@index']);
    Route::get('/order/success/{id}', ['as' => 'order.success', 'uses' => 'OrderController@success']);

});