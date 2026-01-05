<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PasswordUpdateController extends Controller
{
    // GET /password/reset/{token}
    public function showResetForm($token, Request $request)
    {
        $email = $request->query('email', '');
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]); // create view if needed
    }

    // POST /password/reset
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8',
        ]);

        $row = DB::table('password_resets')->where('email', $request->email)->first();

        if (! $row) {
            return back()->withErrors(['email' => 'Invalid token or email.']);
        }

        // token stored hashed
        if (! Hash::check($request->token, $row->token)) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        // expiry (60 minutes)
        if (Carbon::parse($row->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Token expired.']);
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No user found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password reset successful. You can now log in.');
    }
}
