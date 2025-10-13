<?php
// Script simple para crear usuarios paso a paso

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

// Primero crear los roles
echo "Creando roles...\n";

// Rol Administrador
$adminRol = Rol::firstOrCreate(
    ['nombre' => 'Administrador'],
    [
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
    ]
);

echo "Rol Administrador creado: " . $adminRol->id . "\n";

// Rol RH
$rhRol = Rol::firstOrCreate(
    ['nombre' => 'RH'],
    [
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
    ]
);

echo "Rol RH creado: " . $rhRol->id . "\n";

// Rol Contabilidad
$contaRol = Rol::firstOrCreate(
    ['nombre' => 'Contabilidad'],
    [
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
);

echo "Rol Contabilidad creado: " . $contaRol->id . "\n";

echo "Todos los roles creados exitosamente!\n";