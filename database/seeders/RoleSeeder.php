<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Usuario;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear rol Administrador con TODOS los permisos
        $adminRole = Rol::firstOrCreate([
            'nombre' => 'Administrador'
        ], [
            'descripcion' => 'Acceso completo al sistema',
            'permisos' => json_encode([
                'candidatos' => true,
                'empleados' => true,
                'puestos' => true,
                'giros' => true,
                'clientes' => true,
                'documentos' => true,
                'contratos' => true,
                'asistencia' => true,
                'nomina' => true,
                'eliminados' => true,
                'usuarios' => true,
                'roles' => true
            ])
        ]);

        // Crear rol Contabilidad (personal, documentación, asistencia, nómina, eliminados)
        $contabilidadRole = Rol::firstOrCreate([
            'nombre' => 'Contabilidad'
        ], [
            'descripcion' => 'Acceso a personal, documentación y nómina',
            'permisos' => json_encode([
                'candidatos' => true,
                'empleados' => true,
                'puestos' => false,
                'giros' => false,
                'clientes' => false,
                'documentos' => true,
                'contratos' => true,
                'asistencia' => true,
                'nomina' => true,
                'eliminados' => true,
                'usuarios' => false,
                'roles' => false
            ])
        ]);

        // Crear rol RH (todos excepto configuración)
        $rhRole = Rol::firstOrCreate([
            'nombre' => 'Recursos Humanos'
        ], [
            'descripcion' => 'Acceso completo excepto configuración',
            'permisos' => json_encode([
                'candidatos' => true,
                'empleados' => true,
                'puestos' => true,
                'giros' => true,
                'clientes' => true,
                'documentos' => true,
                'contratos' => true,
                'asistencia' => true,
                'nomina' => true,
                'eliminados' => true,
                'usuarios' => false,
                'roles' => false
            ])
        ]);
    }
}
