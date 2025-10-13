<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;

class VerificarPermisos extends Command
{
    protected $signature = 'usuarios:permisos {email}';
    protected $description = 'Verificar permisos de un usuario';

    public function handle()
    {
        $email = $this->argument('email');
        $usuario = Usuario::where('correo', $email)->with('roles')->first();
        
        if (!$usuario) {
            $this->error("Usuario no encontrado: {$email}");
            return 1;
        }
        
        $this->info("Usuario: {$usuario->nombre}");
        $this->info("Email: {$usuario->correo}");
        
        if ($usuario->roles->isEmpty()) {
            $this->error("El usuario no tiene roles asignados");
            return 1;
        }
        
        foreach ($usuario->roles as $rol) {
            $this->info("Rol: {$rol->nombre}");
            $this->info("Permisos:");
            
            $permisos = $rol->permisos;
            foreach ($permisos as $modulo => $acciones) {
                $this->line("  {$modulo}:");
                foreach ($acciones as $accion => $permitido) {
                    $estado = $permitido ? '✅' : '❌';
                    $this->line("    {$estado} {$accion}");
                }
            }
        }
        
        return 0;
    }
}