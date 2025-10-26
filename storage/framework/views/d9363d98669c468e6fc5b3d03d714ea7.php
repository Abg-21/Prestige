<?php if($eliminados->count() > 0): ?>
    <table style="width:100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #FE7743; color: #fff;">
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Tipo</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Nombre/Identificador</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Motivo</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Eliminado Por</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: left;">Fecha</th>
                <th style="padding: 12px 8px; border: 1px solid #ddd; text-align: center; width: 120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $eliminados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eliminado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="registro-eliminado" data-tipo="<?php echo e($eliminado->tipo); ?>" style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        <?php
                            $tipoDisplay = $eliminado->tipo;
                            if ($eliminado->eliminable_type) {
                                $tipoDisplay = class_basename($eliminado->eliminable_type);
                            } elseif ($eliminado->tipo === 'desactivacion') {
                                $tipoDisplay = 'Empleado';
                            }
                        ?>
                        <span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            <?php echo e($tipoDisplay); ?>

                        </span>
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        <?php if($eliminado->eliminable): ?>
                            <?php
                                $nombre = 'N/A';
                                if ($eliminado->eliminable_type === 'App\Models\Candidato' || $eliminado->tipo === 'desactivacion') {
                                    $nombre = ($eliminado->eliminable->Nombre ?? 'N/A') . ' ' . ($eliminado->eliminable->Apellido_Paterno ?? '');
                                } elseif ($eliminado->eliminable_type === 'App\Models\Empleado') {
                                    $nombre = ($eliminado->eliminable->Nombre ?? 'N/A') . ' ' . ($eliminado->eliminable->Apellido_Paterno ?? '');
                                } elseif ($eliminado->eliminable_type === 'App\Models\Usuario') {
                                    $nombre = $eliminado->eliminable->nombre_usuario ?? $eliminado->eliminable->nombre ?? 'N/A';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Cliente') {
                                    $nombre = $eliminado->eliminable->Empresa ?? 'N/A';
                                } else {
                                    $nombre = $eliminado->eliminable->nombre ?? $eliminado->eliminable->Nombre ?? 'N/A';
                                }
                            ?>
                            <?php echo e(trim($nombre)); ?>

                        <?php else: ?>
                            <span style="color: #6c757d; font-style: italic;">Registro no encontrado</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        <?php if($eliminado->motivo): ?>
                            <span style="color: #6c757d;"><?php echo e(Str::limit($eliminado->motivo, 35)); ?></span>
                        <?php else: ?>
                            <span style="color: #6c757d; font-style: italic;">Sin motivo</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd;">
                        <?php if($eliminado->usuario): ?>
                            <?php echo e($eliminado->usuario->nombre_usuario ?? $eliminado->usuario->nombre); ?>

                        <?php else: ?>
                            <span style="color: #6c757d; font-style: italic;">Sistema</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px 8px; border: 1px solid #ddd; font-size: 13px; color: #6c757d;">
                        <?php echo e($eliminado->eliminado_en ? $eliminado->eliminado_en->format('d/m/Y H:i') : $eliminado->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        <?php
                            $tipoParam = $eliminado->eliminable_type ? class_basename($eliminado->eliminable_type) : ($eliminado->tipo === 'desactivacion' ? 'Empleado' : $eliminado->tipo);
                            $nombreParam = '';
                            if ($eliminado->eliminable) {
                                if ($eliminado->eliminable_type === 'App\Models\Empleado' || $eliminado->tipo === 'desactivacion') {
                                    $nombreParam = $eliminado->eliminable->Nombre ?? 'este registro';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Usuario') {
                                    $nombreParam = $eliminado->eliminable->nombre_usuario ?? $eliminado->eliminable->nombre ?? 'este registro';
                                } elseif ($eliminado->eliminable_type === 'App\Models\Cliente') {
                                    $nombreParam = $eliminado->eliminable->Empresa ?? 'este registro';
                                } else {
                                    $nombreParam = $eliminado->eliminable->Nombre ?? $eliminado->eliminable->nombre ?? 'este registro';
                                }
                            }
                        ?>
                        
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <button type="button" 
                                    onclick="verDetalle('<?php echo e($tipoParam); ?>', '<?php echo e($eliminado->eliminable_id); ?>')"
                                    style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;"
                                    title="Ver detalles">
                                <img src="<?php echo e(asset('images/ver.png')); ?>" alt="Ver" style="width: 28px; height: 28px;">
                                <div style="font-size: 11px; color: #555;">Ver</div>
                            </button>
                            
                            <?php if($eliminado->eliminable): ?>
                                <button type="button" 
                                        onclick="confirmarRestauracion('<?php echo e($tipoParam); ?>', '<?php echo e($eliminado->eliminable_id); ?>', '<?php echo e(addslashes($nombreParam)); ?>')"
                                        style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;"
                                        title="Restaurar registro">
                                    <img src="<?php echo e(asset('images/restaurar.png')); ?>" alt="Restaurar" style="width: 28px; height: 28px;">
                                    <div style="font-size: 11px; color: #4CAF50;">Restaurar</div>
                                </button>
                            <?php else: ?>
                                <button type="button" 
                                        style="background: none; border: none; padding: 5px; cursor: not-allowed; display: flex; flex-direction: column; align-items: center; opacity: 0.5;"
                                        disabled
                                        title="Registro no disponible">
                                    <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="No disponible" style="width: 28px; height: 28px;">
                                    <div style="font-size: 11px; color: #6c757d;">N/A</div>
                                </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    </table>
    
    <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px; border-left: 4px solid #FE7743;">
        <small style="color: #6c757d;">
            <strong>Total de registros eliminados:</strong> <?php echo e($eliminados->count()); ?>

        </small>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
        <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="Sin registros" style="width: 64px; height: 64px; opacity: 0.5; margin-bottom: 20px;">
        <h4 style="color: #6c757d; margin-bottom: 10px;">No hay registros eliminados</h4>
        <p style="color: #6c757d; margin: 0;">Los registros eliminados aparecerán aquí cuando se desactiven.</p>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/Eliminados/tabla.blade.php ENDPATH**/ ?>