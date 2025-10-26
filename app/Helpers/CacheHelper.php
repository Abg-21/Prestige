<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    // Cache ultrarrápido - 60 minutos
    private static $cacheTime = 3600;
    
    public static function remember($key, $callback)
    {
        return Cache::remember($key, self::$cacheTime, $callback);
    }
    
    public static function getPuestos()
    {
        return self::remember('puestos_ultra_fast', function() {
            return \App\Models\Puesto::select('idPuestos', 'Puesto')
                ->orderBy('Puesto')
                ->get();
        });
    }
    
    public static function getEmpleados()
    {
        return self::remember('empleados_ultra_fast', function() {
            return \App\Models\Empleado::with(['puesto.cliente', 'puesto.giro'])
                ->whereNull('Eliminado_en')
                ->orderBy('created_at', 'asc') // Más viejo arriba
                ->get();
        });
    }
    
    public static function getCandidatos()
    {
        return self::remember('candidatos_ultra_fast', function() {
            return \App\Models\Candidato::with(['puesto.cliente', 'puesto.giro'])
                ->whereNull('Eliminado_en')
                ->orderBy('created_at', 'asc') // Más viejo arriba
                ->get();
        });
    }
    
    public static function clearAll()
    {
        Cache::forget('puestos_ultra_fast');
        Cache::forget('empleados_ultra_fast');
        Cache::forget('candidatos_ultra_fast');
    }
}