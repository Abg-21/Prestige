<?php
echo "<h1>🔍 Debug Directo AJAX - Puestos</h1>";
echo "<p>Hora: " . date('Y-m-d H:i:s') . "</p>";

// Función para hacer peticiones HTTP con cookies
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $defaultHeaders = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept: application/json, text/html, */*',
        'X-Requested-With: XMLHttpRequest'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/debug_cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/debug_cookies.txt');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));
    curl_setopt($ch, CURLOPT_HEADER, true);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'headers' => $headers,
        'body' => $body
    ];
}

echo "<h2>1. 🔐 Login Primero</h2>";

// Hacer login
$loginData = http_build_query([
    'correo' => 'admin@grupoprestige.com.mx',
    'password' => '123456',
    '_token' => 'dummy' // Se actualizará
]);

// Obtener token CSRF del login
$loginPageResponse = makeRequest('http://127.0.0.1:8000/login');
echo "<p>Login page status: {$loginPageResponse['code']}</p>";

// Extraer token CSRF
preg_match('/name="csrf-token" content="([^"]+)"/', $loginPageResponse['body'], $matches);
$csrfToken = $matches[1] ?? 'not_found';
echo "<p>CSRF Token obtenido: " . substr($csrfToken, 0, 10) . "...</p>";

// Hacer login real
$loginData = http_build_query([
    'correo' => 'admin@grupoprestige.com.mx',
    'password' => '123456',
    '_token' => $csrfToken
]);

$loginResponse = makeRequest('http://127.0.0.1:8000/login', 'POST', $loginData, [
    'Content-Type: application/x-www-form-urlencoded',
    'X-CSRF-TOKEN: ' . $csrfToken
]);

echo "<p>Login attempt status: {$loginResponse['code']}</p>";

if ($loginResponse['code'] == 302) {
    echo "<p>✅ Login exitoso (redirect 302)</p>";
} else {
    echo "<p>❌ Login falló</p>";
    echo "<pre>" . htmlspecialchars(substr($loginResponse['body'], 0, 500)) . "</pre>";
}

echo "<h2>2. 🧪 Test de Rutas Debug</h2>";

// Test ruta de debug general
$debugResponse = makeRequest('http://127.0.0.1:8000/debug-ajax', 'GET', null, [
    'X-CSRF-TOKEN: ' . $csrfToken
]);

echo "<h3>Debug General:</h3>";
echo "<p>Status: {$debugResponse['code']}</p>";
echo "<p>Response: " . htmlspecialchars(substr($debugResponse['body'], 0, 300)) . "</p>";

// Test modal
$modalResponse = makeRequest('http://127.0.0.1:8000/debug-ajax/modal', 'GET', null, [
    'X-CSRF-TOKEN: ' . $csrfToken
]);

echo "<h3>Test Modal:</h3>";
echo "<p>Status: {$modalResponse['code']}</p>";
echo "<p>Response length: " . strlen($modalResponse['body']) . " chars</p>";
if ($modalResponse['code'] == 200) {
    echo "<p>✅ Modal funcionando</p>";
    echo "<p>Contenido: " . htmlspecialchars(substr($modalResponse['body'], 0, 200)) . "...</p>";
} else {
    echo "<p>❌ Modal falló</p>";
    echo "<pre>" . htmlspecialchars(substr($modalResponse['body'], 0, 500)) . "</pre>";
}

// Test eliminación
$eliminateResponse = makeRequest('http://127.0.0.1:8000/debug-ajax/eliminate', 'POST', 
    http_build_query(['puesto_id' => 2]), 
    [
        'Content-Type: application/x-www-form-urlencoded',
        'X-CSRF-TOKEN: ' . $csrfToken
    ]
);

echo "<h3>Test Eliminación:</h3>";
echo "<p>Status: {$eliminateResponse['code']}</p>";
if ($eliminateResponse['code'] == 200) {
    echo "<p>✅ Eliminación funcionando</p>";
    $data = json_decode($eliminateResponse['body'], true);
    if ($data) {
        echo "<p>Antes: {$data['antes']}, Después: {$data['despues']}</p>";
        echo "<p>¿Aún existe?: " . ($data['aun_existe'] ? 'SÍ' : 'NO') . "</p>";
    }
} else {
    echo "<p>❌ Eliminación falló</p>";
    echo "<pre>" . htmlspecialchars(substr($eliminateResponse['body'], 0, 500)) . "</pre>";
}

echo "<h2>3. 🌐 Test de Rutas Originales</h2>";

// Test ruta original create
$createResponse = makeRequest('http://127.0.0.1:8000/puestos/create', 'GET', null, [
    'X-CSRF-TOKEN: ' . $csrfToken
]);

echo "<h3>Puestos Create Original:</h3>";
echo "<p>Status: {$createResponse['code']}</p>";
echo "<p>Response length: " . strlen($createResponse['body']) . " chars</p>";

if ($createResponse['code'] != 200) {
    echo "<p>❌ Problema con ruta original</p>";
    echo "<pre>" . htmlspecialchars(substr($createResponse['body'], 0, 300)) . "</pre>";
}

// Test ruta lista
$listaResponse = makeRequest('http://127.0.0.1:8000/puestos/lista', 'GET', null, [
    'X-CSRF-TOKEN: ' . $csrfToken
]);

echo "<h3>Puestos Lista Original:</h3>";
echo "<p>Status: {$listaResponse['code']}</p>";
echo "<p>Response: " . htmlspecialchars(substr($listaResponse['body'], 0, 200)) . "</p>";

echo "<hr>";
echo "<p><strong>📝 Ahora revisa el log:</strong> storage/logs/laravel.log</p>";
echo "<p><strong>🕐 Test completado:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>