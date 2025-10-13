<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\Rol;

class MostrarUsuarios extends Command
{
    protected $signature = 'usuarios:mostrar';
    protected $description = 'Mostrar todos los usuarios con sus roles y permisos';

    public function handle()
    {
        $this->info('📊 RESUMEN DE USUARIOS Y ROLES');
        $this->info('================================');
        
        $usuarios = Usuario::with('roles')->get();
        
        foreach ($usuarios as $usuario) {
            $this->line('');
            $this->info("👤 {$usuario->nombre}");
            $this->line("   📧 Email: {$usuario->correo}");
            
            if ($usuario->roles->isNotEmpty()) {
                $rol = $usuario->roles->first();
                $this->line("   🎭 Rol: {$rol->nombre}");
                $this->line("   📝 Descripción: {$rol->descripcion}");
                $this->line("   📋 Permisos:");
                
                $permisos = $rol->permisos ?? [];
                foreach ($permisos as $modulo => $acciones) {
                    $permitidas = array_filter($acciones, function($valor) { return $valor; });
                    if (!empty($permitidas)) {
                        $this->line("      • {$modulo}: " . implode(', ', array_keys($permitidas)));
                    }
                }
            } else {
                $this->warn("   ⚠️  Sin rol asignado");
            }
        }
        
        $this->line('');
        $this->info('📝 CREDENCIALES DE ACCESO:');
        $this->info('   - admin@prestige.com / admin123 (Administrador)');
        $this->info('   - rh@prestige.com / rh123 (RH)');
        $this->info('   - contabilidad@prestige.com / conta123 (Contabilidad)');
        
        return 0;
    }
}