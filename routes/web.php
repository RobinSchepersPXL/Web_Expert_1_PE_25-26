<?php

use App\Http\Controllers\Auth\PasswordResetRequestController;
use App\Http\Controllers\Auth\PasswordUpdateController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;


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
Route::get('/admin', function () {
})->middleware(['auth.custom', 'admin']);


Route::get('/admin-direct', function () {
})->middleware(\App\Http\Middleware\EnsureAdmin::class);


// Password reset request (show form) and submit (send email)
Route::get('password/reset', [PasswordResetRequestController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [PasswordResetRequestController::class, 'store'])->name('password.email');

// Password reset form (clicked link) and update (submit new password)
Route::get('password/reset/{token}', [PasswordUpdateController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordUpdateController::class, 'update'])->name('password.update');


// Zorg dat gebruikers ingelogd zijn; de controller authorizeert admin via policy
Route::middleware(['auth'])->group(function () {
    Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
    Route::post('/events', [EventsController::class, 'store'])->name('events.store');
});

// Public event listing and detail routes (accessible to guests)
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');

Route::get('/test-session', function () {
    // Sla iets op in session
    session(['test_key' => 'test_value']);

    // Haal het op
    $value = session('test_key');

    return response()->json([
        'stored' => 'test_value',
        'retrieved' => $value,
        'auth_check' => Auth::check(),
        'user_id' => Auth::id(),
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
});
Route::post('/events/{event}/favorite', [EventsController::class, 'toggleFavorite'])
    ->middleware('auth')
    ->name('events.favorite.toggle');
