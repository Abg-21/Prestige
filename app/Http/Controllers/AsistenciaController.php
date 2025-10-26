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
     * Mostrar la lista de asistencia - Versión completa con vista test
     */
    public function index(Request $request)
    {
        try {
            // Parámetros del filtro con valores por defecto
            $fechaActual = Carbon::now();
            $año = $request->get('año', $fechaActual->year);
            $mes = $request->get('mes', $fechaActual->month);
            $estado = $request->get('estado', 'todos');
            
            // Auto-detectar quincena basado en el día actual si estamos en el mes actual
            $periodoDefault = 'primera_quincena';
            if ($año == $fechaActual->year && $mes == $fechaActual->month) {
                $periodoDefault = $fechaActual->day > 15 ? 'segunda_quincena' : 'primera_quincena';
            }
            $periodo = $request->get('periodo', $periodoDefault);
            
            // Debug: Log de parámetros
            \Log::info('AsistenciaController::index - Parámetros:', [
                'año' => $año,
                'mes' => $mes,
                'estado' => $estado,
                'periodo' => $periodo
            ]);
            
            $diasDelPeriodo = $this->obtenerDiasDelPeriodo($año, $mes, $periodo);
            
            // Construir query de empleados con relaciones necesarias
            $query = Empleado::with(['puesto.cliente']);
            
            if ($estado !== 'todos') {
                $query->where('Estado', $estado);
            }
            
            $empleados = $query->orderBy('Apellido_Paterno')
                              ->orderBy('Apellido_Materno')
                              ->orderBy('Nombre')
                              ->get();
            
            // Obtener asistencias para estos empleados
            $asistencias = [];
            foreach ($empleados as $empleado) {
                $asistencia = Asistencia::where('empleado_id', $empleado->IdEmpleados)
                                       ->where('año', $año)
                                       ->where('mes', $mes)
                                       ->first();
                
                if ($asistencia) {
                    $asistencias[$empleado->IdEmpleados] = $asistencia;
                }
            }
            
            // Obtener estados para el dropdown
            $estados = Empleado::select('Estado')
                               ->distinct()
                               ->whereNotNull('Estado')
                               ->where('Estado', '!=', '')
                               ->pluck('Estado')
                               ->sort()
                               ->values()
                               ->toArray();
            
            // Información del día actual para resaltar en la vista
            $fechaActual = Carbon::now();
            $diaActual = $fechaActual->day;
            $mesActual = $fechaActual->month;
            $añoActual = $fechaActual->year;
            $nombreDiaActual = $fechaActual->locale('es')->dayName;
            $semanaActual = $fechaActual->weekOfMonth;
            
            // Debug: Log de resultados
            \Log::info('AsistenciaController::index - Resultados:', [
                'empleados_count' => $empleados->count(),
                'asistencias_count' => count($asistencias),
                'estados_count' => count($estados),
                'fecha_actual' => [
                    'dia' => $diaActual,
                    'mes' => $mesActual,
                    'año' => $añoActual,
                    'nombre_dia' => $nombreDiaActual,
                    'semana' => $semanaActual
                ]
            ]);
            
            return view('asistencia.index', compact(
                'empleados',
                'asistencias',
                'año',
                'mes',
                'estado',
                'periodo',
                'diasDelPeriodo',
                'estados',
                'diaActual',
                'mesActual',
                'añoActual',
                'nombreDiaActual',
                'semanaActual'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error en AsistenciaController::index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error en controlador',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    /**
     * Obtener días del periodo especificado
     */
    private function obtenerDiasDelPeriodo($año, $mes, $periodo)
    {
        $diasEnMes = Carbon::createFromDate($año, $mes, 1)->daysInMonth;
        
        if ($periodo === 'primera_quincena') {
            return range(1, 15);
        } elseif ($periodo === 'segunda_quincena') {
            return range(16, $diasEnMes);
        }
        
        return range(1, $diasEnMes); // periodo completo por defecto
    }

    /**
     * Guardar asistencia individual
     */
    public function guardarAsistencia(Request $request)
    {
        try {
            $empleadoId = $request->input('empleado_id');
            $dia = $request->input('dia');
            $valor = $request->input('valor');
            $año = $request->input('año');
            $mes = $request->input('mes');
            
            // Obtener o crear el registro de asistencia
            $asistencia = Asistencia::firstOrCreate(
                [
                    'empleado_id' => $empleadoId,
                    'año' => $año,
                    'mes' => $mes
                ]
            );
            
            // Actualizar el día específico
            $campoAsistencia = "dia_{$dia}";
            $asistencia->$campoAsistencia = $valor;
            $asistencia->save();
            
            // Calcular totales actualizados
            $totales = $asistencia->calcularTotales();
            
            return response()->json([
                'success' => true,
                'message' => 'Asistencia guardada correctamente',
                'totales' => $totales
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al guardar asistencia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar asistencia individual
     */
    public function updateAsistencia(Request $request)
    {
        try {
            $empleadoId = $request->input('empleado_id');
            $año = $request->input('año');
            $mes = $request->input('mes');
            $campo = $request->input('campo');
            $valor = $request->input('valor');
            
            // Obtener o crear el registro de asistencia
            $asistencia = Asistencia::firstOrCreate(
                [
                    'empleado_id' => $empleadoId,
                    'año' => $año,
                    'mes' => $mes
                ]
            );
            
            // Actualizar el campo específico
            $asistencia->$campo = $valor;
            $asistencia->save();
            
            // Calcular totales actualizados
            $totales = $asistencia->calcularTotales();
            
            return response()->json([
                'success' => true,
                'message' => 'Asistencia actualizada correctamente',
                'totales' => $totales
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar asistencia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar campos financieros
     */
    public function updateFinanciero(Request $request)
    {
        try {
            $empleadoId = $request->input('empleado_id');
            $año = $request->input('año');
            $mes = $request->input('mes');
            
            // Obtener o crear el registro de asistencia
            $asistencia = Asistencia::firstOrCreate(
                [
                    'empleado_id' => $empleadoId,
                    'año' => $año,
                    'mes' => $mes
                ]
            );
            
            // Actualizar campos financieros según lo que venga en el request
            if ($request->has('bono')) {
                $asistencia->bono = $request->input('bono');
            }
            if ($request->has('prestamo')) {
                $asistencia->prestamo = $request->input('prestamo');
            }
            if ($request->has('fonacot')) {
                $asistencia->fonacot = $request->input('fonacot');
            }
            if ($request->has('estatus_finiquito')) {
                $asistencia->estatus_finiquito = $request->input('estatus_finiquito');
            }
            if ($request->has('observaciones')) {
                $asistencia->observaciones = $request->input('observaciones');
            }
            
            $asistencia->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Campos financieros actualizados correctamente'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar campos financieros: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar campos financieros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar campos adicionales
     */
    public function guardarCamposAdicionales(Request $request)
    {
        try {
            $empleadoId = $request->input('empleado_id');
            $año = $request->input('año');
            $mes = $request->input('mes');
            
            // Obtener o crear el registro de asistencia
            $asistencia = Asistencia::firstOrCreate(
                [
                    'empleado_id' => $empleadoId,
                    'año' => $año,
                    'mes' => $mes
                ]
            );
            
            // Actualizar campos según lo que venga en el request
            if ($request->has('bono')) {
                $asistencia->bono = $request->input('bono');
            }
            if ($request->has('prestamo')) {
                $asistencia->prestamo = $request->input('prestamo');
            }
            if ($request->has('fonacot')) {
                $asistencia->fonacot = $request->input('fonacot');
            }
            if ($request->has('estatus_finiquito')) {
                $asistencia->estatus_finiquito = $request->input('estatus_finiquito');
            }
            if ($request->has('observaciones')) {
                $asistencia->observaciones = $request->input('observaciones');
            }
            
            $asistencia->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Campos adicionales guardados correctamente'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al guardar campos adicionales: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar campos adicionales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aplicar filtros vía AJAX
     */
    public function aplicarFiltros(Request $request)
    {
        try {
            // Debug: Log de la petición AJAX
            \Log::info('AsistenciaController::aplicarFiltros - Petición AJAX recibida:', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'parameters' => $request->all()
            ]);
            
            $año = $request->get('año') ?: $request->get('ano'); // Permitir ambos parámetros
            $mes = $request->get('mes');
            $estado = $request->get('estado', 'todos');
            $periodo = $request->get('periodo');
            
            if (!$año || !$mes || !$periodo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetros incompletos'
                ], 400);
            }
            
            $diasDelPeriodo = $this->obtenerDiasDelPeriodo($año, $mes, $periodo);
            
            // Construir query de empleados con relaciones necesarias
            $query = Empleado::with(['puesto.cliente']);
            
            if ($estado !== 'todos') {
                $query->where('Estado', $estado);
            }
            
            $empleados = $query->orderBy('Apellido_Paterno')
                              ->orderBy('Apellido_Materno')
                              ->orderBy('Nombre')
                              ->get();
            
            // Obtener asistencias para estos empleados
            $asistencias = [];
            foreach ($empleados as $empleado) {
                $asistencia = Asistencia::where('empleado_id', $empleado->IdEmpleados)
                                       ->where('año', $año)
                                       ->where('mes', $mes)
                                       ->first();
                
                if ($asistencia) {
                    $asistencias[$empleado->IdEmpleados] = $asistencia;
                }
            }
            
            // Obtener estados para el dropdown
            $estados = Empleado::select('Estado')
                               ->distinct()
                               ->whereNotNull('Estado')
                               ->where('Estado', '!=', '')
                               ->pluck('Estado')
                               ->sort()
                               ->values()
                               ->toArray();
            
            // Información del día actual para la vista
            $fechaActual = Carbon::now();
            $diaActual = $fechaActual->day;
            $mesActual = $fechaActual->month;
            $añoActual = $fechaActual->year;
            $nombreDiaActual = $fechaActual->locale('es')->dayName;
            $semanaActual = $fechaActual->weekOfMonth;
            
            // Generar HTML de la tabla actualizada
            $tableHtml = view('asistencia.tabla', compact(
                'empleados', 
                'asistencias', 
                'año', 
                'mes', 
                'estado',
                'periodo',
                'diasDelPeriodo',
                'diaActual',
                'mesActual',
                'añoActual',
                'nombreDiaActual',
                'semanaActual'
            ))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Filtros aplicados correctamente',
                'empleados_count' => $empleados->count(),
                'tableHtml' => $tableHtml,
                'diasDelPeriodo' => $diasDelPeriodo,
                'estados' => $estados
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en aplicarFiltros: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aplicar filtros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar reportes
     */
    public function exportar(Request $request)
    {
        try {
            $formato = $request->get('formato');
            $año = $request->get('año') ?: $request->get('ano'); // Permitir ambos parámetros
            $mes = $request->get('mes');
            $estado = $request->get('estado', 'todos');
            $periodo = $request->get('periodo');
            
            if (!$formato || !$año || !$mes || !$periodo) {
                return back()->with('error', 'Parámetros de exportación incompletos');
            }
            
            $diasDelPeriodo = $this->obtenerDiasDelPeriodo($año, $mes, $periodo);
            
            // Obtener empleados con el mismo filtro que la vista
            $query = Empleado::with(['puesto.cliente'])
                ->whereHas('puesto', function($q) {
                    $q->whereNotNull('id_ClientePuestoFK');
                });
            
            if ($estado !== 'todos') {
                $query->where('Estado', $estado);
            }
            
            $empleados = $query->orderBy('Apellido_Paterno')
                              ->orderBy('Apellido_Materno')
                              ->orderBy('Nombre')
                              ->get();
            
            // Obtener asistencias
            $asistencias = [];
            foreach ($empleados as $empleado) {
                $asistencia = Asistencia::where('empleado_id', $empleado->IdEmpleados)
                                       ->where('año', $año)
                                       ->where('mes', $mes)
                                       ->first();
                
                if ($asistencia) {
                    $asistencias[$empleado->IdEmpleados] = $asistencia;
                }
            }
            
            if ($formato === 'excel') {
                return $this->exportarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo);
            } elseif ($formato === 'pdf') {
                return $this->exportarPDF($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo);
            }
            
            return back()->with('error', 'Formato de exportación no válido');
            
        } catch (\Exception $e) {
            \Log::error('Error en exportación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar a Excel con gráficas de barras y colores reales
     */
    private function exportarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo)
    {
        // Fallback: usar CSV simple si ExcelAsistenciaHelper falla
        try {
            if (class_exists('ExcelAsistenciaHelper')) {
                return ExcelAsistenciaHelper::generarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo);
            }
        } catch (\Exception $e) {
            \Log::error('Error con ExcelAsistenciaHelper: ' . $e->getMessage());
        }
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
            
            // Agregar BOM para UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Encabezado principal
            fputcsv($file, ["REPORTE DE ASISTENCIA - " . strtoupper($mesesEspanol[$mes]) . " $año"]);
            fputcsv($file, ["Periodo: " . ($periodo === 'primera_quincena' ? 'Primera Quincena (1-15)' : 'Segunda Quincena (16-' . max($diasDelPeriodo) . ')')]);
            fputcsv($file, ["Generado: " . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, ["Total Empleados: " . count($empleados)]);
            fputcsv($file, []);
            
            // Leyenda de estados con colores
            fputcsv($file, ["LEYENDA DE ESTADOS:"]);
            foreach (\App\Models\Asistencia::ESTADOS as $key => $estado_info) {
                fputcsv($file, [
                    $estado_info['icono'] . " " . $estado_info['nombre'],
                    "Color: " . $estado_info['color']
                ]);
            }
            fputcsv($file, []);
            
            // Encabezados de datos
            $header = ['EMPLEADO', 'CLIENTE', 'ESTADO/ZONA', 'PUESTO'];
            foreach ($diasDelPeriodo as $dia) {
                $header[] = "Dia {$dia}";
            }
            $header = array_merge($header, ['PRESENTE', 'FALTAS', 'OTROS', 'BONO', 'PRESTAMO', 'FONACOT', 'FINIQUITO', 'OBSERVACIONES']);
            fputcsv($file, $header);
            
            // Datos de empleados
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                $totales = $asistencia ? $asistencia->calcularTotales() : [];
                
                $row = [
                    $empleado->Nombre . ' ' . $empleado->Apellido_Paterno . ' ' . ($empleado->Apellido_Materno ?? ''),
                    $empleado->puesto->cliente->Nombre ?? 'Sin cliente',
                    $empleado->Estado ?? 'Sin especificar',
                    $empleado->puesto->Puesto ?? 'Sin puesto'
                ];
                
                // Asistencias por día con iconos y nombres
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
                $row[] = isset($totales['presente']) ? $totales['presente'] : 0;
                $row[] = isset($totales['falta']) ? $totales['falta'] : 0;
                
                // Otros estados consolidados
                $otrosTotal = 0;
                $estadosOtros = ['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'];
                foreach ($estadosOtros as $estado) {
                    $otrosTotal += isset($totales[$estado]) ? $totales[$estado] : 0;
                }
                $row[] = $otrosTotal;
                
                // Campos monetarios
                $row[] = $asistencia && $asistencia->bono ? '$' . number_format($asistencia->bono, 2) : '';
                $row[] = $asistencia && $asistencia->prestamo ? '$' . number_format($asistencia->prestamo, 2) : '';
                $row[] = $asistencia && $asistencia->fonacot ? '$' . number_format($asistencia->fonacot, 2) : '';
                $row[] = $asistencia && $asistencia->estatus_finiquito ? '$' . number_format($asistencia->estatus_finiquito, 2) : '';
                $row[] = $asistencia ? ($asistencia->observaciones ?: '') : '';
                
                fputcsv($file, $row);
            }
            
            // Resumen estadístico
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
        if (!class_exists('\Dompdf\Dompdf')) {
            return response($html, 200)
                ->header('Content-Type', 'text/html; charset=utf-8');
        }
        
        try {
            $dompdf = new Dompdf();
            $dompdf->getOptions()->setChroot(public_path());
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            
            $filename = "reporte_asistencia_{$periodo}_{$mes}_{$año}.pdf";
            
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                
        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage());
            return response($html, 200)
                ->header('Content-Type', 'text/html; charset=utf-8');
        }
    }
}