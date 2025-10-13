<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class CrearUsuarios extends Command
{
    protected $signature = 'usuarios:crear';
    protected $description = 'Crear usuarios RH, Contabilidad y Administrador';

    public function handle()
    {
        $this->info('🚀 Iniciando creación de usuarios y roles...');

        // Crear roles
        $this->info('📋 Creando roles...');
        
        $rolePermissions = [
            'Administrador' => [
                'descripcion' => 'Acceso completo a todos los módulos del sistema',
                'permisos' => [
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'usuarios' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'roles' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                ]
            ],
            'RH' => [
                'descripcion' => 'Recursos Humanos - Gestión de candidatos, empleados y documentos',
                'permisos' => [
                    'candidatos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'empleados' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'puestos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'giros' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'clientes' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                ]
            ],
            'Contabilidad' => [
                'descripcion' => 'Departamento de Contabilidad - Gestión de clientes, giros y documentos financieros',
                'permisos' => [
                    'candidatos' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'empleados' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'puestos' => ['ver' => true, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'giros' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'clientes' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => true],
                    'documentos' => ['ver' => true, 'crear' => true, 'editar' => true, 'eliminar' => false],
                    'usuarios' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                    'roles' => ['ver' => false, 'crear' => false, 'editar' => false, 'eliminar' => false],
                ]
            ]
        ];

        $roles = [];

        foreach ($rolePermissions as $nombreRol => $datosRol) {
            $rol = Rol::updateOrCreate(
                ['nombre' => $nombreRol],
                [
                    'descripcion' => $datosRol['descripcion'],
                    'permisos' => $datosRol['permisos']
                ]
            );
            
            $this->info("   ✅ Rol '{$nombreRol}' creado/actualizado");
            $roles[$nombreRol] = $rol;
        }

        // Crear usuarios
        $this->info('👤 Creando usuarios...');

        $usuarios = [
            [
                'nombre' => 'Administrador Sistema',
                'correo' => 'admin@prestige.com',
                'contraseña' => 'admin123',
                'rol' => 'Administrador'
            ],
            [
                'nombre' => 'Usuario RH',
                'correo' => 'rh@prestige.com',
                'contraseña' => 'rh123',
                'rol' => 'RH'
            ],
            [
                'nombre' => 'Usuario Contabilidad',
                'correo' => 'contabilidad@prestige.com',
                'contraseña' => 'conta123',
                'rol' => 'Contabilidad'
            ]
        ];

        foreach ($usuarios as $datosUsuario) {
            $usuario = Usuario::updateOrCreate(
                ['correo' => $datosUsuario['correo']],
                [
                    'nombre' => $datosUsuario['nombre'],
                    'contraseña' => Hash::make($datosUsuario['contraseña']),
                    'eliminado_en' => null
                ]
            );

            // Asignar rol
            $rol = $roles[$datosUsuario['rol']];
            $usuario->roles()->sync([$rol->id]);

            $this->info("   ✅ Usuario '{$datosUsuario['nombre']}' creado/actualizado con rol '{$datosUsuario['rol']}'");
        }

        $this->info('🎉 ¡Proceso completado exitosamente!');
        
        $this->info('📝 Credenciales de acceso:');
        $this->info('   - admin@prestige.com / admin123 (Administrador)');
        $this->info('   - rh@prestige.com / rh123 (RH)');
        $this->info('   - contabilidad@prestige.com / conta123 (Contabilidad)');

        return 0;
    }
}