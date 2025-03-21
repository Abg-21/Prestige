<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionTimeout = 15 * 60; // 15 minutos
            $lastActivity = Session::get('last_activity', now());

            if (now()->diffInSeconds($lastActivity) > $sessionTimeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Tu sesión ha expirado.');
            }

            Session::put('last_activity', now());
        }

        return $next($request);
    }
}
