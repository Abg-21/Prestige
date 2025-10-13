<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Rol::firstOrCreate(
            ['nombre' => 'Administrador'],
            [
                'descripcion' => 'Acceso completo al sistema',
                'permisos' => json_encode([
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'asistencia' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'nomina' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'eliminados' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'usuarios' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'roles' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true]
                ])
            ]
        );

        $contabilidadRole = Rol::firstOrCreate(
            ['nombre' => 'Contabilidad'],
            [
                'descripcion' => 'Acceso a funciones contables y nómina',
                'permisos' => json_encode([
                    'candidatos' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'empleados' => ['ver' => true, 'crear' => false, 'editar' => true, 'eliminar' => false],
                    'puestos' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'giros' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'clientes' => ['ver' => true, 'crear' => false, 'editar' => true, 'eliminar' => false],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'asistencia' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'nomina' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'eliminados' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false]
                ])
            ]
        );

        $rhRole = Rol::firstOrCreate(
            ['nombre' => 'Recursos Humanos'],
            [
                'descripcion' => 'Gestión de personal y candidatos',
                'permisos' => json_encode([
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'asistencia' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'nomina' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'eliminados' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false]
                ])
            ]
        );

        // Crear usuario Administrador
        $admin = Usuario::firstOrCreate(
            ['correo' => 'admin@prestige.com'],
            [
                'nombre' => 'Administrador del Sistema',
                'contraseña' => 'admin123' // El mutator automáticamente aplicará el hash
            ]
        );

        // Crear usuario Contabilidad
        $contabilidad = Usuario::firstOrCreate(
            ['correo' => 'contabilidad@prestige.com'],
            [
                'nombre' => 'Departamento de Contabilidad',
                'contraseña' => 'conta123' // El mutator automáticamente aplicará el hash
            ]
        );

        // Crear usuario RH
        $rh = Usuario::firstOrCreate(
            ['correo' => 'rh@prestige.com'],
            [
                'nombre' => 'Recursos Humanos',
                'contraseña' => 'rh123' // El mutator automáticamente aplicará el hash
            ]
        );

        // Asignar roles a usuarios (verificar que no existan antes de crear)
        if (!$admin->roles()->where('rol_id', $adminRole->id)->exists()) {
            $admin->roles()->attach($adminRole->id);
        }

        if (!$contabilidad->roles()->where('rol_id', $contabilidadRole->id)->exists()) {
            $contabilidad->roles()->attach($contabilidadRole->id);
        }

        if (!$rh->roles()->where('rol_id', $rhRole->id)->exists()) {
            $rh->roles()->attach($rhRole->id);
        }

        $this->command->info('Usuarios base creados exitosamente:');
        $this->command->info('┌─────────────────────────────────────────────────────────┐');
        $this->command->info('│ USUARIOS CREADOS                                        │');
        $this->command->info('├─────────────────────────────────────────────────────────┤');
        $this->command->info('│ Admin:        admin@prestige.com / admin123             │');
        $this->command->info('│ Contabilidad: contabilidad@prestige.com / conta123     │');
        $this->command->info('│ RH:           rh@prestige.com / rh123                   │');
        $this->command->info('└─────────────────────────────────────────────────────────┘');
    }
}
