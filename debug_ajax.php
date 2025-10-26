<?php
// Script para diagnosticar problemas con AJAX

echo "=== DIAGNÓSTICO AJAX ===\n\n";

// 1. Revisar rutas
echo "1. VERIFICANDO RUTAS:\n";
$routes_output = shell_exec('php artisan route:list | findstr "clientes\|giros"');
echo $routes_output . "\n";

// 2. Revisar archivos JavaScript
$js_files = [
    'resources/views/clientes/create_cliente.blade.php',
    'resources/views/clientes/edit_cliente.blade.php', 
    'resources/views/giros/create_giro.blade.php',
    'resources/views/giros/edit_giro.blade.php'
];

echo "2. VERIFICANDO JAVASCRIPT EN VISTAS:\n";
foreach ($js_files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'form.onsubmit') !== false) {
            echo "✅ $file - JavaScript encontrado\n";
        } else {
            echo "❌ $file - JavaScript NO encontrado\n";
        }
    } else {
        echo "⚠️ $file - Archivo no existe\n";
    }
}

// 3. Revisar logs recientes
echo "\n3. LOGS RECIENTES:\n";
$log_file = 'storage/logs/laravel.log';
if (file_exists($log_file)) {
    $lines = file($log_file);
    $recent_lines = array_slice($lines, -10);
    foreach ($recent_lines as $line) {
        if (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
            echo "🔴 " . trim($line) . "\n";
        }
    }
}

echo "\n=== FIN DIAGNÓSTICO ===\n";
?>