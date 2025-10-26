<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

echo "=== DIAGNÓSTICO DE CREDENCIALES ADMIN ===\n\n";

// Buscar el usuario admin
$usuario = Usuario::where('correo', 'admin@grupoprestige.com.mx')->first();

if ($usuario) {
    echo "✅ Usuario encontrado:\n";
    echo "ID: {$usuario->id}\n";
    echo "Nombre: {$usuario->nombre}\n";
    echo "Correo: {$usuario->correo}\n";
    echo "Eliminado: " . ($usuario->eliminado_en ? 'SÍ (' . $usuario->eliminado_en . ')' : 'NO') . "\n";
    echo "Hash actual: " . substr($usuario->contraseña, 0, 30) . "...\n\n";
    
    // Probar diferentes contraseñas posibles
    $passwords = ['123456', 'admin', 'password', 'Admin123', 'grupoprestige'];
    
    echo "🔍 Probando contraseñas comunes:\n";
    foreach ($passwords as $password) {
        $match = Hash::check($password, $usuario->contraseña);
        echo "- '{$password}': " . ($match ? '✅ COINCIDE' : '❌ NO COINCIDE') . "\n";
        if ($match) {
            echo "  🎉 ¡CONTRASEÑA CORRECTA ENCONTRADA!\n";
            break;
        }
    }
    
    echo "\n🔧 Estableciendo contraseña '123456' para pruebas:\n";
    $usuario->contraseña = Hash::make('123456');
    $usuario->save();
    
    // Verificar que se guardó correctamente
    $verify = Hash::check('123456', $usuario->contraseña);
    echo "Contraseña '123456' establecida: " . ($verify ? '✅ ÉXITO' : '❌ ERROR') . "\n";
    
} else {
    echo "❌ Usuario admin NO encontrado\n";
    echo "📋 Usuarios disponibles:\n";
    $usuarios = Usuario::whereNull('eliminado_en')->get(['id', 'nombre', 'correo']);
    foreach ($usuarios as $u) {
        echo "- ID: {$u->id} | {$u->nombre} | {$u->correo}\n";
    }
}