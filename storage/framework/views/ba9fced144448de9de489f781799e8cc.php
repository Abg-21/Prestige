<div style="padding: 20px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <h2 style="margin-bottom: 20px; margin-right: 24px; display: flex; align-items: center;">
                Puestos
            </h2>
            <a href="<?php echo e(route('puestos.create')); ?>" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Agregar un puesto">
                <?php if(App\Helpers\PermissionHelper::hasPermission('puestos', 'crear')): ?>
                <img src="<?php echo e(asset('images/agregar-usuario.png')); ?>" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
                <?php endif; ?>
            </a>
        </div>
    </div>
    <?php if($puestos->isEmpty()): ?>
        <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
            No hay puestos registrados.
        </div>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #447D9B; color: #fff;">
                    <th style="padding: 8px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Categoría</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Puesto</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Giro</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Cliente</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($puesto->idPuestos); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($puesto->Categoría); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($puesto->Puesto); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($puesto->giro ? $puesto->giro->Nombre : 'Sin giro'); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo e($puesto->cliente ? $puesto->cliente->Nombre : 'Sin cliente'); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <div style="display: flex; gap: 24px; justify-content: center;">
                                <a href="<?php echo e(route('puestos.edit', $puesto->idPuestos)); ?>" class="ajax-link" style="text-align: center;">
                                    <img src="<?php echo e(asset('images/editar.png')); ?>" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Editar</div>
                                </a>
                                <a href="<?php echo e(route('puestos.show', $puesto->idPuestos)); ?>" class="ajax-link" style="text-align: center;">
                                    <img src="<?php echo e(asset('images/ver.png')); ?>" alt="Ver" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #447D9B;">Ver</div>
                                </a>
                                <button type="button"
                                    <?php if(App\Helpers\PermissionHelper::hasPermission('puestos', 'eliminar')): ?>
                                    <button type="button"
                                        class="btn-eliminar-puesto"
                                        data-id="<?php echo e($puesto->idPuestos); ?>"
                                        style="background: none; border: none; padding: 0; cursor: pointer; text-align: center;">
                                        <img src="<?php echo e(asset('images/eliminar.png')); ?>" alt="Eliminar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                    </button>
                                    <?php endif; ?>
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
        <div style="font-size:18px; margin-bottom:24px;">¿Estás seguro de eliminar este puesto?</div>
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
    $('.alert-float').remove();
    var alert = $('<div class="alert-float alert-' + type + '">' + message + '</div>');
    $('body').append(alert);
    setTimeout(function() { alert.addClass('show'); }, 10);
    setTimeout(function() {
        alert.removeClass('show');
        setTimeout(function() { alert.remove(); }, 300);
    }, 2000);
}

// Mostrar alerta si viene de una creación exitosa
$(document).ready(function() {
    console.log('✅ jQuery cargado en puestos - versión:', $.fn.jquery);
    console.log('🔍 Verificando elementos de eliminación...');
    console.log('  - Botones eliminar encontrados:', $('.btn-eliminar-puesto').length);
    console.log('  - Modal confirmar encontrado:', $('#modal-confirmar-eliminar').length > 0 ? 'SÍ' : 'NO');
    
    <?php if(session('success')): ?>
        showAlert('<?php echo e(session('success')); ?>', 'success');
    <?php endif; ?>
});

var puestoIdPendiente = null;

$(document).on('click', '.btn-eliminar-puesto', function(e) {
    e.preventDefault();
    console.log('🗑️ BOTÓN ELIMINAR CLICKEADO');
    puestoIdPendiente = $(this).data('id');
    console.log('📝 ID del puesto a eliminar:', puestoIdPendiente);
    $('#modal-confirmar-eliminar').fadeIn(150).css('display', 'flex');
    console.log('⚠️ Modal de confirmación mostrado');
});

$(document).on('click', '#btn-confirmar-eliminar', function() {
    console.log('✅ CONFIRMACIÓN DE ELIMINACIÓN');
    if (!puestoIdPendiente) {
        console.error('❌ No hay ID de puesto pendiente');
        return;
    }
    
    console.log('📤 Enviando petición DELETE para ID:', puestoIdPendiente);
    
    $.ajax({
        url: "/debug-ajax/eliminate",
        type: 'POST',
        data: {
            _token: '<?php echo e(csrf_token()); ?>',
            puesto_id: puestoIdPendiente
        },
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'X-Requested-With': 'XMLHttpRequest'
        },
        beforeSend: function() {
            console.log('⏳ Enviando petición de eliminación...');
        },
        success: function(response) {
            console.log('✅ Respuesta de eliminación exitosa:', response);
            if (response.success) {
                showAlert('Puesto eliminado correctamente', 'success');
                
                console.log('🔄 Recargando lista de puestos...');
                // Recargar inmediatamente sin timeout
                $.get("<?php echo e(route('puestos.index')); ?>" + "?_=" + Date.now(), function(html) {
                    $('#main-content-overlay').html(html);
                    console.log('📋 Lista de puestos actualizada después de eliminar');
                }).fail(function() {
                    console.error('❌ Error al recargar lista de puestos');
                    // Si falla AJAX, recargar toda la página
                    window.location.reload();
                });
            }
            $('#modal-confirmar-eliminar').fadeOut(150);
        },
        error: function(xhr) {
            console.error('❌ Error en eliminación:', xhr.status, xhr.responseText);
            if (xhr.status === 409 && xhr.responseJSON.confirm) {
                console.log('⚠️ Requiere confirmación por relaciones');
                $('#modal-confirmar-eliminar').fadeOut(150);
                if (confirm(xhr.responseJSON.message)) {
                    console.log('✅ Usuario confirmó eliminación forzada');
                    $.ajax({
                        url: "<?php echo e(url('puestos')); ?>/" + puestoIdPendiente,
                        type: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                            _method: 'DELETE',
                            force: true
                        },
                        success: function(response) {
                            console.log('✅ Puesto eliminado forzadamente:', response);
                            if (response.success) {
                                showAlert('Puesto eliminado correctamente', 'success');
                                
                                // Recargar inmediatamente
                                $.get("<?php echo e(route('puestos.index')); ?>" + "?_=" + Date.now(), function(html) {
                                    $('#main-content-overlay').html(html);
                                    console.log('📋 Lista actualizada después de eliminación forzada');
                                }).fail(function() {
                                    console.error('❌ Error al recargar después de eliminación forzada');
                                    window.location.reload();
                                });
                            }
                        },
                        error: function() {
                            showAlert('Hubo un error al intentar eliminar el puesto', 'error');
                        }
                    });
                }
            } else {
                showAlert('Hubo un error al intentar eliminar el puesto', 'error');
            }
            $('#modal-confirmar-eliminar').fadeOut(150);
        }
    });
    
    puestoIdPendiente = null;
});

$(document).on('click', '#btn-cancelar-eliminar', function() {
    $('#modal-confirmar-eliminar').fadeOut(150);
    puestoIdPendiente = null;
});
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/puesto.blade.php ENDPATH**/ ?>