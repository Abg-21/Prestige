<div style="padding: 20px;">
    <h2 style="margin-bottom: 20px; color: rgb(0, 0, 0);">Gestión de Roles
        <?php if(App\Helpers\PermissionHelper::hasPermission('roles', 'crear')): ?>
        <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-primary ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center; background-color: #C57F1B; border-color: #C57F1B;" title="Crear nuevo rol">
            <img src="<?php echo e(asset('images/agregar-usuario.png')); ?>" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
        </a>
        <?php endif; ?>
    </h2>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-warning" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #fff3cd; color: #856404;">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($roles->isEmpty()): ?>
        <div style="padding: 20px; color: #6c757d; font-style: italic; text-align: center;">
            No hay roles registrados.
        </div>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #447D9B; color: #fff;">
                    <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Nombre</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Descripción</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Usuarios Asignados</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="padding: 12px; border: 1px solid #ddd;"><?php echo e($rol->id); ?></td>
                        <td style="padding: 12px; border: 1px solid #ddd;">
                            <strong><?php echo e($rol->nombre); ?></strong>
                        </td>
                        <td style="padding: 12px; border: 1px solid #ddd;"><?php echo e($rol->descripcion); ?></td>
                        <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                            <?php if($rol->usuarios && $rol->usuarios->count() > 0): ?>
                                <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                                    <?php echo e($rol->usuarios->count()); ?> usuario(s)
                                </span>
                            <?php else: ?>
                                <span style="color: #6c757d; font-style: italic;">Sin usuarios</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px; border: 1px solid #ddd;">
                            <div style="display: flex; gap: 24px; justify-content: center;">
                                <a href="<?php echo e(route('roles.edit', $rol->id)); ?>" class="ajax-link" style="text-align: center;">
                                    <img src="<?php echo e(asset('images/editar.png')); ?>" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Editar</div>
                                </a>
                                <?php if($rol->usuarios->count() == 0): ?>
                                    <?php if(App\Helpers\PermissionHelper::hasPermission('roles', 'eliminar')): ?>
                                    <button type="button" class="btn-eliminar-rol" data-id="<?php echo e($rol->id); ?>" style="background: none; border: none; padding: 0; cursor: pointer; text-align: center;">
                                        <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="Eliminar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                    </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div style="text-align: center; opacity: 0.5;" title="No se puede eliminar porque tiene usuarios asignados">
                                        <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="No disponible" style="width: 32px; height: 32px; filter: grayscale(100%);">
                                        <div style="font-size: 13px; color: #6c757d;">No disponible</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
$(document).on('click', '.btn-eliminar-rol', function(e) {
    e.preventDefault();
    var rolId = $(this).data('id');
    
    if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
        // Crear formulario para enviar DELETE
        var form = $('<form>', {
            'method': 'POST',
            'action': '/roles/' + rolId
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_method',
            'value': 'DELETE'
        }));
        
        $('body').append(form);
        form.submit();
    }
});
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/roles/roles.blade.php ENDPATH**/ ?>