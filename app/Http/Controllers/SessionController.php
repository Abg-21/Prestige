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
     * Verificar si la sesión ha expirado
     */
    public function checkSession()
    {
        $user = auth()->user();
        $lastActivity = session('last_activity');
        $timeout = config('session.lifetime') * 60; // Convertir minutos a segundos

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            return response()->json(['expired' => true]);
        }

        return response()->json(['expired' => false]);
    }

    /**
     * Actualizar la actividad del usuario
     */
    public function updateActivity()
    {
        session(['last_activity' => time()]);
        return response()->json(['success' => true]);
    }
}
