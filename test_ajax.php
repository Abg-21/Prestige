<?php
// Script para probar peticiones AJAX

echo "=== TEST AJAX ===\n";

// Simular petición AJAX para crear cliente
echo "1. SIMULANDO PETICIÓN AJAX CLIENTE:\n";

// Headers que enviamos
$headers = [
    'X-Requested-With: XMLHttpRequest',
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
];

// Datos de prueba
$cliente_data = [
    'Nombre' => 'Cliente Test AJAX',
    'Telefono' => '123456789',
    'Descripcion' => 'Descripcion test'
];

echo "Datos a enviar: " . json_encode($cliente_data, JSON_PRETTY_PRINT) . "\n";

// Probar URL
$url = 'http://127.0.0.1:8000/clientes';
echo "URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($cliente_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "\nRespuesta HTTP: $http_code\n";

if ($response) {
    // Separar headers del body
    $parts = explode("\r\n\r\n", $response, 2);
    $headers_part = $parts[0];
    $body_part = isset($parts[1]) ? $parts[1] : '';
    
    echo "\nHeaders de respuesta:\n";
    echo $headers_part . "\n";
    
    echo "\nPrimeros 500 caracteres del body:\n";
    echo substr($body_part, 0, 500) . "\n";
    
    // Buscar si contiene HTML de la lista de clientes
    if (strpos($body_part, 'Gestión de Clientes') !== false || strpos($body_part, 'cliente') !== false) {
        echo "✅ La respuesta contiene HTML de clientes\n";
    } else {
        echo "❌ La respuesta NO contiene HTML de clientes\n";
    }
} else {
    echo "❌ No se recibió respuesta\n";
}

echo "\n=== FIN TEST ===\n";
?>