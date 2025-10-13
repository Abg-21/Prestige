<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class ProbarVistas extends Command
{
    protected $signature = 'test:vistas';
    protected $description = 'Probar si las vistas se pueden renderizar correctamente';

    public function handle()
    {
        $this->info('🔍 Verificando vistas del sistema...');
        
        $vistas = [
            'candidatos.candidatos' => 'Candidatos - Lista',
            'candidatos.create_candidatos' => 'Candidatos - Crear',
            'clientes.cliente' => 'Clientes - Lista', 
            'clientes.create_cliente' => 'Clientes - Crear',
            'giros.giro' => 'Giros - Lista',
            'empleados.empleado' => 'Empleados - Lista',
            'puestos.puesto' => 'Puestos - Lista',
            'documentos.documentoE' => 'Documentos - Lista',
        ];

        foreach ($vistas as $vista => $descripcion) {
            try {
                // Verificar si la vista existe
                if (View::exists($vista)) {
                    $this->line("✅ {$descripcion}: Vista existe");
                    
                    // Intentar renderizar con datos mínimos
                    try {
                        $contenido = view($vista, $this->getDataForView($vista))->render();
                        $this->line("   ✅ Se puede renderizar");
                    } catch (\Exception $e) {
                        $this->line("   ❌ Error al renderizar: " . $e->getMessage());
                    }
                } else {
                    $this->line("❌ {$descripcion}: Vista NO existe");
                }
            } catch (\Exception $e) {
                $this->line("❌ {$descripcion}: Error - " . $e->getMessage());
            }
        }

        $this->info('');
        $this->info('🔍 Verificando controladores...');
        
        $controladores = [
            'CandidatoController' => 'App\Http\Controllers\CandidatoController',
            'ClienteController' => 'App\Http\Controllers\ClienteController',
            'GiroController' => 'App\Http\Controllers\GiroController',
            'EmpleadoController' => 'App\Http\Controllers\EmpleadoController',
            'PuestoController' => 'App\Http\Controllers\PuestoController',
            'DocumentoController' => 'App\Http\Controllers\DocumentoController',
        ];

        foreach ($controladores as $nombre => $clase) {
            if (class_exists($clase)) {
                $this->line("✅ {$nombre}: Clase existe");
                
                // Verificar métodos principales
                $metodos = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
                foreach ($metodos as $metodo) {
                    if (method_exists($clase, $metodo)) {
                        $this->line("   ✅ Método {$metodo}() existe");
                    } else {
                        $this->line("   ⚠️  Método {$metodo}() NO existe");
                    }
                }
            } else {
                $this->line("❌ {$nombre}: Clase NO existe");
            }
        }

        return 0;
    }

    private function getDataForView($vista)
    {
        // Datos mínimos para cada vista
        switch ($vista) {
            case 'candidatos.candidatos':
                return ['candidatos' => collect([])];
            case 'candidatos.create_candidatos':
                return ['puestos' => collect([])];
            case 'clientes.cliente':
                return ['clientes' => collect([])];
            case 'clientes.create_cliente':
                return ['errors' => new \Illuminate\Support\MessageBag()];
            case 'giros.giro':
                return ['giros' => collect([])];
            case 'empleados.empleado':
                return ['empleados' => collect([])];
            case 'puestos.puesto':
                return ['puestos' => collect([])];
            case 'documentos.documentoE':
                return ['empleados' => collect([])];
            default:
                return ['errors' => new \Illuminate\Support\MessageBag()];
        }
    }
}