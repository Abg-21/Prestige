<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Clase helper para generar reportes Excel con gráficas y colores
 */
class ExcelAsistenciaHelper
{
    /**
     * Generar Excel con gráficas de barras y colores reales
     */
    public static function generarExcel($empleados, $asistencias, $año, $mes, $periodo, $diasDelPeriodo)
    {
        try {
            $mesesEspanol = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("Asistencia {$mesesEspanol[$mes]} {$año}");
            
            // Configurar encabezado principal
            $sheet->setCellValue('A1', "REPORTE DE ASISTENCIA - " . strtoupper($mesesEspanol[$mes]) . " $año");
            $sheet->mergeCells('A1:Z1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $sheet->setCellValue('A2', "Periodo: " . ($periodo === 'primera_quincena' ? 'Primera Quincena (1-15)' : 'Segunda Quincena (16-' . max($diasDelPeriodo) . ')'));
            $sheet->setCellValue('A3', "Generado: " . now()->format('d/m/Y H:i:s'));
            $sheet->setCellValue('A4', "Total Empleados: " . count($empleados));
            
            // Leyenda de estados con colores reales
            $currentRow = 6;
            $sheet->setCellValue('A' . $currentRow, "LEYENDA DE ESTADOS:");
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            
            foreach (\App\Models\Asistencia::ESTADOS as $key => $estado_info) {
                $sheet->setCellValue('A' . $currentRow, $estado_info['icono'] . " " . $estado_info['nombre']);
                $sheet->setCellValue('B' . $currentRow, $estado_info['color']);
                
                // Aplicar el color real como fondo de celda
                $sheet->getStyle('A' . $currentRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(ltrim($estado_info['color'], '#'));
                
                // Determinar color de texto basado en el fondo
                $textColor = in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '000000' : 'FFFFFF';
                $sheet->getStyle('A' . $currentRow)->getFont()->getColor()->setRGB($textColor);
                
                $currentRow++;
            }
            
            // Encabezados de datos
            $currentRow += 2;
            $dataStartRow = $currentRow;
            $col = 1;
            
            $headers = ['EMPLEADO', 'CLIENTE', 'ESTADO/ZONA', 'PUESTO'];
            foreach ($diasDelPeriodo as $dia) {
                $headers[] = "Dia {$dia}";
            }
            $headers = array_merge($headers, ['PRESENTE', 'FALTAS', 'OTROS', 'BONO', 'PRESTAMO', 'FONACOT', 'FINIQUITO']);
            
            foreach ($headers as $header) {
                $sheet->setCellValueByColumnAndRow($col, $currentRow, $header);
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->setBold(true);
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('447D9B');
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->getColor()->setRGB('FFFFFF');
                $col++;
            }
            
            // Datos de empleados con colores
            $currentRow++;
            foreach ($empleados as $empleado) {
                $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                $totales = $asistencia ? $asistencia->calcularTotales() : [];
                
                $col = 1;
                
                // Información básica
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $empleado->Nombre . ' ' . $empleado->Apellido_Paterno . ' ' . ($empleado->Apellido_Materno ?? ''));
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $empleado->puesto->cliente->Nombre ?? 'Sin cliente');
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $empleado->Estado ?? 'Sin especificar');
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $empleado->puesto->Puesto ?? 'Sin puesto');
                
                // Asistencias por día con colores
                foreach ($diasDelPeriodo as $dia) {
                    $valor = $asistencia ? $asistencia->getAsistenciaDia($dia) : '';
                    if ($valor && isset(\App\Models\Asistencia::ESTADOS[$valor])) {
                        $estado_info = \App\Models\Asistencia::ESTADOS[$valor];
                        $sheet->setCellValueByColumnAndRow($col, $currentRow, $estado_info['icono']);
                        
                        // Aplicar color de fondo
                        $sheet->getStyleByColumnAndRow($col, $currentRow)->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB(ltrim($estado_info['color'], '#'));
                        
                        // Color de texto
                        $textColor = in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '000000' : 'FFFFFF';
                        $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->getColor()->setRGB($textColor);
                        $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->setBold(true);
                    } else {
                        $sheet->setCellValueByColumnAndRow($col, $currentRow, '-');
                    }
                    $col++;
                }
                
                // Totales con colores
                $presenteCol = $col;
                $sheet->setCellValueByColumnAndRow($col, $currentRow, isset($totales['presente']) ? $totales['presente'] : 0);
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('28A745');
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->getColor()->setRGB('FFFFFF')->setBold(true);
                $col++;
                
                $faltaCol = $col;
                $sheet->setCellValueByColumnAndRow($col, $currentRow, isset($totales['falta']) ? $totales['falta'] : 0);
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DC3545');
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->getColor()->setRGB('FFFFFF')->setBold(true);
                $col++;
                
                // Otros estados consolidados
                $otrosTotal = 0;
                $estadosOtros = ['prima_dominical', 'bajas', 'alta', 'incapacidad', 'prestamo', 'vacante', 'vacaciones'];
                foreach ($estadosOtros as $estado) {
                    $otrosTotal += isset($totales[$estado]) ? $totales[$estado] : 0;
                }
                $sheet->setCellValueByColumnAndRow($col, $currentRow, $otrosTotal);
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFC107');
                $sheet->getStyleByColumnAndRow($col, $currentRow)->getFont()->getColor()->setRGB('000000')->setBold(true);
                $col++;
                
                // Campos monetarios
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $asistencia && $asistencia->bono ? $asistencia->bono : '');
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $asistencia && $asistencia->prestamo ? $asistencia->prestamo : '');
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $asistencia && $asistencia->fonacot ? $asistencia->fonacot : '');
                $sheet->setCellValueByColumnAndRow($col++, $currentRow, $asistencia && $asistencia->estatus_finiquito ? $asistencia->estatus_finiquito : '');
                
                $currentRow++;
            }
            
            // Crear gráfica de barras para totales
            $currentRow += 3;
            $chartStartRow = $currentRow;
            $sheet->setCellValue('A' . $currentRow, "GRÁFICA DE TOTALES GENERALES");
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(14);
            $currentRow++;
            
            // Calcular totales generales
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
            
            // Configurar datos de la gráfica
            $sheet->setCellValue('A' . $currentRow, 'Categoría');
            $sheet->setCellValue('B' . $currentRow, 'Total Días');
            $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'Presentes');
            $sheet->setCellValue('B' . $currentRow, $totalPresentes);
            $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('28A745');
            $sheet->getStyle('A' . $currentRow)->getFont()->getColor()->setRGB('FFFFFF')->setBold(true);
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'Faltas');
            $sheet->setCellValue('B' . $currentRow, $totalFaltas);
            $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DC3545');
            $sheet->getStyle('A' . $currentRow)->getFont()->getColor()->setRGB('FFFFFF')->setBold(true);
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
            
            $sheet->setCellValue('A' . $currentRow, 'Otros eventos');
            $sheet->setCellValue('B' . $currentRow, $totalOtros);
            $sheet->getStyle('A' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFC107');
            $sheet->getStyle('A' . $currentRow)->getFont()->getColor()->setRGB('000000')->setBold(true);
            $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
            
            // Crear gráfica de barras
            $dataSeriesLabels = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . ($chartStartRow + 1), null, 1),
            ];
            
            $xAxisTickValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$' . ($chartStartRow + 2) . ':$A$' . $currentRow, null, 3),
            ];
            
            $dataSeriesValues = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$' . ($chartStartRow + 2) . ':$B$' . $currentRow, null, 3),
            ];
            
            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, count($dataSeriesValues) - 1),
                $dataSeriesLabels,
                $xAxisTickValues,
                $dataSeriesValues
            );
            
            $series->setPlotDirection(DataSeries::DIRECTION_COL);
            
            $plotArea = new PlotArea(null, [$series]);
            $legend = new Legend(Legend::POSITION_RIGHT, null, false);
            $title = new Title('Distribución de Asistencia');
            $yAxisLabel = new Title('Número de Días');
            
            $chart = new Chart(
                'chart1',
                $title,
                $legend,
                $plotArea,
                true,
                0,
                null,
                $yAxisLabel
            );
            
            $chart->setTopLeftPosition('D' . ($chartStartRow + 1));
            $chart->setBottomRightPosition('J' . ($currentRow + 10));
            
            $sheet->addChart($chart);
            
            // Ajustar ancho de columnas
            foreach (range('A', 'Z') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Crear archivo Excel
            $filename = "reporte_asistencia_con_graficas_{$periodo}_{$mes}_{$año}.xlsx";
            
            $writer = new Xlsx($spreadsheet);
            
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en generarExcel: ' . $e->getMessage());
            throw new \Exception('Error al generar archivo Excel: ' . $e->getMessage());
        }
    }
}