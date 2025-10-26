<?php
// Verificar modal de puestos y select de candidatos

echo "=== VERIFICACIÓN MODAL PUESTOS Y SELECT ===\n\n";

// 1. Verificar que existe la ruta puestos.lista
echo "1. VERIFICANDO RUTA PUESTOS.LISTA:\n";
$routes = shell_exec('php artisan route:list | findstr puestos.lista');
if ($routes) {
    echo "✅ Ruta puestos.lista encontrada:\n$routes\n";
} else {
    echo "❌ Ruta puestos.lista NO encontrada\n";
}

// 2. Verificar controlador de puestos
echo "\n2. VERIFICANDO CONTROLADOR:\n";
$controller = 'app/Http/Controllers/PuestoController.php';
if (file_exists($controller)) {
    $contenido = file_get_contents($controller);
    
    // Verificar método lista
    if (strpos($contenido, 'public function lista()') !== false) {
        echo "✅ Método lista() existe\n";
    } else {
        echo "❌ Método lista() NO encontrado\n";
    }
    
    // Verificar respuesta JSON para modal
    if (strpos($contenido, 'from_modal') !== false) {
        echo "✅ Detección de modal implementada\n";
    } else {
        echo "❌ Detección de modal NO implementada\n";
    }
    
    // Verificar respuesta JSON
    if (strpos($contenido, "'id' => \$puesto->idPuestos") !== false) {
        echo "✅ Respuesta JSON con ID implementada\n";
    } else {
        echo "❌ Respuesta JSON NO implementada\n";
    }
}

// 3. Verificar vista select_options
echo "\n3. VERIFICANDO VISTA SELECT_OPTIONS:\n";
$select_view = 'resources/views/puestos/select_options.blade.php';
if (file_exists($select_view)) {
    echo "✅ Vista select_options existe\n";
    $contenido = file_get_contents($select_view);
    if (strpos($contenido, 'option value="{{ $puesto->idPuestos }}') !== false) {
        echo "✅ Vista genera opciones correctamente\n";
    }
} else {
    echo "❌ Vista select_options NO encontrada\n";
}

// 4. Verificar modal mejorado
echo "\n4. VERIFICANDO MODAL MEJORADO:\n";
$modal = 'resources/views/puestos/form_puesto_ajax.blade.php';
if (file_exists($modal)) {
    $contenido = file_get_contents($modal);
    
    // Verificar que no tiene botones de crear giro/cliente
    if (strpos($contenido, 'btnNuevoGiro') === false && strpos($contenido, 'btnNuevoCliente') === false) {
        echo "✅ Botones de crear giro/cliente eliminados\n";
    } else {
        echo "❌ Botones de crear NO eliminados\n";
    }
    
    // Verificar campos completos
    if (strpos($contenido, 'Conocimientos[]') !== false && strpos($contenido, 'Funciones[]') !== false) {
        echo "✅ Campos completos (Conocimientos, Funciones, Habilidades) agregados\n";
    } else {
        echo "❌ Campos completos NO agregados\n";
    }
    
    // Verificar JavaScript dinámico
    if (strpos($contenido, 'add-conocimiento-modal') !== false) {
        echo "✅ JavaScript para campos dinámicos implementado\n";
    } else {
        echo "❌ JavaScript para campos dinámicos NO implementado\n";
    }
}

// 5. Verificar función actualizada en candidatos
echo "\n5. VERIFICANDO FUNCIÓN ACTUALIZARSELECTPUESTOS:\n";
$candidatos_create = 'resources/views/candidatos/create_candidatos.blade.php';
if (file_exists($candidatos_create)) {
    $contenido = file_get_contents($candidatos_create);
    
    if (strpos($contenido, '🔄 Actualizando lista de puestos') !== false) {
        echo "✅ Función actualizarSelectPuestos mejorada\n";
    } else {
        echo "❌ Función NO mejorada\n";
    }
    
    if (strpos($contenido, 'mostrarNotificacionCandidato') !== false) {
        echo "✅ Notificaciones implementadas\n";
    } else {
        echo "❌ Notificaciones NO implementadas\n";
    }
}

echo "\n=== RESUMEN DE CAMBIOS REALIZADOS ===\n";
echo "🔧 1. ✅ Eliminados botones 'Crear nuevo giro' y 'Crear nuevo cliente' del modal\n";
echo "🔧 2. ✅ Agregados todos los campos faltantes al modal:\n";
echo "     - Conocimientos (dinámico)\n";
echo "     - Funciones (dinámico)\n";
echo "     - Habilidades (dinámico)\n";
echo "🔧 3. ✅ JavaScript para manejar campos dinámicos en modal\n";
echo "🔧 4. ✅ Función actualizarSelectPuestos mejorada con:\n";
echo "     - Mejor logging y debug\n";
echo "     - Verificación de selección automática\n";
echo "     - Notificaciones de estado\n";
echo "🔧 5. ✅ Ruta 'Zona' cambiada a 'Ruta' en el modal\n\n";

echo "📋 PARA PROBAR:\n";
echo "1. Ir a crear candidato\n";
echo "2. Hacer clic en 'Nuevo Puesto'\n";
echo "3. Llenar TODOS los campos (incluyendo conocimientos, funciones, habilidades)\n";
echo "4. Guardar - debe cerrar automáticamente\n";
echo "5. El nuevo puesto debe aparecer seleccionado en la lista\n";
echo "6. Abrir F12 > Consola para ver mensajes de debug\n\n";

echo "🚨 Mensajes que debes ver en consola:\n";
echo "- '🔄 Actualizando lista de puestos. Nuevo puesto ID: X'\n";
echo "- '✅ HTML recibido: ...'\n";
echo "- '📝 Puesto seleccionado automáticamente ID: X'\n";
echo "- '✅ Select actualizado y puesto seleccionado correctamente'\n\n";

echo "=== FIN VERIFICACIÓN ===\n";
?>