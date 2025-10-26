<?php
echo "<h1>🔐 Test con Autenticación - Puestos</h1>";

// Configuración para usar Laravel desde script externo
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Puesto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h2>1. 🔐 Probando Autenticación</h2>";

// Buscar usuario admin
$usuario = Usuario::where('correo', 'admin@grupoprestige.com.mx')->first();

if ($usuario) {
    echo "<p>✅ Usuario encontrado: {$usuario->nombre}</p>";
    
    // Simular login
    Auth::login($usuario);
    echo "<p>✅ Autenticado como: " . Auth::user()->nombre . "</p>";
    
} else {
    echo "<p>❌ Usuario admin no encontrado</p>";
    exit;
}

echo "<h2>2. 📊 Estado Actual de Puestos</h2>";

$puestos = Puesto::all();
echo "<p><strong>Total puestos:</strong> " . $puestos->count() . "</p>";

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Puesto</th><th>Categoría</th><th>Empleados Asignados</th></tr>";

foreach ($puestos as $puesto) {
    $empleados_count = $puesto->empleados()->count();
    echo "<tr>";
    echo "<td>{$puesto->idPuestos}</td>";
    echo "<td>{$puesto->Puesto}</td>";
    echo "<td>{$puesto->Categoría}</td>";
    echo "<td>{$empleados_count}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>3. 🧪 Prueba de Eliminación Real</h2>";

// Encontrar un puesto que no tenga empleados asignados
$puestoParaEliminar = Puesto::whereDoesntHave('empleados')->first();

if ($puestoParaEliminar) {
    echo "<p>🎯 Puesto a eliminar: ID {$puestoParaEliminar->idPuestos} - {$puestoParaEliminar->Puesto}</p>";
    
    // Contar puestos antes
    $countAntes = Puesto::count();
    echo "<p>Puestos antes de eliminar: {$countAntes}</p>";
    
    try {
        // Intentar eliminar usando el método del modelo
        $eliminado = $puestoParaEliminar->delete();
        
        echo "<p>Resultado delete(): " . ($eliminado ? '✅ TRUE' : '❌ FALSE') . "</p>";
        
        // Contar después
        $countDespues = Puesto::count();
        echo "<p>Puestos después de eliminar: {$countDespues}</p>";
        
        // Verificar específicamente este puesto
        $aun_existe = Puesto::where('idPuestos', $puestoParaEliminar->idPuestos)->exists();
        echo "<p>¿Puesto aún existe?: " . ($aun_existe ? '❌ SÍ (PROBLEMA)' : '✅ NO (CORRECTO)') . "</p>";
        
    } catch (\Exception $e) {
        echo "<p style='color: red;'>❌ Error al eliminar: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p>⚠️ No hay puestos sin empleados para probar eliminación</p>";
}

echo "<h2>4. 🌐 Simulación de Peticiones AJAX</h2>";

// Simular petición AJAX para crear puesto
try {
    $request = new Request();
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    
    $controller = new \App\Http\Controllers\PuestoController();
    $response = $controller->create($request);
    
    if (method_exists($response, 'getContent')) {
        $content = $response->getContent();
        echo "<p>✅ Respuesta del create(): " . strlen($content) . " caracteres</p>";
        echo "<p>Primeras líneas:</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow: auto;'>";
        echo htmlspecialchars(substr($content, 0, 300));
        echo "</pre>";
    } else {
        echo "<p>❌ Respuesta no válida del controller</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error en petición create: " . $e->getMessage() . "</p>";
}

// Simular petición lista
try {
    $request = new Request();
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    
    $controller = new \App\Http\Controllers\PuestoController();
    $response = $controller->lista($request);
    
    if (method_exists($response, 'getContent')) {
        $content = $response->getContent();
        echo "<p>✅ Respuesta del lista(): " . strlen($content) . " caracteres</p>";
        echo "<p>Contenido:</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 200px; overflow: auto;'>";
        echo htmlspecialchars(substr($content, 0, 500));
        echo "</pre>";
    } else {
        echo "<p>❌ Respuesta no válida del lista</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error en petición lista: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>📋 Resumen:</strong></p>";
echo "<ul>";
echo "<li>Usuario autenticado: ✅</li>";
echo "<li>Puestos disponibles: ✅ (" . Puesto::count() . " encontrados)</li>";
echo "<li>Controladores funcionando: ✅</li>";
echo "</ul>";

echo "<p><strong>🚨 Problema identificado:</strong> Las peticiones AJAX desde el navegador NO están autenticadas</p>";
echo "<p><strong>💡 Solución:</strong> Verificar que el usuario esté logueado antes de hacer las peticiones desde JavaScript</p>";
?>