<?php
// Script para monitorear los logs en tiempo real

echo "=== MONITOR DE LOGS AJAX ===\n";
echo "Presiona Ctrl+C para salir\n";
echo "Monitoreando: storage/logs/laravel.log\n\n";

$log_file = 'storage/logs/laravel.log';

if (!file_exists($log_file)) {
    echo "❌ Archivo de log no existe\n";
    exit(1);
}

// Obtener tamaño inicial del archivo
$last_size = filesize($log_file);
echo "📋 Tamaño inicial del log: $last_size bytes\n";
echo "🔄 Esperando nuevas entradas...\n\n";

while (true) {
    // Verificar si el archivo ha crecido
    $current_size = filesize($log_file);
    
    if ($current_size > $last_size) {
        // Leer solo las nuevas líneas
        $handle = fopen($log_file, 'r');
        fseek($handle, $last_size);
        
        while (($line = fgets($handle)) !== false) {
            // Filtrar solo logs relacionados con AJAX
            if (stripos($line, 'AJAX') !== false || 
                stripos($line, 'CLIENTE') !== false || 
                stripos($line, 'GIRO') !== false ||
                stripos($line, 'X-Requested-With') !== false) {
                
                echo "🔸 " . trim($line) . "\n";
            }
        }
        
        fclose($handle);
        $last_size = $current_size;
    }
    
    // Esperar 1 segundo antes de verificar de nuevo
    sleep(1);
}
?>