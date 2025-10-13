<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rol Administrador - Acceso total
        Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Acceso completo a todas las funcionalidades del sistema',
            'permisos' => json_encode([
                'giros' => true,
                'clientes' => true,
                'puestos' => true,
                'candidatos' => true,
                'empleados' => true,
                'documentos' => true,
                'usuarios' => true,
                'roles' => true,
                'configuracion' => true
            ])
        ]);

        // Rol Contabilidad - Solo empleados y candidatos
        Rol::create([
            'nombre' => 'Contabilidad',
            'descripcion' => 'Acceso limitado a empleados y candidatos (solo lectura)',
            'permisos' => json_encode([
                'giros' => false,
                'clientes' => false,
                'puestos' => false,
                'candidatos' => true,
                'empleados' => true,
                'documentos' => false,
                'usuarios' => false,
                'roles' => false,
                'configuracion' => false
            ])
        ]);

        // Rol RH - Todas las opciones excepto usuarios y roles
        Rol::create([
            'nombre' => 'RH',
            'descripcion' => 'Acceso a todas las opciones excepto gestión de usuarios y roles',
            'permisos' => json_encode([
                'giros' => true,
                'clientes' => true,
                'puestos' => true,
                'candidatos' => true,
                'empleados' => true,
                'documentos' => true,
                'usuarios' => false,
                'roles' => false,
                'configuracion' => true
            ])
        ]);
    }
}
