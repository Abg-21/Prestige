<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function hasPermission($modulo, $accion = 'ver')
    {
        $usuario = Auth::user();
        if (!$usuario || !$usuario->roles || $usuario->roles->isEmpty()) {
            return false;
        }

        // Si el usuario tiene el rol 'Administrador', siempre tiene todos los permisos
        foreach ($usuario->roles as $rol) {
            if (strtolower($rol->nombre) === 'administrador') {
                return true;
            }
        }

        // Para otros roles, validar los permisos asignados
        foreach ($usuario->roles as $rol) {
            $permisos = is_string($rol->permisos) ? json_decode($rol->permisos, true) : $rol->permisos;
            if (is_array($permisos) && isset($permisos[$modulo][$accion]) && $permisos[$modulo][$accion]) {
                return true;
            }
        }
        return false;
    }
}