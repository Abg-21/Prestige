<?php
echo "<h1>🔧 Debug de Funcionamiento - Puestos</h1>";

// Limpiar log para que solo veamos los nuevos mensajes
file_put_contents(__DIR__ . '/storage/logs/laravel.log', '');

echo "<h2>1. 📊 Estado de la Base de Datos</h2>";

// Conexión directa a la BD
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=prestige', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>📋 Lista de Puestos Actual:</h3>";
    $stmt = $pdo->query("SELECT idPuestos, Puesto, Categoría FROM puestos ORDER BY idPuestos");
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Puesto</th><th>Categoría</th></tr>";
    
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['idPuestos']}</td>";
        echo "<td>{$row['Puesto']}</td>";
        echo "<td>{$row['Categoría']}</td>";
        echo "</tr>";
        $count++;
    }
    echo "</table>";
    echo "<p><strong>Total de puestos: {$count}</strong></p>";
    
    echo "<h3>👥 Empleados con Puestos:</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM empleados WHERE IdPuestoEmpleadoFK IS NOT NULL");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Empleados asignados a puestos: <strong>{$result['total']}</strong></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error BD: " . $e->getMessage() . "</p>";
}

echo "<h2>2. 🌐 Pruebas de Rutas</h2>";

// Función para hacer peticiones HTTP
function testRoute($url, $method = 'GET', $headers = []) {
    $context = stream_context_create([
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    $status = 'N/A';
    
    if (isset($http_response_header[0])) {
        preg_match('/HTTP\/\d\.\d\s+(\d+)/', $http_response_header[0], $matches);
        $status = isset($matches[1]) ? $matches[1] : 'Unknown';
    }
    
    return [
        'status' => $status,
        'response' => $response,
        'headers' => $http_response_header ?? []
    ];
}

$routes = [
    'Página principal puestos' => 'http://127.0.0.1:8000/puestos',
    'Modal crear puesto' => 'http://127.0.0.1:8000/puestos/create',
    'Lista puestos AJAX' => 'http://127.0.0.1:8000/puestos/lista'
];

foreach ($routes as $name => $url) {
    echo "<h3>🔗 {$name}</h3>";
    $result = testRoute($url, 'GET', [
        'X-Requested-With: XMLHttpRequest',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
    ]);
    
    echo "<p><strong>Status:</strong> {$result['status']}</p>";
    if ($result['response']) {
        echo "<p><strong>Longitud respuesta:</strong> " . strlen($result['response']) . " caracteres</p>";
        echo "<p><strong>Primeros 200 chars:</strong></p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow-y: auto;'>";
        echo htmlspecialchars(substr($result['response'], 0, 200));
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Sin respuesta</p>";
    }
}

echo "<h2>3. 📋 Verificación de Archivos</h2>";

$files = [
    'Vista modal puesto' => __DIR__ . '/resources/views/puestos/form_puesto_ajax.blade.php',
    'Vista candidatos' => __DIR__ . '/resources/views/candidatos/create_candidatos.blade.php',
    'Controlador puestos' => __DIR__ . '/app/Http/Controllers/PuestoController.php',
    'Modelo puesto' => __DIR__ . '/app/Models/Puesto.php'
];

foreach ($files as $name => $file) {
    echo "<p><strong>{$name}:</strong> ";
    if (file_exists($file)) {
        echo "✅ Existe (" . filesize($file) . " bytes)";
    } else {
        echo "❌ No existe";
    }
    echo "</p>";
}

echo "<h2>4. 🛠️ Prueba de Eliminación Directa</h2>";

// Crear un puesto de prueba y tratar de eliminarlo
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=prestige', 'root', '');
    
    // Crear puesto de prueba
    $stmt = $pdo->prepare("INSERT INTO puestos (Categoría, Puesto, Zona, Estado) VALUES (?, ?, ?, ?)");
    $stmt->execute(['PRUEBA', 'Puesto de Prueba DEBUG', 'Nacional', 'Activo']);
    
    $testId = $pdo->lastInsertId();
    echo "<p>✅ Puesto de prueba creado con ID: {$testId}</p>";
    
    // Verificar que existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM puestos WHERE idPuestos = ?");
    $stmt->execute([$testId]);
    $exists = $stmt->fetchColumn();
    echo "<p>Puesto existe antes de eliminar: " . ($exists ? '✅ SÍ' : '❌ NO') . "</p>";
    
    // Eliminar directamente
    $stmt = $pdo->prepare("DELETE FROM puestos WHERE idPuestos = ?");
    $result = $stmt->execute([$testId]);
    echo "<p>Resultado eliminación directa: " . ($result ? '✅ OK' : '❌ FALLÓ') . "</p>";
    
    // Verificar que se eliminó
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM puestos WHERE idPuestos = ?");
    $stmt->execute([$testId]);
    $stillExists = $stmt->fetchColumn();
    echo "<p>Puesto existe después de eliminar: " . ($stillExists ? '❌ SÍ (PROBLEMA)' : '✅ NO (CORRECTO)') . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error en prueba de eliminación: " . $e->getMessage() . "</p>";
}

echo "<h2>5. 📜 JavaScript Debug</h2>";
echo "<p>Para debuggear el modal, abre la consola del navegador (F12) y ejecuta:</p>";
echo "<pre style='background: #f0f0f0; padding: 10px;'>";
echo "console.log('Botón:', $('#btnNuevoPuesto').length);\n";
echo "console.log('Modal:', $('#modal-nuevo-puesto').length);\n";
echo "console.log('Content:', $('#modalPuestoContent').length);\n";
echo "// Para probar manualmente:\n";
echo "$('#btnNuevoPuesto').click();\n";
echo "</pre>";

echo "<hr>";
echo "<p><strong>🕐 Debug completado a las:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>📝 Revisa el log de Laravel después de probar:</strong> storage/logs/laravel.log</p>";
?>