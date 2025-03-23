<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('lastActivity')) {
            Session::put('lastActivity', time());
        }

        if (time() - Session::get('lastActivity') > 1800) { // 30 minutos
            Session::forget('lastActivity');
            auth()->logout();
            return redirect()->route('login')->with('error', 'Sesión expirada');
        }

        Session::put('lastActivity', time());
        return $next($request);
    }
}
