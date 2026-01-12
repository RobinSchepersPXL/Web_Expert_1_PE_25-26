<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\PasswordResetRequestController;
use App\Http\Controllers\Auth\PasswordUpdateController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\EventsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketReservationController;
use App\Http\Controllers\ProfileController;

use App\Http\Middleware\EnsureAuthenticated;

/*
|--------------------------------------------------------------------------
| Redirect root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register');

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated (dashboard + logout)
|--------------------------------------------------------------------------
*/
Route::middleware(EnsureAuthenticated::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Password reset (Laravel standaard)
|--------------------------------------------------------------------------
*/
Route::get('/password/reset', [PasswordResetRequestController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/password/email', [PasswordResetRequestController::class, 'store'])
    ->name('password.email');

Route::get('/password/reset/{token}', [PasswordUpdateController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/password/reset', [PasswordUpdateController::class, 'update'])
    ->name('password.update');

/*
|--------------------------------------------------------------------------
| Events – PUBLIC (index)
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventsController::class, 'index'])
    ->name('events.index');

/*
|--------------------------------------------------------------------------
| Auth-only routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Events CRUD (admin via policy)
    Route::get('/events/create', [EventsController::class, 'create'])
        ->name('events.create');
    Route::post('/events', [EventsController::class, 'store'])
        ->name('events.store');

    Route::get('/events/{event}/edit', [EventsController::class, 'edit'])
        ->name('events.edit');
    Route::put('/events/{event}', [EventsController::class, 'update'])
        ->name('events.update');
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])
        ->name('events.destroy');

    // Favorite toggle
    Route::post('/events/{event}/favorite', [EventsController::class, 'toggleFavorite'])
        ->name('events.favorite.toggle');

    // Tickets CRUD (admin-owner via policy in TicketsController)
    Route::get('/events/{event}/tickets/create', [TicketsController::class, 'create'])
        ->name('tickets.create');
    Route::post('/events/{event}/tickets', [TicketsController::class, 'store'])
        ->name('tickets.store');

    Route::get('/tickets/{ticket}/edit', [TicketsController::class, 'edit'])
        ->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketsController::class, 'update'])
        ->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketsController::class, 'destroy'])
        ->name('tickets.destroy');

    // Ticket reservatie
    Route::post('/tickets/{ticket}/reserve', [TicketReservationController::class, 'store'])
        ->name('tickets.reserve');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Events – SHOW (must be after /events/create)
|--------------------------------------------------------------------------
*/
Route::get('/events/{event}', [EventsController::class, 'show'])
    ->whereNumber('event')
    ->name('events.show');

/*
|--------------------------------------------------------------------------
| Debug (optional)
|--------------------------------------------------------------------------
*/
Route::get('/test-session', function () {
    session(['test_key' => 'test_value']);

    return response()->json([
        'stored' => 'test_value',
        'retrieved' => session('test_key'),
        'auth_check' => Auth::check(),
        'user_id' => Auth::id(),
    ]);
});
