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

Route::get('', 'HomeController@index')->name('home');

Route::get('category/{slug}', 'CategoryViewController@view')->name('category.view');
Route::get('product/{slug}', 'ProductViewController@view')->name('product.view');
Route::get('product-search', 'SearchController@result')->name('search.result');

Route::post('add-to-cart', 'CartController@addToCart')->name('cart.add-to-cart');
Route::get('cart/view', 'CartController@view')->name('cart.view');
Route::put('cart/update', 'CartController@update')->name('cart.update');
Route::get('cart/destroy/{id}', 'CartController@destroy')->name('cart.destroy');

Route::get('checkout', 'CheckoutController@index')->name('checkout.index');

Route::get('login', 'LoginController@showLoginForm')->name('login');
Route::post('login', 'LoginController@login')->name('login.post');
Route::get('logout', 'LoginController@logout')->name('logout');
Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.token');
Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'RegisterController@register')->name('register.post');
Route::get('user-activation/{token}/{email}', 'UserActivationController@activateAccount')->name('user.activation');
Route::get('user/resend', 'UserActivationController@resend')->name('user.activation.resend');
Route::post('user/resend', 'UserActivationController@resendPost')->name('user.activation.resend.post');

Route::get('order', 'OrderController@index')->name('order.index');
Route::post('order', 'OrderController@place')->name('order.place');
Route::get('order/success/{order}', 'OrderController@success')->name('order.success');

Route::get('page/{slug}', 'PageController@show')->name('page.show');

Route::middleware('front.auth')
    ->prefix('my-account')
    ->group(function () {
        Route::get('', 'MyAccountController@home')->name('my-account.home');
        Route::get('edit', 'MyAccountController@edit')->name('my-account.edit');
        Route::post('edit', 'MyAccountController@store')->name('my-account.store');
        Route::get('upload-image', 'MyAccountController@uploadImage')->name('my-account.upload-image');
        Route::post('upload-image', 'MyAccountController@uploadImagePost')->name('my-account.upload-image.post');
        Route::get('change-password', 'MyAccountController@changePassword')->name('my-account.change-password');
        Route::post('change-password', 'MyAccountController@changePasswordPost')->name('my-account.change-password.post');

        Route::resource('address', 'AddressController', ['as' => 'my-account']);

        Route::get('order/list', 'OrderController@myAccountOrderList')->name('my-account.order.list');
        Route::get('order/{order}/view', 'OrderController@myAccountOrderView')->name('my-account.order.view');

        Route::get('wishlist/add/{slug}', 'WishlistController@add')->name('wishlist.add');
        Route::get('wishlist', 'WishlistController@mylist')->name('wishlist.list');
        Route::get('wishlist/remove/{slug}', 'WishlistController@destroy')->name('wishlist.remove');
    });
