<?php
// File: app/Console/Commands/CreateDefaultRoles.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rol;

class CreateDefaultRoles extends Command
{
    protected $signature = 'roles:create-default';
    protected $description = 'Crear roles predefinidos del sistema';

    public function handle()
    {
        $this->info('Creando roles predefinidos...');

        // ROL ADMIN - Acceso total a todo
        $adminRole = Rol::updateOrCreate(
            ['nombre' => 'Admin'],
            [
                'descripcion' => 'Administrador con acceso total al sistema',
                'permisos' => json_encode([
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'contratos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'asistencia' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'nomina' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'eliminados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'usuarios' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'roles' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true]
                ])
            ]
        );

        // ROL CONTABILIDAD
        $contabilidadRole = Rol::updateOrCreate(
            ['nombre' => 'Contabilidad'],
            [
                'descripcion' => 'Personal de contabilidad con acceso limitado',
                'permisos' => json_encode([
                    // Personal - Solo ver y eliminar
                    'candidatos' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => true],
                    
                    // Documentación - Acceso completo
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'contratos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    
                    // Nómina - Solo ver (en desarrollo)
                    'nomina' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    
                    // Asistencia - Solo ver (en desarrollo)
                    'asistencia' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    
                    // SIN acceso a Reclutamiento y Configuración
                    'puestos' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'giros' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'clientes' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'eliminados' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false]
                ])
            ]
        );

        // ROL RH (Recursos Humanos)
        $rhRole = Rol::updateOrCreate(
            ['nombre' => 'RH'],
            [
                'descripcion' => 'Recursos Humanos con acceso a todos los módulos excepto configuración',
                'permisos' => json_encode([
                    // Acceso completo a todos los módulos operativos
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'contratos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'asistencia' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'nomina' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'eliminados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    
                    // SIN acceso a Configuración
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false]
                ])
            ]
        );

        $this->info('✅ Rol Admin creado/actualizado');
        $this->info('✅ Rol Contabilidad creado/actualizado');
        $this->info('✅ Rol RH creado/actualizado');
        $this->info('🎉 Roles predefinidos instalados correctamente');

        return 0;
    }
}