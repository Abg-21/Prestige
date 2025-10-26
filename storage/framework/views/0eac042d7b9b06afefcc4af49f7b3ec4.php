<div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead style="background: #447D9B; color: white; position: sticky; top: 0;">
            <tr>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Empleado</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 150px;">Cliente</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 120px;">Estado</th>
                
                
                <?php $__currentLoopData = $diasDelPeriodo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="padding: 5px; border: 1px solid #ddd; min-width: 40px; text-align: center;">
                        <?php echo e($dia); ?>

                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 100px;">Totales</th>
                
                
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Bono</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Finiquito</th>
                <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                    $totales = $asistencia ? $asistencia->calcularTotales() : [];
                ?>
                <tr style="border-bottom: 1px solid #eee;">
                    
                    <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">
                        <?php echo e($empleado->Nombre); ?> <?php echo e($empleado->Apellido_Paterno); ?> <?php echo e($empleado->Apellido_Materno); ?>

                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <?php if($empleado->puesto && $empleado->puesto->cliente): ?>
                            <?php echo e($empleado->puesto->cliente->Nombre); ?>

                        <?php else: ?>
                            <span style="color: #dc3545;">Sin cliente asignado</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <?php echo e($empleado->Estado ?? 'Sin estado'); ?>

                    </td>
                    
                    
                    <?php $__currentLoopData = $diasDelPeriodo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $campo = "dia_{$dia}";
                            $valor = $asistencia ? $asistencia->$campo : null;
                            $estado_info = \App\Models\Asistencia::ESTADOS[$valor] ?? \App\Models\Asistencia::ESTADOS[''];
                        ?>
                        <td class="casilla-asistencia" 
                            style="padding: 4px; border: 1px solid #ddd; text-align: center; background-color: #f8f9fa; cursor: pointer; min-width: 35px;"
                            data-empleado-id="<?php echo e($empleado->IdEmpleados); ?>"
                            data-dia="<?php echo e($dia); ?>"
                            data-valor="<?php echo e($valor); ?>"
                            title="Click para cambiar estado - Día <?php echo e($dia); ?>">
                            
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    <td style="padding: 8px; border: 1px solid #ddd; font-size: 10px;">
                        <?php if($totales): ?>
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                <?php $__currentLoopData = $totales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($total > 0): ?>
                                        <?php $info = \App\Models\Asistencia::ESTADOS[$estado] ?? ['icono' => $estado, 'color' => '#ccc']; ?>
                                        <span style="background: <?php echo e($info['color']); ?>; color: white; padding: 1px 4px; border-radius: 2px; font-size: 9px;">
                                            <?php echo e($info['icono']); ?>: <?php echo e($total); ?>

                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <span style="color: #999;">Sin datos</span>
                        <?php endif; ?>
                    </td>
                    
                    
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="<?php echo e($asistencia->id ?? ''); ?>"
                               data-campo="bono"
                               value="<?php echo e($asistencia->bono ?? ''); ?>" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="<?php echo e($asistencia->id ?? ''); ?>"
                               data-campo="prestamo"
                               value="<?php echo e($asistencia->prestamo ?? ''); ?>" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="<?php echo e($asistencia->id ?? ''); ?>"
                               data-campo="fonacot"
                               value="<?php echo e($asistencia->fonacot ?? ''); ?>" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="<?php echo e($asistencia->id ?? ''); ?>"
                               data-campo="finiquito"
                               value="<?php echo e($asistencia->finiquito ?? ''); ?>" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <textarea class="financiero-input" 
                                  data-asistencia-id="<?php echo e($asistencia->id ?? ''); ?>"
                                  data-campo="observaciones"
                                  style="width: 180px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; resize: vertical; min-height: 30px;"
                                  placeholder="Observaciones..."><?php echo e($asistencia->observaciones ?? ''); ?></textarea>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(count($diasDelPeriodo) + 8); ?>" style="padding: 20px; text-align: center; color: #666;">
                        No se encontraron empleados<?php echo e($estado !== 'todos' ? ' para el estado: ' . $estado : ''); ?>

                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/asistencia/tabla.blade.php ENDPATH**/ ?>