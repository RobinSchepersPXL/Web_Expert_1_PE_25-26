<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // Als gebruiker al is ingelogd, redirect naar events
        if (Auth::check()) {
            return redirect()->route('events.index');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Valideer de input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Haal de gebruiker op basis van email
        $user = User::where('email', $credentials['email'])->first();

        // Check of gebruiker bestaat en wachtwoord klopt
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Log de gebruiker in (start session)
            Auth::login($user, $request->filled('remember'));

            // Regenerate session ID voor security
            $request->session()->regenerate();

            // BELANGRIJK: Redirect naar intended URL of naar events index
            return redirect()->intended(route('events.index'));
        }

        // Als login faalt, ga terug met error
        return back()->withErrors([
            'email' => 'De inloggegevens zijn onjuist.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate de session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Je bent uitgelogd.');
    }
}
