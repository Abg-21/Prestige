<?php
/**
 * ====================================================================
 * SCRIPT PARA CREAR USUARIOS Y ROLES - SISTEMA PRESTIGE
 * ====================================================================
 * 
 * ✅ ¡USUARIOS YA CREADOS EXITOSAMENTE!
 * 
 * Los usuarios RH, Contabilidad y Administrador han sido creados
 * correctamente con sus roles y permisos correspondientes.
 * 
 * ====================================================================
 * CREDENCIALES DE ACCESO:
 * ====================================================================
 * 
 * 👤 ADMINISTRADOR:
 *    Email: admin@prestige.com
 *    Contraseña: admin123
 *    Permisos: Acceso completo a todos los módulos
 * 
 * 👤 RECURSOS HUMANOS:
 *    Email: rh@prestige.com
 *    Contraseña: rh123
 *    Permisos: Candidatos, Empleados, Puestos, Documentos (completo)
 *             Giros, Clientes (solo lectura)
 * 
 * 👤 CONTABILIDAD:
 *    Email: contabilidad@prestige.com
 *    Contraseña: conta123
 *    Permisos: Giros, Clientes, Documentos (completo)
 *             Candidatos, Empleados, Puestos (solo lectura)
 * 
 * ====================================================================
 * COMANDOS DISPONIBLES:
 * ====================================================================
 * 
 * 🔄 Para RECREAR todos los usuarios:
 *    php artisan usuarios:crear
 * 
 * 📋 Para VER el resumen de usuarios:
 *    php artisan usuarios:mostrar
 * 
 * 🔧 Para usar en TINKER (comandos individuales):
 *    php artisan tinker
 *    Usuario::with('roles')->get();                    // Ver todos los usuarios
 *    Rol::with('usuarios')->get();                     // Ver todos los roles
 *    Usuario::where('correo', 'admin@prestige.com')->first(); // Usuario específico
 * 
 * ====================================================================
 * ESTRUCTURA DE PERMISOS:
 * ====================================================================
 * 
 * ADMINISTRADOR:
 * ✅ Candidatos: ver, crear, editar, eliminar
 * ✅ Empleados: ver, crear, editar, eliminar
 * ✅ Puestos: ver, crear, editar, eliminar
 * ✅ Giros: ver, crear, editar, eliminar
 * ✅ Clientes: ver, crear, editar, eliminar
 * ✅ Documentos: ver, crear, editar, eliminar
 * ✅ Usuarios: ver, crear, editar, eliminar
 * ✅ Roles: ver, crear, editar, eliminar
 * 
 * RH (RECURSOS HUMANOS):
 * ✅ Candidatos: ver, crear, editar, eliminar
 * ✅ Empleados: ver, crear, editar, eliminar
 * ✅ Puestos: ver, crear, editar
 * ✅ Giros: ver
 * ✅ Clientes: ver
 * ✅ Documentos: ver, crear, editar, eliminar
 * ❌ Usuarios: sin acceso
 * ❌ Roles: sin acceso
 * 
 * CONTABILIDAD:
 * ✅ Candidatos: ver
 * ✅ Empleados: ver
 * ✅ Puestos: ver
 * ✅ Giros: ver, crear, editar, eliminar
 * ✅ Clientes: ver, crear, editar, eliminar
 * ✅ Documentos: ver, crear, editar
 * ❌ Usuarios: sin acceso
 * ❌ Roles: sin acceso
 * 
 * ====================================================================
 * VERIFICACIÓN:
 * ====================================================================
 * 
 * Total de usuarios en el sistema: 3
 * Total de roles en el sistema: 3
 * 
 * ¡Sistema listo para usar! 🎉
 */

// Si necesitas recrear usuarios desde Tinker, descomenta y ejecuta este código:

/*
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

echo "🚀 Recreando usuarios...\n";

// Crear roles
$adminRol = Rol::updateOrCreate(['nombre' => 'Administrador'], [
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
]);

$rhRol = Rol::updateOrCreate(['nombre' => 'RH'], [
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
]);

$contaRol = Rol::updateOrCreate(['nombre' => 'Contabilidad'], [
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
]);

// Crear usuarios
$admin = Usuario::updateOrCreate(['correo' => 'admin@prestige.com'], [
    'nombre' => 'Administrador Sistema',
    'contraseña' => Hash::make('admin123'),
    'eliminado_en' => null
]);
$admin->roles()->sync([$adminRol->id]);

$rh = Usuario::updateOrCreate(['correo' => 'rh@prestige.com'], [
    'nombre' => 'Usuario RH',
    'contraseña' => Hash::make('rh123'),
    'eliminado_en' => null
]);
$rh->roles()->sync([$rhRol->id]);

$conta = Usuario::updateOrCreate(['correo' => 'contabilidad@prestige.com'], [
    'nombre' => 'Usuario Contabilidad',
    'contraseña' => Hash::make('conta123'),
    'eliminado_en' => null
]);
$conta->roles()->sync([$contaRol->id]);

echo "✅ Usuarios recreados exitosamente!\n";
*/