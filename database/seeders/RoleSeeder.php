<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos de usuarios
        $crearUsuarios = Permission::firstOrCreate(['name' => 'crear usuarios']);
        $verUsuarios = Permission::firstOrCreate(['name' => 'ver usuarios']);

        // Permisos adicionales según módulos
        $gestionarCandidatos = Permission::firstOrCreate(['name' => 'gestionar candidatos']);
        $gestionarPuestos = Permission::firstOrCreate(['name' => 'gestionar puestos']);
        $gestionarEmpleados = Permission::firstOrCreate(['name' => 'gestionar empleados']);
        $gestionarDocumentos = Permission::firstOrCreate(['name' => 'gestionar documentos']);

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $rhRole = Role::firstOrCreate(['name' => 'Recursos Humanos', 'guard_name' => 'web']);
        $contabilidadRole = Role::firstOrCreate(['name' => 'Contabilidad', 'guard_name' => 'web']);

        // Asignar permisos al Administrador (Todos los permisos)
        $adminRole->syncPermissions([
            $crearUsuarios, $verUsuarios,
            $gestionarCandidatos, $gestionarPuestos,
            $gestionarEmpleados, $gestionarDocumentos,
        ]);

        // Asignar permisos a Recursos Humanos
        $rhRole->syncPermissions([
            $gestionarCandidatos, $gestionarPuestos,
            $gestionarEmpleados, $gestionarDocumentos,
        ]);

        // Asignar permisos a Contabilidad (Solo ver empleados y documentos)
        $contabilidadRole->syncPermissions([
            $gestionarEmpleados, $gestionarDocumentos,
        ]);
    }
}
