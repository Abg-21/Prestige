<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Middleware\CheckPermissions;

class ProbarMiddleware extends Command
{
    protected $signature = 'test:middleware';
    protected $description = 'Probar el middleware de permisos';

    public function handle()
    {
        $this->info('🔍 Probando Middleware CheckPermissions...');
        
        try {
            // Crear una instancia del middleware
            $middleware = new CheckPermissions();
            $this->info('✅ Middleware CheckPermissions se puede instanciar correctamente');
            
            // Verificar métodos
            if (method_exists($middleware, 'handle')) {
                $this->info('✅ Método handle() existe');
            } else {
                $this->error('❌ Método handle() NO existe');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error al crear middleware: ' . $e->getMessage());
        }
        
        return 0;
    }
}