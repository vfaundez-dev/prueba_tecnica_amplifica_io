<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'showLoginForm')->name('login');
	Route::post('login', 'login')->name('login.post');
	Route::get('register', 'showRegisterForm')->name('register');
	Route::post('register', 'register')->name('register.post');
	Route::post('logout', 'logout')->name('logout');
});

// Protected Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Shop Routes
    Route::controller(ShopController::class)->prefix('shop')->group(function () {
        Route::get('/', 'index')->name('shop.index');
        Route::post('/store', 'store')->name('shop.store');
    });

});




