<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class ProbarConexionVistas extends Command
{
    protected $signature = 'test:conexion-vistas';
    protected $description = 'Probar la conexión y carga de vistas específicas';

    public function handle()
    {
        $this->info('🔍 Probando conexión y vistas...');
        
        // Probar URLs específicas
        $rutas = [
            'http://127.0.0.1:8000/login' => 'Página de Login',
            'http://127.0.0.1:8000/menu' => 'Menú Principal (requiere autenticación)',
        ];

        foreach ($rutas as $url => $descripcion) {
            try {
                $response = Http::timeout(5)->get($url);
                
                if ($response->successful()) {
                    $this->line("✅ {$descripcion}: Respuesta exitosa (200)");
                } else {
                    $this->line("❌ {$descripcion}: Error {$response->status()}");
                }
            } catch (\Exception $e) {
                $this->line("❌ {$descripcion}: Error de conexión - " . $e->getMessage());
            }
        }

        $this->info('');
        $this->info('🔍 Verificando rutas registradas...');
        
        $rutasImportantes = [
            'login',
            'menu', 
            'candidatos.index',
            'clientes.index',
            'giros.index',
            'empleados.index',
            'puestos.index',
            'documentos.index'
        ];

        foreach ($rutasImportantes as $nombreRuta) {
            try {
                $url = route($nombreRuta);
                $this->line("✅ Ruta '{$nombreRuta}': {$url}");
            } catch (\Exception $e) {
                $this->line("❌ Ruta '{$nombreRuta}': No está definida");
            }
        }

        return 0;
    }
}