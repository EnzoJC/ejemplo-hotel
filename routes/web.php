<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Auth0IndexController;
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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::view('/', 'home');
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/auth0/callback', [Auth0IndexController::class, 'callback'])->name('auth0-callback');
    Route::get('/login', [Auth0IndexController::class, 'login'])->name('login');
    Route::get('/logout', [Auth0IndexController::class, 'logout'])->name('logout')->middleware('auth');

    Route::group(['prefix' => 'dashboard'], function() {
        Route::view('/', 'dashboard/dashboard');
        Route::get('reservations/create/{id}', [ReservationController::class, 'create']);
        Route::resource('reservations', ReservationController::class)->except('create');
    });
});
