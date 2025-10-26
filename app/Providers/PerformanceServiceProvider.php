<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class PerformanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Deshabilitar lazy loading y logs para máximo rendimiento
        Model::preventLazyLoading(true);
        
        // Deshabilitar query logging completamente
        DB::disableQueryLog();

        // Cache de vistas compartidas
        View::composer(['candidatos.*', 'empleados.*'], function ($view) {
            if (!$view->offsetExists('puestos')) {
                $puestos = \Cache::remember('puestos_shared_cache', 600, function() {
                    return \App\Models\Puesto::select('idPuestos', 'Puesto')
                        ->orderBy('Puesto')
                        ->get();
                });
                $view->with('puestos', $puestos);
            }
        });
    }

    public function register()
    {
        // Registrar servicios optimizados
        $this->app->singleton('cache.performance', function ($app) {
            return new \Illuminate\Cache\CacheManager($app);
        });
    }
}