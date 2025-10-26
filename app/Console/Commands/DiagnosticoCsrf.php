<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnosticoCsrf extends Command
{
    protected $signature = 'csrf:diagnostico';
    protected $description = 'Diagnóstico completo de problemas CSRF';

    public function handle()
    {
        $this->info('=== DIAGNÓSTICO CSRF ===');
        
        // 1. Verificar APP_KEY
        $appKey = config('app.key');
        if (empty($appKey)) {
            $this->error('❌ APP_KEY no configurada');
            return;
        } else {
            $this->info('✅ APP_KEY: ' . substr($appKey, 0, 20) . '...');
        }
        
        // 2. Verificar configuración de sesiones
        $sessionDriver = config('session.driver');
        $this->info("✅ Session Driver: {$sessionDriver}");
        
        // 3. Verificar permisos de directorio de sesiones
        $sessionPath = storage_path('framework/sessions');
        if ($sessionDriver === 'file') {
            if (is_writable($sessionPath)) {
                $this->info("✅ Directorio de sesiones escribible: {$sessionPath}");
            } else {
                $this->error("❌ Directorio de sesiones NO escribible: {$sessionPath}");
            }
        }
        
        // 4. Limpiar sesiones y cache
        $this->info('🧹 Limpiando cache y sesiones...');
        
        // Limpiar archivos de sesión
        if ($sessionDriver === 'file') {
            $files = glob($sessionPath . '/sess_*');
            foreach ($files as $file) {
                unlink($file);
            }
            $this->info('✅ Archivos de sesión limpiados');
        }
        
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        
        $this->info('🚀 Diagnóstico completado. Intenta hacer login ahora.');
    }
}