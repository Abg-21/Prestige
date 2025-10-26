<?php
// Diagnóstico completo del problema de puestos

echo "=== DIAGNÓSTICO PUESTOS - ELIMINACIÓN Y ACTUALIZACIÓN ===\n\n";

// 1. Verificar tabla en base de datos
echo "1. VERIFICANDO BASE DE DATOS:\n";
try {
    $pdo = new PDO('sqlite:' . getcwd() . '/database/database.sqlite');
    
    // Contar puestos totales
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM puestos");
    $total = $stmt->fetch()['total'];
    echo "📊 Total de puestos en BD: $total\n";
    
    // Mostrar últimos 5 puestos
    $stmt = $pdo->query("SELECT idPuestos, Puesto FROM puestos ORDER BY idPuestos DESC LIMIT 5");
    $puestos = $stmt->fetchAll();
    
    echo "📋 Últimos 5 puestos:\n";
    foreach ($puestos as $puesto) {
        echo "   ID: {$puesto['idPuestos']} - {$puesto['Puesto']}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error conectando a BD: " . $e->getMessage() . "\n";
}

// 2. Verificar controlador
echo "\n2. VERIFICANDO CONTROLADOR:\n";
$controller = 'app/Http/Controllers/PuestoController.php';
if (file_exists($controller)) {
    $content = file_get_contents($controller);
    
    if (strpos($content, 'Cache::forget') !== false) {
        echo "✅ Limpieza de cache implementada\n";
    } else {
        echo "❌ Cache NO se está limpiando\n";
    }
    
    if (strpos($content, 'whereNotNull.*idPuestos') !== false) {
        echo "✅ Filtro whereNotNull implementado\n";
    } else {
        echo "❌ Filtro whereNotNull NO implementado\n";
    }
    
    if (strpos($content, 'Log::info.*ELIMINAR PUESTO') !== false) {
        echo "✅ Logging de eliminación implementado\n";
    } else {
        echo "❌ Logging de eliminación NO implementado\n";
    }
}

// 3. Verificar vista
echo "\n3. VERIFICANDO VISTA DE ELIMINACIÓN:\n";
$vista = 'resources/views/puestos/puesto.blade.php';
if (file_exists($vista)) {
    $content = file_get_contents($vista);
    
    if (strpos($content, 'btn-eliminar-puesto') !== false) {
        echo "✅ Botón de eliminar encontrado\n";
    } else {
        echo "❌ Botón de eliminar NO encontrado\n";
    }
    
    if (strpos($content, 'Date.now()') !== false) {
        echo "✅ Cache busting implementado\n";
    } else {
        echo "❌ Cache busting NO implementado\n";
    }
    
    if (strpos($content, 'window.location.reload') !== false) {
        echo "✅ Fallback de recarga implementado\n";
    } else {
        echo "❌ Fallback de recarga NO implementado\n";
    }
}

// 4. Probar ruta de eliminación (simulación)
echo "\n4. VERIFICANDO RUTAS:\n";
$routes = shell_exec('php artisan route:list | findstr "puestos.*DELETE"');
if ($routes) {
    echo "✅ Ruta DELETE encontrada:\n" . trim($routes) . "\n";
} else {
    echo "❌ Ruta DELETE NO encontrada\n";
}

// 5. Verificar logs recientes
echo "\n5. VERIFICANDO LOGS RECIENTES:\n";
$log_file = 'storage/logs/laravel.log';
if (file_exists($log_file)) {
    $logs = shell_exec('tail -20 ' . $log_file);
    if ($logs) {
        echo "📄 Últimas 20 líneas del log:\n";
        echo $logs . "\n";
    } else {
        echo "⚠️ No se pudieron leer los logs\n";
    }
} else {
    echo "⚠️ Archivo de logs no encontrado\n";
}

echo "\n=== INSTRUCCIONES DE PRUEBA ===\n";
echo "1. 🚀 Ejecutar: php artisan serve\n";
echo "2. 🌐 Ir a: lista de puestos\n";
echo "3. 🔍 Abrir F12 > Consola\n";
echo "4. 🗑️ Hacer clic en 'Eliminar' de algún puesto\n";
echo "5. ✅ Confirmar eliminación\n";
echo "6. 👀 Observar mensajes en consola\n\n";

echo "🚨 MENSAJES ESPERADOS EN CONSOLA:\n";
echo "- '✅ Puesto eliminado exitosamente: {respuesta}'\n";
echo "- '📋 Lista de puestos actualizada después de eliminar'\n\n";

echo "🚨 MENSAJES ESPERADOS EN LOGS:\n";
echo "- '[timestamp] local.INFO: === ELIMINAR PUESTO ==='\n";
echo "- '[timestamp] local.INFO: Puesto eliminado: {\"deleted\":true}'\n";
echo "- '[timestamp] local.INFO: Verificación de eliminación: {\"still_exists\":false}'\n\n";

echo "❌ PROBLEMAS POSIBLES:\n";
echo "- Si sale 'still_exists: true' → El puesto NO se eliminó de la BD\n";
echo "- Si no aparecen logs → El método destroy no se está ejecutando\n";
echo "- Si sale error 500 → Problema en el servidor\n";
echo "- Si la lista no se actualiza → Problema de cache o AJAX\n\n";

echo "🔧 SOLUCIONES:\n";
echo "- Limpiar cache: php artisan optimize:clear\n";
echo "- Ver logs en tiempo real: tail -f storage/logs/laravel.log\n";
echo "- Verificar permisos de BD: ls -la database/database.sqlite\n";
echo "- Probar eliminación directa en BD para descartar problemas de permisos\n\n";

echo "=== FIN DIAGNÓSTICO ===\n";
?>