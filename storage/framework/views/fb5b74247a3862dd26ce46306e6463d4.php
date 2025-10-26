<div style="padding: 20px;">
    <h2 style="margin-bottom: 20px; color: rgb(0, 0, 0);">Crear Nuevo Rol</h2>
    
    <form id="form-rol" method="POST" action="<?php echo e(route('roles.store')); ?>">
        <?php echo csrf_field(); ?>
        
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <!-- Información básica del rol -->
            <div style="margin-bottom: 20px;">
                <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">Nombre del Rol:</label>
                <input type="text" id="nombre" name="nombre" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                       value="<?php echo e(old('nombre')); ?>">
            </div>
            
            <div style="margin-bottom: 30px;">
                <label for="descripcion" style="display: block; margin-bottom: 5px; font-weight: bold;">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="2"
                          style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e(old('descripcion')); ?></textarea>
            </div>

            <!-- Matriz de permisos -->
            <h3 style="margin-bottom: 15px; color: #447D9B;">Permisos por Módulo</h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <thead>
                        <tr style="background: #447D9B; color: #fff;">
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: left; min-width: 150px;">Módulo</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Ver</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Crear</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Editar</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Eliminar</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Todos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduloKey => $moduloNombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr style="background: <?php echo e($loop->even ? '#f8f9fa' : '#fff'); ?>;">
                                <td style="padding: 12px; border: 1px solid #ddd; font-weight: bold;">
                                    <?php echo e($moduloNombre); ?>

                                </td>
                                <?php $__currentLoopData = $acciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accionKey => $accionNombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                                        <input type="checkbox" 
                                               name="permisos[<?php echo e($moduloKey); ?>][<?php echo e($accionKey); ?>]" 
                                               value="1"
                                               id="permiso_<?php echo e($moduloKey); ?>_<?php echo e($accionKey); ?>"
                                               class="permiso-checkbox"
                                               data-modulo="<?php echo e($moduloKey); ?>"
                                               data-accion="<?php echo e($accionKey); ?>"
                                               style="transform: scale(1.2);">
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                                    <input type="checkbox" 
                                           class="toggle-all-modulo"
                                           data-modulo="<?php echo e($moduloKey); ?>"
                                           style="transform: scale(1.2);"
                                           title="Seleccionar/Deseleccionar todos los permisos de este módulo">
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción rápida -->
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <h4 style="margin-bottom: 10px; color: #495057;">Acciones Rápidas:</h4>
                <button type="button" id="seleccionar-todo" style="margin-right: 10px; padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Seleccionar Todo
                </button>
                <button type="button" id="deseleccionar-todo" style="margin-right: 10px; padding: 8px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Deseleccionar Todo
                </button>
                <button type="button" id="solo-ver" style="padding: 8px 15px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Solo Permisos de Ver
                </button>
            </div>

            <!-- Botones del formulario -->
            <div style="margin-top: 30px; text-align: right;">
                <button type="button" onclick="history.back()" style="margin-right: 10px; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit" style="padding: 10px 20px; background: #C57F1B; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Crear Rol
                </button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Toggle all permisos para un módulo específico
    $('.toggle-all-modulo').on('change', function() {
        var modulo = $(this).data('modulo');
        var isChecked = $(this).is(':checked');
        
        $('input[data-modulo="' + modulo + '"]').not(this).prop('checked', isChecked);
    });

    // Actualizar el checkbox "Todos" cuando se cambian permisos individuales
    $('.permiso-checkbox').on('change', function() {
        var modulo = $(this).data('modulo');
        var totalCheckboxes = $('input[data-modulo="' + modulo + '"]').not('.toggle-all-modulo').length;
        var checkedCheckboxes = $('input[data-modulo="' + modulo + '"]').not('.toggle-all-modulo').filter(':checked').length;
        
        var toggleAll = $('.toggle-all-modulo[data-modulo="' + modulo + '"]');
        
        if (checkedCheckboxes === 0) {
            toggleAll.prop('indeterminate', false);
            toggleAll.prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            toggleAll.prop('indeterminate', false);
            toggleAll.prop('checked', true);
        } else {
            toggleAll.prop('indeterminate', true);
        }
    });

    // Seleccionar todo
    $('#seleccionar-todo').on('click', function() {
        $('.permiso-checkbox').prop('checked', true);
        $('.toggle-all-modulo').prop('checked', true).prop('indeterminate', false);
    });

    // Deseleccionar todo
    $('#deseleccionar-todo').on('click', function() {
        $('.permiso-checkbox').prop('checked', false);
        $('.toggle-all-modulo').prop('checked', false).prop('indeterminate', false);
    });

    // Solo permisos de ver
    $('#solo-ver').on('click', function() {
        $('.permiso-checkbox').prop('checked', false);
        $('input[data-accion="ver"]').prop('checked', true);
        
        // Actualizar los toggles
        $('.toggle-all-modulo').each(function() {
            var modulo = $(this).data('modulo');
            var totalCheckboxes = $('input[data-modulo="' + modulo + '"]').not('.toggle-all-modulo').length;
            var checkedCheckboxes = $('input[data-modulo="' + modulo + '"]').not('.toggle-all-modulo').filter(':checked').length;
            
            if (checkedCheckboxes === 0) {
                $(this).prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $(this).prop('indeterminate', false).prop('checked', true);
            } else {
                $(this).prop('indeterminate', true);
            }
        });
    });
});
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/roles/create_rol.blade.php ENDPATH**/ ?>