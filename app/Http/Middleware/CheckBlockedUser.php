<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBlockedUser
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->withErrors([
                'email' => 'Tavs konts ir bloķēts. Sazinies ar administratoru.',
            ]);
        }

        return $next($request);
    }
}
