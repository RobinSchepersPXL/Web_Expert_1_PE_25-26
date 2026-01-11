<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Je moet ingelogd zijn.');
        }

        $user = auth()->user();


        if (! (isset($user->is_admin) && $user->is_admin) && ! ($user->role ?? null === 'admin')) {
            abort(403, 'Geen toegang.');
        }

        return $next($request);
    }
}
