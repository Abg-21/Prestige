<div style="padding: 20px;">
        <h2 style="margin-bottom: 20px; color:rgb(0, 0, 0);">Clientes
           <?php if(App\Helpers\PermissionHelper::hasPermission('clientes', 'crear')): ?>
           <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary ajax-link" data-modal="false" style="margin-left: 15px; display: inline-flex; align-items: center; background-color: #C57F1B; border-color: #C57F1B;" title="Agregar cliente">
                <img src="<?php echo e(asset('images/agregar-usuario.png')); ?>" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
            </a>
           <?php endif; ?>
        </h2>
        <?php if($clientes->isEmpty()): ?>
            <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
                No hay clientes registrados.
            </div>
        <?php else: ?>
            <table style="width:100%; border-collapse: collapse; background: #fff;">
                <thead>
                    <tr style="background: #FE7743; color: #fff;">
                        <th style="padding: 8px; border: 1px solid #ddd;">ID</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Nombre</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Teléfono</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Descripción</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($cliente->idClientes); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($cliente->Nombre); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($cliente->Telefono); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($cliente->Descripcion); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <div style="display: flex; gap: 24px;">
                                    <a href="<?php echo e(route('clientes.edit', $cliente->idClientes)); ?>" class="ajax-link" style="text-align: center;">
                                        <img src="<?php echo e(asset('images/editar.png')); ?>" alt="Editar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #555;">Editar</div>
                                    </a>
                                    <form action="<?php echo e(route('clientes.destroy', $cliente->idClientes)); ?>" method="POST" class="form-eliminar-cliente" style="display:inline-block; text-align: center;">
                                        <?php if(App\Helpers\PermissionHelper::hasPermission('clientes', 'eliminar')): ?>
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                            data-id="<?php echo e($cliente->idClientes); ?>"
                                            style="background: none; border: none; padding: 0; cursor: pointer;">
                                            <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="Eliminar" style="width: 32px; height: 32px;">
                                            <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                        </button>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
</div>

<!-- Modal de confirmación de eliminación -->
<div id="modal-confirmar-eliminar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:32px 24px; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.2); text-align:center; min-width:300px;">
        <div style="font-size:18px; margin-bottom:24px;">¿Seguro que deseas eliminar al cliente?</div>
        <button id="btn-confirmar-eliminar" style="padding:8px 24px; background:#e74c3c; color:#fff; border:none; border-radius:4px; margin-right:12px; cursor:pointer;">Sí, eliminar</button>
        <button id="btn-cancelar-eliminar" style="padding:8px 24px; background:#447D9B; color: white; border:none; border-radius:4px; cursor:pointer;">Cancelar</button>
    </div>
</div>

<style>
.alert-float {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 30px;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    z-index: 9999;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
}

.alert-success {
    background-color: #2ecc71;
}

.alert-error {
    background-color: #e74c3c;
}

.alert-float.show {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
function showAlert(message, type) {
    // Remove any existing alerts
    $('.alert-float').remove();
    
    // Create new alert
    var alert = $('<div class="alert-float alert-' + type + '">' + message + '</div>');
    $('body').append(alert);
    
    // Show alert
    setTimeout(function() {
        alert.addClass('show');
    }, 10);
    
    // Remove alert after 2 seconds
    setTimeout(function() {
        alert.removeClass('show');
        setTimeout(function() {
            alert.remove();
        }, 300);
    }, 2000);
}

var formEliminarPendiente = null;

// Mostrar modal al intentar eliminar
$(document).on('submit', '.form-eliminar-cliente', function(e) {
    e.preventDefault();
    e.stopPropagation(); // Evitar que otros manejadores interfieran
    
    console.log('🗑️ Interceptando formulario de eliminación de cliente');
    
    formEliminarPendiente = $(this);
    
    // Mostrar el modal de confirmación
    $('#modal-confirmar-eliminar').fadeIn(150).css('display', 'flex');
    
    return false; // Asegurar que no se procese el formulario
});

// Confirmar eliminación
$(document).on('click', '#btn-confirmar-eliminar', function() {
    if (!formEliminarPendiente) return;
    
    $.ajax({
        url: formEliminarPendiente.attr('action'),
        type: 'POST',
        data: formEliminarPendiente.serialize(),
        success: function(response) {
            if (response.success) {
                showAlert('Cliente eliminado correctamente', 'success');
                setTimeout(function() {
                    $.get("<?php echo e(route('clientes.index')); ?>", function(html) {
                        $('#main-content-overlay').html(html);
                    });
                }, 1000);
                $('#modal-confirmar-eliminar').fadeOut(150);
            }
        },
        error: function(xhr) {
            if (xhr.status === 409 && xhr.responseJSON.confirm) {
                $('#modal-confirmar-eliminar').fadeOut(150);
                if (confirm(xhr.responseJSON.message)) {
                    let newData = formEliminarPendiente.serialize() + '&force=true';
                    $.ajax({
                        url: formEliminarPendiente.attr('action'),
                        type: 'POST',
                        data: newData,
                        success: function(response) {
                            if (response.success) {
                                showAlert('Cliente eliminado correctamente', 'success');
                                setTimeout(function() {
                                    $.get("<?php echo e(route('clientes.index')); ?>", function(html) {
                                        $('#main-content-overlay').html(html);
                                    });
                                }, 1000);
                            }
                        },
                        error: function() {
                            showAlert('Hubo un error al intentar eliminar al cliente', 'error');
                        }
                    });
                }
            } else {
                showAlert('Hubo un error al intentar eliminar al cliente', 'error');
            }
            $('#modal-confirmar-eliminar').fadeOut(150);
        }
    });
    
    formEliminarPendiente = null;
});

// Cancelar eliminación
$(document).on('click', '#btn-cancelar-eliminar', function() {
    $('#modal-confirmar-eliminar').fadeOut(150);
    formEliminarPendiente = null;
});
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/clientes/cliente.blade.php ENDPATH**/ ?>