<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Empleado;
use App\Helpers\CacheHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dompdf\Dompdf;

class AsistenciaController extends Controller
{
    /**
     * Mostrar la lista de asistencia
     */
    public function index(Request $request)
    {
        // Forzar no usar caché del navegador
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $hoy = Carbon::now('America/Mexico_City'); // Forzar zona horaria
        $año = $request->get('año', $hoy->year);
        $mes = $request->get('mes', $hoy->month);
        $estado = $request->get('estado', 'todos');
        $periodo = $request->get('periodo');
        $diaActual = $hoy->day;
        
        // Debug: Log de parámetros recibidos
        \Log::info('AsistenciaController::index - Parámetros:', [
            'hoy_completo' => $hoy->toDateTimeString(),
            'año' => $año,
            'mes' => $mes,
            'dia_actual' => $diaActual,
            'estado' => $estado,
            'periodo_recibido' => $periodo,
            'all_request_params' => $request->all(),
        ]);
        
        // Determinar el período basado en el día actual o usar el recibido
        if (!$periodo) {
            $periodo = Asistencia::determinarPeriodo($diaActual);
        }
        
        \Log::info('AsistenciaController::index - Período determinado:', ['periodo' => $periodo]);
        
        // Obtener todos los estados únicos para el filtro
        $estados = Empleado::whereNotNull('Estado')
            ->whereNull('Fecha_Egreso')
            ->whereNull('Eliminado_en')
            ->distinct()
            ->pluck('Estado')
            ->sort();

        \Log::info('AsistenciaController::index - Estados encontrados:', $estados->toArray());
        
        // Obtener empleados usando CacheHelper y aplicar filtros
        $empleadosCache = CacheHelper::getEmpleados()
            ->where('Fecha_Egreso', null);
        
        // Aplicar filtro por estado si no es "todos"
        if ($estado !== 'todos') {
            $empleadosCache = $empleadosCache->where('Estado', $estado);
        }
        
        $empleados = $empleadosCache->values(); // Reindexar la colección

        \Log::info('AsistenciaController::index - Empleados encontrados:', [
            'total' => $empleados->count(),
            'filtro_estado' => $estado,
            'empleados' => $empleados->pluck('Nombre', 'IdEmpleados')->toArray()
        ]);

        // Obtener o crear registros de asistencia para cada empleado
        $asistencias = [];
        foreach ($empleados as $empleado) {
            $asistencia = Asistencia::firstOrCreate([
                'empleado_id' => $empleado->IdEmpleados,
                'año' => $año,
                'mes' => $mes,
                'periodo' => $periodo
            ]);
            $asistencias[$empleado->IdEmpleados] = $asistencia;
        }

        // Calcular días del período actual
        $diasDelPeriodo = $this->getDiasDelPeriodo($año, $mes, $periodo);
        
        // Verificar si es el último día de la quincena para mostrar botón reporte
        $esUltimoDiaQuincena = $this->esUltimoDiaQuincena($año, $mes, $periodo, $diaActual);
        
        return view('asistencia.asistencia', compact(
            'empleados', 
            'asistencias', 
            'año', 
            'mes', 
            'periodo', 
            'diasDelPeriodo',
            'diaActual',
            'estados',
            'estado',
            'esUltimoDiaQuincena'
        ));
    }

    /**
     * Actualizar asistencia via AJAX
     */
    public function updateAsistencia(Request $request)
    {
        try {
            \Log::info('updateAsistencia called with data:', $request->all());
            
            $asistenciaId = $request->asistencia_id;
            $asistencia = null;
            
            // Si no hay ID de asistencia, crear una nueva
            if (!$asistenciaId && $request->has('crear_si_no_existe') && $request->crear_si_no_existe) {
                \Log::info('Creating new asistencia record');
                $asistencia = Asistencia::firstOrCreate([
                    'empleado_id' => $request->empleado_id,
                    'año' => $request->año,
                    'mes' => $request->mes,
                    'periodo' => $request->periodo
                ]);
                \Log::info('Asistencia created/found:', ['id' => $asistencia->id]);
            } else {
                \Log::info('Finding existing asistencia:', ['id' => $asistenciaId]);
                $asistencia = Asistencia::findOrFail($asistenciaId);
            }
            
            // Actualizar campo específico
            if ($request->has('campo') && $request->has('valor')) {
                $campo = $request->campo;
                $valor = $request->valor;
                
                \Log::info('Updating field:', ['campo' => $campo, 'valor' => $valor]);
                
                // Validar que el campo existe
                if (in_array($campo, $asistencia->getFillable())) {
                    $asistencia->$campo = $valor;
                    $asistencia->save();
                    
                    \Log::info('Field updated successfully');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Asistencia actualizada correctamente',
                        'asistencia_id' => $asistencia->id
                    ]);
                } else {
                    \Log::error('Campo no fillable:', ['campo' => $campo, 'fillable' => $asistencia->getFillable()]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Campo no válido'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar datos financieros
     */
    public function updateFinanciero(Request $request)
    {
        $request->validate([
            'asistencia_id' => 'required|exists:asistencia,id',
            'bono' => 'nullable|numeric|min:0',
            'prestamo' => 'nullable|numeric|min:0',
            'fonacot' => 'nullable|numeric|min:0',
            'estatus_finiquito' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:180'
        ]);

        try {
            $asistencia = Asistencia::findOrFail($request->asistencia_id);
            
            $asistencia->update([
                'bono' => $request->bono ?? 0,
                'prestamo' => $request->prestamo ?? 0,
                'fonacot' => $request->fonacot ?? 0,
                'estatus_finiquito' => $request->estatus_finiquito ?? 0,
                'observaciones' => $request->observaciones
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos financieros actualizados correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar período de vista
     */
    public function cambiarPeriodo(Request $request)
    {
        $año = $request->get('año', Carbon::now()->year);
        $mes = $request->get('mes', Carbon::now()->month);
        $periodo = $request->get('periodo', 'primera_quincena');

        return redirect()->route('asistencia.index', [
            'año' => $año,
            'mes' => $mes,
            'periodo' => $periodo
        ]);
    }

        /**
     * Obtener días del período
     */
    private function getDiasDelPeriodo($año, $mes, $periodo)
    {
        if ($periodo === 'primera_quincena') {
            return range(1, 15);
        } else {
            $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $año);
            return range(16, $diasEnMes);
        }
    }

    /**
     * Verificar si es el último día de la quincena
     */
    private function esUltimoDiaQuincena($año, $mes, $periodo, $diaActual)
    {
        if ($periodo === 'primera_quincena') {
            return $diaActual >= 15;
        } else {
            $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $año);
            return $diaActual >= $diasEnMes;
        }
    }

    /**
     * Guardar asistencia completa
     */
    public function guardarAsistencia(Request $request)
    {
        try {
            // La asistencia ya se guarda automáticamente con cada clic
            // Este método puede servir para validaciones adicionales o logs
            return response()->json([
                'success' => true,
                'message' => 'Asistencia guardada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar asistencia (opcional para futuro)
     */
    public function exportar(Request $request)
    {
        $año = $request->get('año', Carbon::now('America/Mexico_City')->year);
        $mes = $request->get('mes', Carbon::now('America/Mexico_City')->month);
        $periodo = $request->get('periodo', 'primera_quincena');
        $formato = $request->get('formato', 'excel');
        $estado = $request->get('estado', 'todos');

        try {
            // Obtener empleados usando CacheHelper con filtros
            $empleadosCache = CacheHelper::getEmpleados()
                ->where('Fecha_Egreso', null);
            
            if ($estado !== 'todos') {
                $empleadosCache = $empleadosCache->where('Estado', $estado);
            }
            
            $empleados = $empleadosCache->values();

            // Obtener asistencias
            $asistencias = [];
            foreach ($empleados as $empleado) {
                $asistencia = Asistencia::where([
                    'empleado_id' => $empleado->IdEmpleados,
                    'año' => $año,
                    'mes' => $mes,
                    'periodo' => $periodo
                ])->first();
                
                if ($asistencia) {
                    $asistencias[$empleado->IdEmpleados] = $asistencia;
                }
            }

            $diasDelPeriodo = $this->getDiasDelPeriodo($año, $mes, $periodo);

            if ($formato === 'pdf') {
                return $this->exportarPDF($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo);
            } else {
                return $this->exportarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar a Excel
     */
    private function exportarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo)
    {
        $mesesEspanol = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $filename = "reporte_asistencia_{$periodo}_{$mes}_{$año}.csv";
        
        $headers = array(
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function() use ($empleados, $asistencias, $diasDelPeriodo, $mesesEspanol, $año, $mes, $periodo) {
            $file = fopen('php://output', 'w');
            
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ["REPORTE DE ASISTENCIA - " . strtoupper($mesesEspanol[$mes]) . " $año"]);
            fputcsv($file, ["Periodo: " . ($periodo === 'primera_quincena' ? 'Primera Quincena (1-15)' : 'Segunda Quincena (16-' . max($diasDelPeriodo) . ')')]);
            fputcsv($file, ["Generado: " . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, ["Total Empleados: " . count($empleados)]);
            fputcsv($file, []);
            
            fputcsv($file, ["LEYENDA DE ESTADOS:"]);
            foreach (\App\Models\Asistencia::ESTADOS as $key => $estado_info) {
                fputcsv($file, [
                    $estado_info['icono'] . " " . $estado_info['nombre'],
                    "Color: " . $estado_info['color']
                ]);
            }
            fputcsv($file, []);
            
            $header = ['EMPLEADO', 'CLIENTE', 'ESTADO/ZONA', 'PUESTO'];
            foreach ($diasDelPeriodo as $dia) {
                $header[] = "Dia {$dia}";
            }
            $header = array_merge($header, ['PRESENTE', 'FALTAS', 'OTROS', 'BONO', 'PRESTAMO', 'FONACOT', 'FINIQUITO', 'OBSERVACIONES']);
            fputcsv($file, $header);
            
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                $totales = $asistencia ? $asistencia->calcularTotales() : [];
                
                $row = [
                    $empleado->Nombre . ' ' . $empleado->Apellido_Paterno . ' ' . ($empleado->Apellido_Materno ?? ''),
                    $empleado->puesto->cliente->Nombre ?? 'Sin cliente',
                    $empleado->Estado ?? 'Sin especificar',
                    $empleado->puesto->Puesto ?? 'Sin puesto'
                ];
                
                foreach ($diasDelPeriodo as $dia) {
                    $valor = $asistencia ? $asistencia->getAsistenciaDia($dia) : '';
                    if ($valor && isset(\App\Models\Asistencia::ESTADOS[$valor])) {
                        $estado_info = \App\Models\Asistencia::ESTADOS[$valor];
                        $row[] = $estado_info['icono'] . " " . $estado_info['nombre'];
                    } else {
                        $row[] = 'Sin registro';
                    }
                }
                
                $row[] = isset($totales['presente']) ? $totales['presente'] : 0;
                $row[] = isset($totales['falta']) ? $totales['falta'] : 0;
                
                $otrosTotal = 0;
                $estadosOtros = ['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'];
                foreach ($estadosOtros as $estado) {
                    $otrosTotal += isset($totales[$estado]) ? $totales[$estado] : 0;
                }
                $row[] = $otrosTotal;
                
                $row[] = $asistencia && $asistencia->bono ? '$' . number_format($asistencia->bono, 2) : '';
                $row[] = $asistencia && $asistencia->prestamo ? '$' . number_format($asistencia->prestamo, 2) : '';
                $row[] = $asistencia && $asistencia->fonacot ? '$' . number_format($asistencia->fonacot, 2) : '';
                $row[] = $asistencia && $asistencia->estatus_finiquito ? '$' . number_format($asistencia->estatus_finiquito, 2) : '';
                $row[] = $asistencia ? ($asistencia->observaciones ?: '') : '';
                
                fputcsv($file, $row);
            }
            
            fputcsv($file, []);
            fputcsv($file, ["RESUMEN GENERAL"]);
            
            $totalPresentes = 0;
            $totalFaltas = 0; 
            $totalOtros = 0;
            
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                if ($asistencia) {
                    $totales = $asistencia->calcularTotales();
                    $totalPresentes += isset($totales['presente']) ? $totales['presente'] : 0;
                    $totalFaltas += isset($totales['falta']) ? $totales['falta'] : 0;
                    
                    foreach (['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'] as $estado) {
                        $totalOtros += isset($totales[$estado]) ? $totales[$estado] : 0;
                    }
                }
            }
            
            fputcsv($file, ["Total dias presentes: " . $totalPresentes]);
            fputcsv($file, ["Total dias faltas: " . $totalFaltas]);
            fputcsv($file, ["Total otros estados: " . $totalOtros]);
            fputcsv($file, ["Total general: " . ($totalPresentes + $totalFaltas + $totalOtros)]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**

        $callback = function() use ($empleados, $asistencias, $diasDelPeriodo, $mesesEspanol, $año, $mes, $periodo) {
            $file = fopen('php://output', 'w');
            
            // Agregar BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // ==================== ENCABEZADO PRINCIPAL ====================
            fputcsv($file, ["REPORTE DE ASISTENCIA - " . strtoupper($mesesEspanol[$mes]) . " $año"]);
            fputcsv($file, ["Periodo: " . ($periodo === 'primera_quincena' ? 'Primera Quincena (1-15)' : 'Segunda Quincena (16-' . max($diasDelPeriodo) . ')')]);
            fputcsv($file, ["Generado: " . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, ["Total Empleados: " . count($empleados)]);
            fputcsv($file, []); // Línea vacía
            
            // ==================== LEYENDA DE ESTADOS ====================
            fputcsv($file, ["LEYENDA DE ESTADOS:"]);
            foreach (\App\Models\Asistencia::ESTADOS as $key => $estado_info) {
                fputcsv($file, [
                    $estado_info['icono'] . " " . $estado_info['nombre'],
                    "Color: " . $estado_info['color']
                ]);
            }
            fputcsv($file, []); // Línea vacía

            // ==================== ENCABEZADOS DE DATOS ====================
            $header = ['EMPLEADO', 'CLIENTE', 'ESTADO/ZONA', 'PUESTO'];
            foreach ($diasDelPeriodo as $dia) {
                $header[] = "Dia {$dia}";
            }
            $header = array_merge($header, [
                'PRESENTE', 
                'FALTAS', 
                'OTROS', 
                'BONO', 
                'PRESTAMO', 
                'FONACOT', 
                'FINIQUITO', 
                'OBSERVACIONES'
            ]);
            fputcsv($file, $header);
            
            // ==================== DATOS DE EMPLEADOS ====================
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                $totales = $asistencia ? $asistencia->calcularTotales() : [];
                
                $row = [
                    $empleado->Nombre . ' ' . $empleado->Apellido_Paterno . ' ' . ($empleado->Apellido_Materno ?? ''),
                    $empleado->puesto->cliente->Nombre ?? 'Sin cliente',
                    $empleado->Estado ?? 'Sin especificar',
                    $empleado->puesto->Puesto ?? 'Sin puesto'
                ];
                
                // Agregar días con estados
                foreach ($diasDelPeriodo as $dia) {
                    $valor = $asistencia ? $asistencia->getAsistenciaDia($dia) : '';
                    if ($valor && isset(\App\Models\Asistencia::ESTADOS[$valor])) {
                        $estado_info = \App\Models\Asistencia::ESTADOS[$valor];
                        $row[] = $estado_info['icono'] . " " . $estado_info['nombre'];
                    } else {
                        $row[] = 'Sin registro';
                    }
                }
                
                // Totales
                $row[] = $totales['presente'] ?? 0;
                $row[] = $totales['falta'] ?? 0;
                
                $otrosTotal = 0;
                foreach (['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'] as $estado) {
                    $otrosTotal += $totales[$estado] ?? 0;
                }
                $row[] = $otrosTotal;
                
                // Datos financieros
                $row[] = $asistencia && $asistencia->bono ? '$' . number_format($asistencia->bono, 2) : '';
                $row[] = $asistencia && $asistencia->prestamo ? '$' . number_format($asistencia->prestamo, 2) : '';
                $row[] = $asistencia && $asistencia->fonacot ? '$' . number_format($asistencia->fonacot, 2) : '';
                $row[] = $asistencia && $asistencia->estatus_finiquito ? '$' . number_format($asistencia->estatus_finiquito, 2) : '';
                $row[] = $asistencia ? ($asistencia->observaciones ?: '') : '';
                
                fputcsv($file, $row);
            }
            
            // ==================== RESUMEN FINAL ====================
            echo '            
            // ==================== RESUMEN FINAL ====================
            fputcsv($file, []); // Línea vacía
            fputcsv($file, ["RESUMEN GENERAL"]);
            
            $totalPresentes = 0;
            $totalFaltas = 0;
            $totalOtros = 0;
            
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                if ($asistencia) {
                    $totales = $asistencia->calcularTotales();
                    $totalPresentes += isset($totales['presente']) ? $totales['presente'] : 0;
                    $totalFaltas += isset($totales['falta']) ? $totales['falta'] : 0;
                    
                    $estadosOtros = ['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'];
                    foreach ($estadosOtros as $estado) {
                        $totalOtros += isset($totales[$estado]) ? $totales[$estado] : 0;
                    }
                }
            }
            
            fputcsv($file, ["Total dias presentes: " . $totalPresentes]);
            fputcsv($file, ["Total dias faltas: " . $totalFaltas]);
            fputcsv($file, ["Total otros estados: " . $totalOtros]);
            fputcsv($file, ["Total general: " . ($totalPresentes + $totalFaltas + $totalOtros)]);
            
            fclose($file);
        };' . (count($diasDelPeriodo) + 12) . '">&nbsp;</td></tr>';
            echo '<tr class="header"><td colspan="' . (count($diasDelPeriodo) + 12) . '">RESUMEN GENERAL</td></tr>';
            
            $totalPresentes = 0;
            $totalFaltas = 0;
            $totalOtros = 0;
            
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                if ($asistencia) {
                    $totales = $asistencia->calcularTotales();
                    $totalPresentes += $totales['presente'] ?? 0;
                    $totalFaltas += $totales['falta'] ?? 0;
                    foreach (['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'] as $estado) {
                        $totalOtros += $totales[$estado] ?? 0;
                    }
                }
            }
            
            echo '<tr>';
            echo '<td colspan="4">TOTALES:</td>';
            echo '<td colspan="' . count($diasDelPeriodo) . '"></td>';
            echo '<td class="presente">PRESENTES: ' . $totalPresentes . '</td>';
            echo '<td class="falta">FALTAS: ' . $totalFaltas . '</td>';
            echo '<td>OTROS: ' . $totalOtros . '</td>';
            echo '<td colspan="5"></td>';
            echo '</tr>';
            
            echo '</table>';
            echo '<br><br>';
            echo '<h3>Estadísticas Generales:</h3>';
            echo '<p><strong class="presente">Total días presentes: ' . $totalPresentes . '</strong></p>';
            echo '<p><strong class="falta">Total días faltas: ' . $totalFaltas . '</strong></p>';
            echo '<p><strong>Total otros estados: ' . $totalOtros . '</strong></p>';
            echo '<p><strong>Total general: ' . ($totalPresentes + $totalFaltas + $totalOtros) . '</strong></p>';
            
            echo '</body></html>';
            
            if (isset($output)) fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar a PDF con gráficas
     */
    private function exportarPDF($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo)
    {
        $mesesEspanol = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $periodoTexto = $periodo === 'primera_quincena' ? 'Primera Quincena (1-15)' : 'Segunda Quincena (16-' . max($diasDelPeriodo) . ')';
        
        $html = view('reportes.asistencia-pdf', [
            'empleados' => $empleados,
            'asistencias' => $asistencias,
            'año' => $año,
            'mes' => $mes,
            'mesTexto' => $mesesEspanol[$mes],
            'periodo' => $periodo,
            'periodoTexto' => $periodoTexto,
            'diasDelPeriodo' => $diasDelPeriodo,
        ])->render();
        
        // Si no existe DomPDF, retornar HTML directamente
        try {
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            
            $filename = "reporte_asistencia_{$periodo}_{$mes}_{$año}.pdf";
            return response($dompdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            // Si DomPDF no está disponible, retornar como HTML para visualización
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'inline; filename="reporte_asistencia_' . $periodo . '_' . $mes . '_' . $año . '.html"');
        }
    }
}
