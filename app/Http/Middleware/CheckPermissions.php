<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $modulo, $accion = 'ver')
    {
        // Si no hay usuario autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();
        
        // Verificar si el usuario tiene el permiso
        if (!$this->tienePermiso($usuario, $modulo, $accion)) {
            if ($request->ajax()) {
                return response()->json(['error' => 'No tienes permisos para realizar esta acción'], 403);
            }
            
            return redirect()->route('menu')->with('error', 'No tienes permisos para acceder a esta sección');
        }

        return $next($request);
    }

    private function tienePermiso($usuario, $modulo, $accion)
    {
        if (!$usuario->roles || $usuario->roles->isEmpty()) {
            return false;
        }

        foreach ($usuario->roles as $rol) {
            $permisos = is_string($rol->permisos) ? json_decode($rol->permisos, true) : $rol->permisos;
            
            if (is_array($permisos) && 
                isset($permisos[$modulo][$accion]) && 
                $permisos[$modulo][$accion]) {
                return true;
            }
        }

        return false;
    }
}
