<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    // show the request form (GET)
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // handle form submission (POST /password/email)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');
        $token = Str::random(64);


        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        return redirect('/password/reset/' . $token . '?email=' . urlencode($email))
            ->with('status', 'Enter a new password for this account.');
    }
}
