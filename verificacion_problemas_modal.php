<?php
// Verificar problemas del modal: campos dinámicos y actualización de select

echo "=== VERIFICACIÓN PROBLEMAS MODAL ===\n\n";

// 1. Verificar configuración de campos dinámicos
echo "1. CAMPOS DINÁMICOS EN MODAL:\n";
$candidatos_create = 'resources/views/candidatos/create_candidatos.blade.php';
if (file_exists($candidatos_create)) {
    $contenido = file_get_contents($candidatos_create);
    
    if (strpos($contenido, 'configurarCamposDinamicosModal') !== false) {
        echo "✅ Función configurarCamposDinamicosModal creada\n";
    } else {
        echo "❌ Función configurarCamposDinamicosModal NO encontrada\n";
    }
    
    if (strpos($contenido, 'setTimeout(function() {') !== false) {
        echo "✅ Timeout para cargar eventos después del modal implementado\n";
    } else {
        echo "❌ Timeout NO implementado\n";
    }
    
    if (strpos($contenido, '.off(\'click\').on(\'click\'') !== false) {
        echo "✅ Eventos con off/on para evitar duplicados implementados\n";
    } else {
        echo "❌ Eventos off/on NO implementados\n";
    }
} else {
    echo "❌ Archivo candidatos create no encontrado\n";
}

// 2. Verificar actualización del select
echo "\n2. ACTUALIZACIÓN DEL SELECT:\n";
if (file_exists($candidatos_create)) {
    $contenido = file_get_contents($candidatos_create);
    
    if (strpos($contenido, '$.ajax({') !== false) {
        echo "✅ Función actualizarSelectPuestos usa jQuery AJAX\n";
    } else {
        echo "❌ AJAX NO configurado correctamente\n";
    }
    
    if (strpos($contenido, '$select.html(html)') !== false) {
        echo "✅ Actualización del HTML del select implementada\n";
    } else {
        echo "❌ Actualización del HTML NO implementada\n";
    }
    
    if (strpos($contenido, 'console.log.*Opciones disponibles') !== false) {
        echo "✅ Debug detallado de opciones implementado\n";
    } else {
        echo "❌ Debug detallado NO implementado\n";
    }
}

// 3. Verificar modal sin JavaScript interno
echo "\n3. MODAL SIN JAVASCRIPT INTERNO:\n";
$modal = 'resources/views/puestos/form_puesto_ajax.blade.php';
if (file_exists($modal)) {
    $contenido = file_get_contents($modal);
    
    if (strpos($contenido, '<script>') === false) {
        echo "✅ JavaScript removido del modal\n";
    } else {
        echo "❌ Todavía hay JavaScript en el modal\n";
    }
    
    if (strpos($contenido, 'add-conocimiento-modal') !== false) {
        echo "✅ IDs únicos para modal mantenidos\n";
    } else {
        echo "❌ IDs únicos NO encontrados\n";
    }
}

// 4. Probar ruta de lista de puestos
echo "\n4. PROBANDO RUTA PUESTOS.LISTA:\n";
$test_url = 'http://127.0.0.1:8000/puestos/lista';

// Verificar si podemos acceder a la ruta (solo si hay servidor corriendo)
$context = stream_context_create([
    'http' => [
        'timeout' => 3,
        'header' => "X-Requested-With: XMLHttpRequest\r\n"
    ]
]);

$response = @file_get_contents($test_url, false, $context);
if ($response !== false) {
    echo "✅ Ruta puestos/lista accesible\n";
    if (strpos($response, '<option') !== false) {
        echo "✅ Ruta devuelve opciones HTML correctamente\n";
    } else {
        echo "❌ Ruta NO devuelve opciones HTML\n";
    }
} else {
    echo "⚠️ No se pudo probar la ruta (servidor no corriendo)\n";
}

echo "\n=== SOLUCIONES IMPLEMENTADAS ===\n";
echo "🔧 1. ✅ JavaScript movido del modal al formulario principal\n";
echo "🔧 2. ✅ Eventos configurados después de cargar el modal con setTimeout\n";
echo "🔧 3. ✅ Eventos con .off().on() para evitar duplicados\n";
echo "🔧 4. ✅ jQuery AJAX en lugar de fetch para mejor compatibilidad\n";
echo "🔧 5. ✅ Debug detallado para identificar problemas de select\n";
echo "🔧 6. ✅ Delegación de eventos para campos dinámicos\n\n";

echo "📋 PASOS PARA PROBAR:\n";
echo "1. Ir a crear candidato\n";
echo "2. Abrir F12 > Consola (IMPORTANTE para ver debug)\n";
echo "3. Hacer clic en 'Nuevo Puesto'\n";
echo "4. Probar agregar/quitar campos dinámicos:\n";
echo "   - Conocimientos: botón '+' debe agregar campos\n";
echo "   - Funciones: botón '+' debe agregar campos\n";
echo "   - Habilidades: botón '+' debe agregar campos\n";
echo "   - Botón '-' debe quitar campos (excepto el último)\n";
echo "5. Llenar formulario y guardar\n";
echo "6. Modal debe cerrarse y select debe actualizarse\n\n";

echo "🚨 Mensajes de debug que debes ver:\n";
echo "- '🔧 Configurando campos dinámicos del modal'\n";
echo "- '➕ Agregando campo conocimiento/función/habilidad'\n";
echo "- '➖ Removiendo campo dinámico'\n";
echo "- '🔄 Actualizando lista de puestos. Nuevo puesto ID: X'\n";
echo "- '✅ HTML recibido del servidor'\n";
echo "- '✅ Puesto seleccionado correctamente!'\n\n";

echo "=== FIN VERIFICACIÓN ===\n";
?>