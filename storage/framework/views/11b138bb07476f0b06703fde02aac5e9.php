<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencia - <?php echo e($mesTexto); ?> <?php echo e($año); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .header {
            text-align: center;
            background: #ffffff;
            color: #000000;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            border: 3px solid #000000;
        }
        
        .header h1 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
            text-shadow: none;
            color: #000000 !important;
            letter-spacing: 2px;
        }
        
        .header .periodo {
            font-size: 22px;
            margin-top: 15px;
            font-weight: bold;
            color: #000000 !important;
            text-shadow: none;
        }
        
        .empleado-section {
            background: white;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .empleado-header {
            background: #ffffff;
            color: #000000;
            padding: 25px;
            font-size: 24px;
            font-weight: bold;
            text-shadow: none;
            border: 2px solid #000000;
        }
        
        .empleado-info {
            padding: 20px;
            background: #ffffff;
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            border-left: 5px solid #000000;
            border-right: 5px solid #000000;
        }
        
        .grafica-container {
            padding: 30px;
            background: #ffffff;
            border: 3px solid #2c3e50;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .grafica-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #000000;
            border-bottom: 3px solid #000000;
            padding-bottom: 10px;
        }
        
        .barras-container {
            display: block;
            text-align: center;
            height: 350px;
            margin: 30px 0;
            padding: 20px 20px 120px 20px;
            background: #ffffff;
            border: 2px solid #000000;
            border-radius: 8px;
            position: relative;
        }
        
        .barra {
            display: inline-block;
            position: relative;
            width: 60px;
            margin: 0 15px;
            vertical-align: bottom;
            border-radius: 4px 4px 0 0;
            border: 2px solid #000000;
        }
        
        .barra-label {
            position: absolute;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #000000;
            background: #ffffff;
            padding: 5px;
            border: 1px solid #000000;
            border-radius: 3px;
            line-height: 1.2;
        }
        
        .barra-valor {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 16px;
            font-weight: bold;
            color: #000000;
            background: #ffffff;
            padding: 3px 8px;
            border: 1px solid #000000;
            border-radius: 3px;
            min-width: 25px;
            text-align: center;
        }
            background: white;
            padding: 2px 8px;
            border-radius: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .resumen-table th {
            background: #ffffff;
            color: #000000;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            border: 2px solid #000000;
        }
        
        .resumen-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #000000;
            color: #000000;
        }
        
        .resumen-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .resumen-table tr:hover {
            background: #e3f2fd;
        }
        
        .estado-color {
            width: 20px;
            height: 20px;
            border-radius: 3px; /* Menos redondeado para PDF */
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            border: 2px solid #000; /* Borde negro para definición */
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: #ffffff;
            color: #000000;
            border: 2px solid #000000;
            border-radius: 10px;
            font-size: 14px;
        }
        
        .no-data {
            text-align: center;
            color: #000000;
            font-style: italic;
            padding: 40px;
            background: #ffffff;
            border: 2px solid #000000;
            border-radius: 10px;
            margin: 20px 0;
        }

        @media print {
            body { background: white; }
            .empleado-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Asistencia</h1>
        <div class="periodo"><?php echo e($mesTexto); ?> <?php echo e($año); ?> - <?php echo e($periodoTexto); ?></div>
        <div style="font-size: 14px; margin-top: 5px; color: #000000;">Generado el <?php echo e(now()->format('d/m/Y H:i:s')); ?></div>
    </div>

    <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
            $totales = $asistencia ? $asistencia->calcularTotales() : [];
            $maxTotal = $totales ? max($totales) : 1;
        ?>
        
        <div class="empleado-section">
            <div class="empleado-header">
                <?php echo e($empleado->Nombre); ?> <?php echo e($empleado->Apellido_Paterno); ?> <?php echo e($empleado->Apellido_Materno); ?>

            </div>
            
            <div class="empleado-info">
                <strong>Cliente:</strong> <?php echo e($empleado->puesto->cliente->Nombre ?? 'Sin asignar'); ?> &nbsp;|&nbsp;
                <strong>Puesto:</strong> <?php echo e($empleado->puesto->Puesto ?? 'Sin asignar'); ?> &nbsp;|&nbsp;
                <strong>Estado/Zona:</strong> <?php echo e($empleado->Estado ?? 'Sin especificar'); ?>

            </div>
            
            <?php if($asistencia && $totales): ?>
                <div class="grafica-container">
                    <div class="grafica-title">Estadisticas de Asistencia</div>
                    
                    <div class="barras-container">
                        <?php $__currentLoopData = \App\Models\Asistencia::ESTADOS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $estado_info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($totales[$key]) && $totales[$key] > 0): ?>
                                <?php
                                    $altura = max(30, ($totales[$key] / $maxTotal) * 200); // Altura mínima 30px, máxima 200px
                                ?>
                                <div class="barra" style="height: <?php echo e($altura); ?>px; background-color: <?php echo e($estado_info['color']); ?>;">
                                    <div class="barra-valor"><?php echo e($totales[$key]); ?></div>
                                    <div class="barra-label">
                                        <strong><?php echo e($estado_info['icono']); ?></strong><br>
                                        <?php echo e($estado_info['nombre']); ?>

                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    
                    <table class="resumen-table">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Días</th>
                                <th>Porcentaje</th>
                                <th>Detalles por Día</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = \App\Models\Asistencia::ESTADOS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $estado_info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($totales[$key]) && $totales[$key] > 0): ?>
                                    <tr>
                                        <td>
                                            <span class="estado-color" style="background-color: <?php echo e($estado_info['color']); ?> !important; border: 2px solid #000;"></span>
                                            <?php echo e($estado_info['icono']); ?> <?php echo e($estado_info['nombre']); ?>

                                        </td>
                                        <td><strong><?php echo e($totales[$key]); ?></strong></td>
                                        <td><?php echo e(round(($totales[$key] / count($diasDelPeriodo)) * 100, 1)); ?>%</td>
                                        <td>
                                            <?php
                                                $diasConEstado = [];
                                                foreach($diasDelPeriodo as $dia) {
                                                    if($asistencia->getAsistenciaDia($dia) === $key) {
                                                        $diasConEstado[] = $dia;
                                                    }
                                                }
                                            ?>
                                            <?php echo e(implode(', ', $diasConEstado)); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    
                    <?php if($asistencia->observaciones): ?>
                        <div style="margin-top: 20px; padding: 15px; background: #ffffff; border-left: 4px solid #000000; border-radius: 5px; color: #000000;">
                            <strong>Observaciones:</strong> <?php echo e($asistencia->observaciones); ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="no-data">
                    No hay datos de asistencia registrados para este empleado en el periodo seleccionado.
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="footer">
        <div>
            <strong>Sistema de Gestión Prestige</strong><br>
            Reporte generado automáticamente el <?php echo e(now()->format('d/m/Y H:i:s')); ?>

        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/reportes/asistencia-pdf.blade.php ENDPATH**/ ?>