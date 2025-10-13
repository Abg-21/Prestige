<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

class DiagnosticoCompleto extends Command
{
    protected $signature = 'sistema:diagnostico';
    protected $description = 'Diagnóstico completo del sistema de usuarios y permisos';

    public function handle()
    {
        $this->info('🔍 DIAGNÓSTICO COMPLETO DEL SISTEMA');
        $this->info('===================================');
        $this->line('');

        // 1. Verificar usuarios
        $this->info('1. 👤 VERIFICANDO USUARIOS:');
        $usuarios = Usuario::with('roles')->get();
        $this->info("   Total usuarios: {$usuarios->count()}");
        
        foreach ($usuarios as $usuario) {
            $this->line("   • {$usuario->nombre} ({$usuario->correo})");
            if ($usuario->roles->isNotEmpty()) {
                $this->line("     Rol: {$usuario->roles->first()->nombre}");
            } else {
                $this->error("     ❌ Sin rol asignado");
            }
        }
        $this->line('');

        // 2. Verificar rutas críticas
        $this->info('2. 🛣️ VERIFICANDO RUTAS CRÍTICAS:');
        $rutas = ['giros.index', 'clientes.index', 'documentos.index', 'candidatos.index', 'empleados.index', 'check.session', 'update.activity'];
        
        foreach ($rutas as $ruta) {
            if (Route::has($ruta)) {
                $this->info("   ✅ {$ruta}");
            } else {
                $this->error("   ❌ {$ruta}");
            }
        }
        $this->line('');

        // 3. Verificar permisos del admin
        $this->info('3. 🔐 VERIFICANDO PERMISOS DEL ADMINISTRADOR:');
        $admin = Usuario::where('correo', 'admin@prestige.com')->with('roles')->first();
        
        if ($admin && $admin->roles->isNotEmpty()) {
            $permisos = $admin->roles->first()->permisos;
            $modulos = ['giros', 'clientes', 'documentos', 'candidatos', 'empleados'];
            
            foreach ($modulos as $modulo) {
                if (isset($permisos[$modulo]['ver']) && $permisos[$modulo]['ver']) {
                    $this->info("   ✅ {$modulo}: puede ver");
                } else {
                    $this->error("   ❌ {$modulo}: NO puede ver");
                }
            }
        } else {
            $this->error("   ❌ Admin no encontrado o sin permisos");
        }
        $this->line('');

        // 4. Verificar archivos críticos
        $this->info('4. 📁 VERIFICANDO ARCHIVOS CRÍTICOS:');
        $archivos = [
            'app/Http/Controllers/GiroController.php',
            'app/Http/Controllers/ClienteController.php',
            'app/Http/Controllers/DocumentoController.php',
            'app/Http/Middleware/CheckPermissions.php',
            'app/Helpers/PermissionHelper.php',
            'resources/views/auth/menu.blade.php'
        ];
        
        foreach ($archivos as $archivo) {
            if (file_exists(base_path($archivo))) {
                $this->info("   ✅ {$archivo}");
            } else {
                $this->error("   ❌ {$archivo}");
            }
        }
        $this->line('');

        // 5. Generar URLs de prueba
        $this->info('5. 🔗 GENERANDO URLS DE PRUEBA:');
        try {
            $urls = [
                'giros.index' => route('giros.index'),
                'clientes.index' => route('clientes.index'),
                'documentos.index' => route('documentos.index'),
                'menu' => route('menu')
            ];
            
            foreach ($urls as $nombre => $url) {
                $this->info("   ✅ {$nombre}: {$url}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Error generando URLs: " . $e->getMessage());
        }
        
        $this->line('');
        $this->info('🎯 RECOMENDACIONES:');
        $this->info('1. Limpiar cache del navegador');
        $this->info('2. Cerrar sesión y volver a iniciar');
        $this->info('3. Verificar consola del navegador para errores JS');
        $this->info('4. Probar acceso directo: http://localhost:8000/giros');

        return 0;
    }
}