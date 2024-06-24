<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('dashboard'))->middleware('auth');

Route::middleware('auth')->group(function () {
    // locations
    Route::prefix('locations')->group(function () {
        Route::get('/', 'LocationController@index');
        Route::post('load', 'LocationController@load');
        Route::match(['post', 'put'], 'submit', 'LocationController@submit');
        Route::put('change_visible', 'LocationController@changeVisible');
    });

    // currencies
    Route::prefix('currencies')->group(function () {
        Route::get('/', 'CurrencyController@index');
        Route::post('load', 'CurrencyController@load');
        Route::match(['post', 'put'], 'submit', 'CurrencyController@submit');
    });

    // retailers
    Route::prefix('retailers')->group(function () {
        Route::get('/', 'RetailerController@index');
        Route::post('load', 'RetailerController@load');
        Route::match(['post', 'put'], 'submit', 'RetailerController@submit');
        Route::match(['put', 'get'], 'edit_approved', 'RetailerController@editApproved');
    });

    // categories
    Route::prefix('categories')->group(function () {
        Route::get('/', 'CategoryController@index');
        Route::post('load', 'CategoryController@load');
        Route::match(['post', 'put'], 'submit', 'CategoryController@submit');
    });

    // seasons
    Route::prefix('seasons')->group(function () {
        Route::get('/', 'SeasonController@index');
        Route::post('load', 'SeasonController@load');
        Route::match(['post', 'put'], 'submit', 'SeasonController@submit');
    });

    // sizes
    Route::prefix('sizes')->group(function () {
        Route::get('/', 'SizeController@index');
        Route::post('load', 'SizeController@load');
        Route::match(['post', 'put'], 'submit', 'SizeController@submit');
    });

    // ws products
    Route::prefix('ws_products')->group(function () {
        Route::get('/', 'WsProductController@index');
        Route::get('view/{ref}', 'WsProductController@view');
        Route::post('load', 'WsProductController@load');
        Route::match(['post', 'put'], 'submit', 'WsProductController@submit');
        Route::post('orders', 'WsProductController@order');
    });

    // ws product sizes
    Route::prefix('product_sizes')->group(function () {
        Route::post('load', 'WsProductsSizeController@load');
        Route::match(['post', 'put'], 'submit', 'WsProductsSizeController@submit');
        Route::post('edit_status', 'WsProductsSizeController@editStatus');
        Route::post('update', 'WsProductsSizeController@update');
    });

    Route::prefix('product_medias')->group(function () {
        Route::post('load', 'ProductsMediaController@load');
        Route::match(['post', 'put'], 'submit', 'ProductsMediaController@submit');
        Route::post('order', 'ProductsMediaController@updateOrder');
        Route::post('image_default', 'ProductsMediaController@imageDefault');
        Route::post('get_color', 'ProductsMediaController@getColor');
    });

    // ws-orders
    Route::prefix('ws_orders')->group(function () {
        Route::get('/', 'WsOrderController@index');
        Route::get('create', 'WsOrderController@create');
        Route::post('load', 'WsOrderController@load');
        Route::post('get_product', 'WsOrderController@getProduct');
        Route::match(['post', 'put'], 'submit', 'WsOrderController@submit');
        Route::post('change_status', 'WsOrderController@updateStatus');
        Route::post('update_qty', 'WsOrderController@updateQty');
        Route::post('del_product', 'WsOrderController@delProduct');
        Route::post('del_size', 'WsOrderController@delSize');
        Route::get('view/{id}', 'WsOrderController@view');
        Route::get('export', 'WsOrderController@export');
        Route::get('get_confirmed/{id}', 'WsOrderController@Confirmed');
        Route::get('get_proforma/{id}', 'WsOrderController@Proforma');
        Route::get('invoice/{id}', 'WsOrderController@invoice');
        Route::get('get_retailer/{id}', 'WsOrderController@getRetailer');
    });

    Route::get('test', 'WsOrderController@test');
    Route::prefix('users')->group(function () {
        Route::get('/', 'UserController@index');
        Route::post('load', 'UserController@load');
        Route::match(['post', 'put'], 'submit', 'UserController@submit');
    });
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';