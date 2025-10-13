<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ProbarLogin extends Command
{
    protected $signature = 'test:login {email}';
    protected $description = 'Probar login de usuario y verificar acceso a rutas';

    public function handle()
    {
        $email = $this->argument('email');
        $usuario = Usuario::where('correo', $email)->with('roles')->first();
        
        if (!$usuario) {
            $this->error("Usuario no encontrado: {$email}");
            return 1;
        }
        
        // Simular login
        Auth::login($usuario);
        
        $this->info("✅ Usuario logueado: {$usuario->nombre}");
        
        // Verificar si puede acceder a giros
        $helper = new \App\Helpers\PermissionHelper();
        $puedeVerGiros = $helper::hasPermission('giros', 'ver');
        
        $this->info("Puede ver giros: " . ($puedeVerGiros ? 'SÍ' : 'NO'));
        
        // Verificar si la ruta existe
        if (Route::has('giros.index')) {
            $this->info("✅ Ruta giros.index existe");
            
            // Intentar generar la URL
            try {
                $url = route('giros.index');
                $this->info("URL generada: {$url}");
            } catch (\Exception $e) {
                $this->error("Error al generar URL: " . $e->getMessage());
            }
        } else {
            $this->error("❌ Ruta giros.index NO existe");
        }
        
        return 0;
    }
}