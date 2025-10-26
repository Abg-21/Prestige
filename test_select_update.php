<?php
// Script específico para probar actualización del select de puestos

echo "=== TEST ACTUALIZACIÓN SELECT PUESTOS ===\n\n";

// 1. Verificar que existe la ruta
echo "1. VERIFICANDO RUTA puestos/lista:\n";
$routes_output = shell_exec('php artisan route:list | findstr "puestos/lista"');
if ($routes_output) {
    echo "✅ Ruta encontrada: " . trim($routes_output) . "\n";
} else {
    echo "❌ Ruta NO encontrada\n";
    exit;
}

// 2. Verificar controlador
echo "\n2. VERIFICANDO CONTROLADOR:\n";
$controller_path = 'app/Http/Controllers/PuestoController.php';
if (file_exists($controller_path)) {
    $content = file_get_contents($controller_path);
    
    if (strpos($content, 'public function lista()') !== false) {
        echo "✅ Método lista() existe\n";
        
        if (strpos($content, 'orderBy') !== false) {
            echo "✅ Ordenamiento implementado (más recientes primero)\n";
        }
        
        if (strpos($content, 'Log::info.*OBTENIENDO LISTA') !== false) {
            echo "✅ Logging implementado\n";
        }
    } else {
        echo "❌ Método lista() NO encontrado\n";
    }
}

// 3. Verificar vista select_options
echo "\n3. VERIFICANDO VISTA select_options:\n";
$view_path = 'resources/views/puestos/select_options.blade.php';
if (file_exists($view_path)) {
    echo "✅ Vista existe\n";
    $content = file_get_contents($view_path);
    echo "📄 Contenido:\n" . $content . "\n";
} else {
    echo "❌ Vista NO existe\n";
}

// 4. Verificar función JavaScript mejorada
echo "\n4. VERIFICANDO JAVASCRIPT MEJORADO:\n";
$candidatos_view = 'resources/views/candidatos/create_candidatos.blade.php';
if (file_exists($candidatos_view)) {
    $content = file_get_contents($candidatos_view);
    
    if (strpos($content, 'INICIANDO ACTUALIZACIÓN DE SELECT') !== false) {
        echo "✅ Debug detallado implementado\n";
    }
    
    if (strpos($content, 'SELECT NO ENCONTRADO') !== false) {
        echo "✅ Validación de select implementada\n";
    }
    
    if (strpos($content, 'TODAS LAS OPCIONES DISPONIBLES') !== false) {
        echo "✅ Logging de opciones implementado\n";
    }
    
    if (strpos($content, 'orderBy.*desc') !== false) {
        echo "✅ Ordenamiento por ID descendente\n";
    }
}

// 5. Simular petición a la ruta (si el servidor está corriendo)
echo "\n5. SIMULANDO PETICIÓN A puestos/lista:\n";

$test_url = 'http://127.0.0.1:8000/puestos/lista';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "X-Requested-With: XMLHttpRequest\r\n",
        'timeout' => 5
    ]
]);

$response = @file_get_contents($test_url, false, $context);
if ($response !== false) {
    echo "✅ Petición exitosa\n";
    echo "📄 Respuesta (primeros 200 chars):\n" . substr($response, 0, 200) . "\n";
    
    // Contar opciones
    $option_count = substr_count($response, '<option');
    echo "📊 Número de opciones encontradas: $option_count\n";
    
    if ($option_count > 1) { // Al menos la opción "Seleccione" + 1 puesto
        echo "✅ La ruta devuelve opciones correctamente\n";
    } else {
        echo "⚠️ Pocas opciones devueltas, puede no haber puestos\n";
    }
} else {
    echo "⚠️ No se pudo probar (servidor no está corriendo)\n";
    echo "   Ejecuta: php artisan serve\n";
    echo "   Y luego: php " . basename(__FILE__) . "\n";
}

// 6. Verificar base de datos
echo "\n6. VERIFICANDO PUESTOS EN BASE DE DATOS:\n";
try {
    // Leer configuración de base de datos
    $env_content = @file_get_contents('.env');
    if ($env_content && strpos($env_content, 'DB_DATABASE=') !== false) {
        echo "✅ Archivo .env encontrado\n";
        
        // Ejecutar consulta usando artisan tinker
        $tinker_command = 'php artisan tinker --execute="echo \'Puestos en BD: \' . App\\\\Models\\\\Puesto::count(); App\\\\Models\\\\Puesto::orderBy(\'idPuestos\', \'desc\')->take(3)->get([\'idPuestos\', \'Puesto\'])->each(function(\$p) { echo \"ID: {\$p->idPuestos} - {\$p->Puesto}\\n\"; });"';
        
        echo "🔍 Ejecutando consulta...\n";
        $db_result = shell_exec($tinker_command);
        if ($db_result) {
            echo "📊 Resultado:\n" . $db_result . "\n";
        } else {
            echo "⚠️ No se pudo ejecutar consulta\n";
        }
    } else {
        echo "⚠️ No se pudo verificar configuración de BD\n";
    }
} catch (Exception $e) {
    echo "⚠️ Error verificando BD: " . $e->getMessage() . "\n";
}

echo "\n=== INSTRUCCIONES DE PRUEBA ===\n";
echo "1. 🚀 Ejecutar: php artisan serve\n";
echo "2. 🌐 Ir a: crear candidato\n";
echo "3. 🔍 Abrir F12 > Consola\n";
echo "4. ➕ Hacer clic en 'Nuevo Puesto'\n";
echo "5. 📝 Llenar formulario del modal\n";
echo "6. 💾 Guardar puesto\n";
echo "7. 👀 Observar mensajes en consola:\n\n";

echo "🚨 MENSAJES ESPERADOS:\n";
echo "- '🔄 INICIANDO ACTUALIZACIÓN DE SELECT - Nuevo puesto ID: X'\n";
echo "- '🔍 Select encontrado: SÍ'\n";
echo "- '📤 Enviando petición AJAX para actualizar select...'\n";
echo "- '✅ RESPUESTA RECIBIDA - Longitud HTML: XXX'\n";
echo "- '📊 Opciones después de actualizar: X'\n";
echo "- '📋 TODAS LAS OPCIONES DISPONIBLES:'\n";
echo "- '✅ OPCIÓN ENCONTRADA: [Nombre del puesto]'\n";
echo "- '🎉 ¡PUESTO SELECCIONADO CORRECTAMENTE!'\n\n";

echo "❌ SI VES ERRORES:\n";
echo "- 'SELECT NO ENCONTRADO' → Problema en el HTML\n";
echo "- 'MISMO NÚMERO DE OPCIONES' → Problema de cache\n";
echo "- 'OPCIÓN NO ENCONTRADA' → ID no coincide\n";
echo "- 'Error en AJAX' → Problema de servidor\n\n";

echo "=== FIN DEL TEST ===\n";
?>