<div style="padding: 20px;">
    <h2 style="margin-bottom: 20px; color: rgb(0, 0, 0);">Editar Rol: <?php echo e($rol->nombre); ?></h2>
    
    <form id="form-rol" method="POST" action="<?php echo e(route('roles.update', $rol->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <!-- Información básica del rol -->
            <div style="margin-bottom: 20px;">
                <label for="nombre" style="display: block; margin-bottom: 5px; font-weight: bold;">Nombre del Rol:</label>
                <input type="text" id="nombre" name="nombre" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                       value="<?php echo e(old('nombre', $rol->nombre)); ?>">
            </div>
            
            <div style="margin-bottom: 30px;">
                <label for="descripcion" style="display: block; margin-bottom: 5px; font-weight: bold;">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="2"
                          style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"><?php echo e(old('descripcion', $rol->descripcion)); ?></textarea>
            </div>

            <!-- Matriz de permisos -->
            <h3 style="margin-bottom: 15px; color: #447D9B;">Permisos por Módulo</h3>
            
            <?php
                $permisosActuales = $rol->permisos ?? [];
            ?>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <thead>
                        <tr style="background: #447D9B; color: #fff;">
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: left; min-width: 150px;">Módulo</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Ver</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Crear</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Editar</th>
                            <th style="padding: 12px; border: 1px solid #ddd; text-align: center; width: 100px;">Eliminar</th>
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
                                               style="transform: scale(1.2);"
                                               <?php echo e(isset($permisosActuales[$moduloKey][$accionKey]) && $permisosActuales[$moduloKey][$accionKey] ? 'checked' : ''); ?>>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Botones de acción rápida -->
            <!-- ...acciones rápidas eliminadas... -->

            <!-- Botones del formulario -->
            <div style="margin-top: 30px; text-align: right;">
                <button type="button" id="btn-cancelar" style="margin-right: 10px; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit" style="padding: 10px 20px; background: #C57F1B; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Actualizar Rol
                </button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Interceptar el envío del formulario para usar AJAX
    $('#form-rol').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var actionUrl = $(this).attr('action');
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Cargar la lista de roles usando el sistema del menú
                    $.ajax({
                        url: '<?php echo e(route("roles.index")); ?>',
                        type: 'GET',
                        cache: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(data) {
                            // Usar la función displayContent del menú principal
                            if (typeof displayContent === 'function') {
                                displayContent(data);
                            } else {
                                // Fallback si displayContent no está disponible
                                $("#main-content-overlay").html(data);
                                $("#main-content-overlay").css('display', 'block');
                            }
                            
                            // Mostrar mensaje de éxito
                            if (typeof mostrarMensaje === 'function') {
                                mostrarMensaje('Rol actualizado correctamente', 'success');
                            } else {
                                alert('Rol actualizado correctamente');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error al cargar la lista de roles:', xhr.responseText);
                            alert('Error al cargar la lista de roles');
                        }
                    });
                } else {
                    alert('Error al actualizar el rol: ' + (response.message || 'Error desconocido'));
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('Error al actualizar el rol');
            }
        });
    });

    // ...acciones rápidas eliminadas...

    // Botón cancelar
    $('#btn-cancelar').on('click', function(e) {
        e.preventDefault();
        
        // Cargar la lista de roles
        $.ajax({
            url: '<?php echo e(route("roles.index")); ?>',
            type: 'GET',
            cache: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                // Usar la función displayContent del menú principal
                if (typeof displayContent === 'function') {
                    displayContent(data);
                } else {
                    // Fallback si displayContent no está disponible
                    $("#main-content-overlay").html(data);
                    $("#main-content-overlay").css('display', 'block');
                }
            },
            error: function(xhr) {
                console.error('Error al cargar la lista de roles:', xhr.responseText);
                alert('Error al cargar la lista de roles');
            }
        });
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/roles/edit_rol.blade.php ENDPATH**/ ?>