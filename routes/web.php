<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::controller(AuthController::class)->group(function () {
	Route::get('login', 'showLoginForm')->name('login');
	Route::post('login', 'login')->name('login.post');
	Route::get('register', 'showRegisterForm')->name('register');
	Route::post('register', 'register')->name('register.post');
	Route::post('logout', 'logout')->name('logout');
});

