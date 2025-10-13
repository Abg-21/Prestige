<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ProbarRutasWeb extends Command
{
    protected $signature = 'test:rutas-web';
    protected $description = 'Probar acceso directo a las rutas web';

    public function handle()
    {
        $this->info('🌐 PROBANDO ACCESO A RUTAS WEB');
        $this->newLine();

        $baseUrl = 'http://127.0.0.1:8000';
        
        // 1. Probar página de login
        $this->info('1. 🔐 Probando página de LOGIN:');
        try {
            $response = Http::timeout(10)->get($baseUrl . '/login');
            if ($response->successful()) {
                $content = $response->body();
                $this->line("   ✅ Status: {$response->status()}");
                $this->line("   📄 Tamaño: " . strlen($content) . " bytes");
                
                // Verificar si contiene elementos esperados del login
                if (strpos($content, 'login') !== false || strpos($content, 'email') !== false) {
                    $this->line("   ✅ Contiene elementos de login");
                } else {
                    $this->line("   ⚠️  No contiene elementos de login esperados");
                }
            } else {
                $this->line("   ❌ Error HTTP: {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Error de conexión: " . $e->getMessage());
        }

        $this->newLine();

        // 2. Simular login para obtener cookies
        $this->info('2. 🔑 Intentando LOGIN automático:');
        try {
            // Obtener token CSRF primero
            $loginPage = Http::timeout(10)->get($baseUrl . '/login');
            $csrf = $this->extractCsrfToken($loginPage->body());
            
            if ($csrf) {
                $this->line("   ✅ Token CSRF obtenido");
                
                // Intentar login
                $loginResponse = Http::timeout(10)->asForm()->post($baseUrl . '/login', [
                    '_token' => $csrf,
                    'email' => 'admin@prestige.com',
                    'password' => 'admin123'
                ]);
                
                $this->line("   📊 Login response status: {$loginResponse->status()}");
                
                if ($loginResponse->status() == 302) {
                    $this->line("   ✅ Redirección detectada (probablemente exitosa)");
                    $location = $loginResponse->header('Location');
                    if ($location) {
                        $this->line("   🔄 Redirige a: {$location}");
                    }
                } else {
                    $this->line("   ❌ Login puede haber fallado");
                }
            } else {
                $this->line("   ❌ No se pudo obtener token CSRF");
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Error en login: " . $e->getMessage());
        }

        $this->newLine();

        // 3. Probar rutas específicas (sin autenticación)
        $this->info('3. 🛣️  Probando RUTAS específicas:');
        $rutas = [
            '/candidatos' => 'Candidatos',
            '/clientes' => 'Clientes',
            '/giros' => 'Giros',
            '/empleados' => 'Empleados',
            '/puestos' => 'Puestos',
            '/documentos' => 'Documentos'
        ];

        foreach ($rutas as $ruta => $nombre) {
            try {
                $response = Http::timeout(10)->get($baseUrl . $ruta);
                $this->line("   📍 {$nombre} ({$ruta}): Status {$response->status()}");
                
                if ($response->status() == 302) {
                    $location = $response->header('Location');
                    $this->line("      🔄 Redirige a: {$location}");
                } elseif ($response->successful()) {
                    $content = $response->body();
                    $this->line("      ✅ Contenido: " . strlen($content) . " bytes");
                } else {
                    $this->line("      ❌ Error HTTP");
                }
            } catch (\Exception $e) {
                $this->line("   ❌ {$nombre}: " . $e->getMessage());
            }
        }

        return 0;
    }

    private function extractCsrfToken($html)
    {
        // Buscar token CSRF en el HTML
        if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $html, $matches)) {
            return $matches[1];
        }
        
        if (preg_match('/<input[^>]+name="_token"[^>]+value="([^"]+)"/', $html, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}