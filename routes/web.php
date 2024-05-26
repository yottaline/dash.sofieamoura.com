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
})->middleware('auth')->name('dashboard');

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
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';