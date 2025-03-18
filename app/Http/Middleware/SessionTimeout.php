<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Solo verificar si el usuario está autenticado
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity');
            
            // Si no hay actividad registrada o han pasado más de 15 minutos
            if (!$lastActivity || now()->diffInMinutes($lastActivity) >= 15) {
                Auth::logout();
                Session::flush();
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado debido a inactividad');
            }
            
            // Actualizar el tiempo de la última actividad
            Session::put('last_activity', now());
        }
        
        return $next($request);
    }
}