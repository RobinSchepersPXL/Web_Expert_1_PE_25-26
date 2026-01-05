<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetRequestController;
use App\Http\Controllers\Auth\PasswordUpdateController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

// home
Route::get('/home', function () {
    return view('home');
})->name('home');

// guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::middleware(EnsureAuthenticated::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
Route::get('password/reset', [PasswordResetRequestController::class, 'showRequestForm'])->name('password.request');
Route::post('password/email', [PasswordResetRequestController::class, 'store'])->name('password.email');

Route::get('password/reset/{token}', [PasswordUpdateController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset', [PasswordUpdateController::class, 'update'])->name('password.update');
