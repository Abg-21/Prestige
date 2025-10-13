<?php
require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Giro;
use App\Models\Cliente;

echo "=== GIROS EN LA BASE DE DATOS ===\n";
$giros = Giro::all();
foreach($giros as $giro) {
    echo "ID: {$giro->idGiros}, Nombre: '{$giro->Nombre}'\n";
}

echo "\n=== CLIENTES EN LA BASE DE DATOS ===\n";
$clientes = Cliente::all();
foreach($clientes as $cliente) {
    echo "ID: {$cliente->idClientes}, Nombre: '{$cliente->Nombre}'\n";
}

echo "\n=== VERIFICAR NOMBRE ESPECÍFICO ===\n";
$testNombre = 'Prueba1';
$girosConNombre = Giro::where('Nombre', $testNombre)->get();
echo "Giros con nombre '{$testNombre}': " . $girosConNombre->count() . "\n";
foreach($girosConNombre as $giro) {
    echo "  - ID: {$giro->idGiros}, Nombre: '{$giro->Nombre}'\n";
}