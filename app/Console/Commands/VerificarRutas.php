<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class VerificarRutas extends Command
{
    protected $signature = 'rutas:verificar {nombre?}';
    protected $description = 'Verificar si una ruta específica existe';

    public function handle()
    {
        $nombre = $this->argument('nombre');
        
        if ($nombre) {
            if (Route::has($nombre)) {
                $this->info("✅ La ruta '{$nombre}' existe");
                $route = Route::getRoutes()->getByName($nombre);
                $this->info("   URI: {$route->uri()}");
                $this->info("   Método: " . implode(', ', $route->methods()));
                $this->info("   Acción: {$route->getActionName()}");
                
                $middleware = $route->gatherMiddleware();
                if (!empty($middleware)) {
                    $this->info("   Middleware: " . implode(', ', $middleware));
                }
            } else {
                $this->error("❌ La ruta '{$nombre}' NO existe");
            }
        } else {
            $this->info("Por favor proporciona el nombre de la ruta a verificar");
            $this->info("Ejemplo: php artisan rutas:verificar giros.index");
        }

        return 0;
    }
}