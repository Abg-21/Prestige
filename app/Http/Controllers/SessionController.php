<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verificar si la sesión ha expirado - Ultra optimizado
     */
    public function checkSession()
    {
        // Cache para evitar consultas repetitivas
        $cacheKey = 'session_check_' . auth()->id();
        
        return \Cache::remember($cacheKey, 30, function() {
            $lastActivity = session('last_activity', time());
            $timeout = config('session.lifetime') * 60;
            
            $expired = $lastActivity && (time() - $lastActivity > $timeout);
            return response()->json(['expired' => $expired]);
        });
    }

    /**
     * Actualizar la actividad del usuario - Ultra optimizado
     */
    public function updateActivity()
    {
        // Cache ultra rápido - solo actualiza cada 30 segundos
        $cacheKey = 'user_activity_' . auth()->id();
        $lastUpdate = \Cache::get($cacheKey, 0);
        
        if (time() - $lastUpdate > 30) {
            \Cache::put($cacheKey, time(), 300); // 5 minutos
            session(['last_activity' => time()]);
        }
        
        return response('', 204); // Sin contenido, más rápido
    }
}
