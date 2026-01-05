<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::middleware(EnsureAuthenticated::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth.session');

Route::middleware('auth.session')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
