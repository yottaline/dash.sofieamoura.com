<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth');

Route::middleware('auth')->group(function(){
    // locations
    Route::prefix('locations')->group(function(){
        Route::get('/', 'LocationController@index');
        Route::post('load', 'LocationController@load');
        Route::match(['post', 'put'], 'submit', 'LocationController@submit');
        Route::put('change_visible', 'LocationController@changeVisible');
    });

    // currencies
    Route::prefix('currencies')->group(function(){
        Route::get('/', 'CurrencyController@index');
        Route::post('load', 'CurrencyController@load');
        Route::match(['post', 'put'], 'submit', 'CurrencyController@submit');
    });

    // retailers
    Route::prefix('retailers')->group(function(){
        Route::get('/', 'RetailerController@index');
        Route::post('load', 'RetailerController@load');
        Route::match(['post', 'put'], 'submit', 'RetailerController@submit');
        Route::put('edit_approved', 'RetailerController@editApproved');
    });

    // categories
    Route::prefix('categories')->group(function(){
        Route::get('/', 'CategoryController@index');
        Route::post('load', 'CategoryController@load');
        Route::match(['post', 'put'], 'submit', 'CategoryController@submit');
    });
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
