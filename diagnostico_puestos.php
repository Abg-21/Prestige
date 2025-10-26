<?php
// Script para diagnosticar problemas con puestos

echo "=== DIAGNÓSTICO DE PUESTOS ===\n\n";

// 1. Verificar estructura de la tabla puestos
echo "1. VERIFICANDO ESTRUCTURA DE PUESTOS:\n";

try {
    $puestos = DB::table('puestos')->limit(3)->get();
    
    if ($puestos->count() > 0) {
        echo "✅ Tabla puestos tiene datos (" . $puestos->count() . " registros de muestra)\n";
        
        $primer_puesto = $puestos->first();
        echo "📋 Campos del primer puesto:\n";
        foreach ($primer_puesto as $campo => $valor) {
            $valor_muestra = is_string($valor) ? substr($valor, 0, 50) : $valor;
            echo "   - $campo: $valor_muestra\n";
        }
    } else {
        echo "⚠️ Tabla puestos está vacía\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error al acceder a tabla puestos: " . $e->getMessage() . "\n";
}

// 2. Probar relaciones
echo "\n2. VERIFICANDO RELACIONES:\n";

try {
    // Usar el modelo Puesto para probar relaciones
    $puesto_con_relaciones = App\Models\Puesto::with(['giro', 'cliente'])->first();
    
    if ($puesto_con_relaciones) {
        echo "✅ Puesto encontrado: " . $puesto_con_relaciones->Puesto . "\n";
        
        // Verificar giro
        if ($puesto_con_relaciones->giro) {
            echo "✅ Giro relacionado: " . $puesto_con_relaciones->giro->Nombre . "\n";
        } else {
            echo "❌ No hay giro relacionado (FK: " . $puesto_con_relaciones->id_GiroPuestoFK . ")\n";
        }
        
        // Verificar cliente
        if ($puesto_con_relaciones->cliente) {
            echo "✅ Cliente relacionado: " . $puesto_con_relaciones->cliente->Nombre . "\n";
        } else {
            echo "❌ No hay cliente relacionado (FK: " . $puesto_con_relaciones->id_ClientePuestoFK . ")\n";
        }
        
        // Verificar campos JSON
        echo "\n📄 Campos JSON:\n";
        foreach (['Conocimientos', 'Funciones', 'Habilidades'] as $campo) {
            $valor = $puesto_con_relaciones->$campo;
            echo "   - $campo: ";
            if (empty($valor) || $valor === '[]' || $valor === 'null') {
                echo "vacío\n";
            } else {
                $decoded = json_decode($valor, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    echo "JSON válido (" . count($decoded) . " elementos)\n";
                } else {
                    echo "texto plano: " . substr($valor, 0, 30) . "\n";
                }
            }
        }
        
    } else {
        echo "❌ No se encontraron puestos\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error al verificar relaciones: " . $e->getMessage() . "\n";
}

// 3. Probar métodos del controlador
echo "\n3. SIMULANDO MÉTODOS DEL CONTROLADOR:\n";

try {
    // Simular edit method
    $puesto = App\Models\Puesto::first();
    
    if ($puesto) {
        echo "🔧 Probando método edit con puesto ID: " . $puesto->idPuestos . "\n";
        
        // Probar la lógica de decodificación
        foreach (['Conocimientos', 'Funciones', 'Habilidades'] as $campo) {
            $valor = $puesto->$campo;
            echo "   - Procesando $campo:\n";
            
            if (is_null($valor) || $valor === '') {
                echo "     → Convertido a array vacío\n";
            } elseif (is_array($valor)) {
                echo "     → Ya es array\n";
            } elseif (is_string($valor) && (substr($valor, 0, 1) === '[' || substr($valor, 0, 1) === '{')) {
                $decoded = json_decode($valor, true);
                if ($decoded !== null) {
                    echo "     → Decodificado JSON exitosamente (" . count($decoded) . " elementos)\n";
                } else {
                    echo "     → ERROR: JSON inválido\n";
                }
            } else {
                echo "     → Separado por comas\n";
            }
        }
        
    }
    
} catch (Exception $e) {
    echo "❌ Error en simulación de controlador: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DIAGNÓSTICO ===\n";
?>