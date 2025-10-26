<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

// Simular el proceso exacto del AuthController
$email = 'admin@grupoprestige.com.mx';
$password = '123456';

echo "=== SIMULACIÓN DEL PROCESO DE LOGIN ===\n\n";

echo "1. Buscando usuario con email: $email\n";
$usuario = Usuario::where('correo', $email)->whereNull('eliminado_en')->first();

if (!$usuario) {
    echo "❌ Usuario NO encontrado o está eliminado\n";
    echo "📋 Verificando usuarios disponibles:\n";
    $usuarios = Usuario::whereNull('eliminado_en')->get(['correo', 'nombre']);
    foreach ($usuarios as $u) {
        echo "  - {$u->correo} ({$u->nombre})\n";
    }
    exit;
}

echo "✅ Usuario encontrado: {$usuario->nombre}\n";
echo "2. Verificando contraseña...\n";

$passwordCheck = Hash::check($password, $usuario->contraseña);
if ($passwordCheck) {
    echo "✅ Contraseña es correcta\n";
    echo "3. Todo listo para autenticación\n";
    echo "\n🎉 EL LOGIN DEBERÍA FUNCIONAR CORRECTAMENTE\n";
} else {
    echo "❌ Contraseña es incorrecta\n";
    echo "Hash almacenado: " . substr($usuario->contraseña, 0, 50) . "...\n";
    echo "Probando regenerar hash...\n";
    
    $nuevoHash = Hash::make($password);
    $usuario->contraseña = $nuevoHash;
    $usuario->save();
    
    echo "✅ Nueva contraseña establecida\n";
    echo "Verificando: " . (Hash::check($password, $usuario->contraseña) ? 'OK' : 'ERROR') . "\n";
}