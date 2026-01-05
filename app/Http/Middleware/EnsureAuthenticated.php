<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('user_id')) {
            // preserve intended url
            return redirect()->route('login.show')->with('redirect_to', $request->fullUrl());
        }

        return $next($request);
    }
}
