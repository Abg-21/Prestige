<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Gestión de Usuarios</h2>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Botón para crear usuario -->
                <?php if(App\Helpers\PermissionHelper::hasPermission('usuarios', 'crear')): ?>
                <a href="<?php echo e(route('usuarios.create')); ?>" class="btn btn-success mb-3 ajax-link">
                    <i class="fas fa-plus"></i> Crear Usuario
                </a>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Fecha Creación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($usuario->id); ?></td>
                                <td><?php echo e($usuario->nombre); ?></td>
                                <td><?php echo e($usuario->correo); ?></td>
                                <td>
                                    <?php if($usuario->roles->count() > 0): ?>
                                        <?php $__currentLoopData = $usuario->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-primary"><?php echo e($rol->nombre); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Sin rol</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A'); ?></td>
                                <td>
                                    <?php if($usuario->eliminado_en): ?>
                                        <span class="badge bg-danger">Eliminado</span>
                                        <small class="text-muted d-block"><?php echo e($usuario->eliminado_en->format('d/m/Y H:i')); ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('usuarios.edit', $usuario->id)); ?>" 
                                           class="btn btn-primary btn-sm ajax-link"
                                           <?php if(!App\Helpers\PermissionHelper::hasPermission('usuarios', 'editar')): ?> disabled <?php endif; ?>>
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        
                                        <?php if(!$usuario->eliminado_en): ?>
                                            <?php if(App\Helpers\PermissionHelper::hasPermission('usuarios', 'eliminar')): ?>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm btn-eliminar-usuario" 
                                                    data-id="<?php echo e($usuario->id); ?>"
                                                    data-nombre="<?php echo e($usuario->nombre); ?>">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button type="button" 
                                                    class="btn btn-success btn-sm btn-restaurar-usuario" 
                                                    data-id="<?php echo e($usuario->id); ?>"
                                                    data-nombre="<?php echo e($usuario->nombre); ?>">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación -->
    <div class="modal fade" id="modal-confirmar-eliminar-usuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar al usuario <strong id="nombre-usuario-eliminar"></strong>?</p>
                    <p class="text-muted">Esta acción se puede revertir posteriormente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar-usuario">Sí, eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let usuarioIdPendiente = null;

    // Manejar click en botón eliminar
    $(document).on('click', '.btn-eliminar-usuario', function(e) {
        e.preventDefault();
        
        usuarioIdPendiente = $(this).data('id');
        const nombreUsuario = $(this).data('nombre');
        
        $('#nombre-usuario-eliminar').text(nombreUsuario);
        $('#modal-confirmar-eliminar-usuario').modal('show');
    });

    // Confirmar eliminación
    $(document).on('click', '#btn-confirmar-eliminar-usuario', function(e) {
        e.preventDefault();
        
        if (!usuarioIdPendiente) return;
        
        $.ajax({
            url: `/usuarios/${usuarioIdPendiente}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#modal-confirmar-eliminar-usuario').modal('hide');
                
                if (response.success) {
                    // Recargar la vista de usuarios
                    $.get("<?php echo e(route('usuarios.index')); ?>", function(html) {
                        $("#main-content-overlay").html(html);
                    });
                    
                    mostrarNotificacion(response.message, 'success');
                }
            },
            error: function(xhr) {
                $('#modal-confirmar-eliminar-usuario').modal('hide');
                
                let mensaje = 'Error al eliminar usuario';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                
                mostrarNotificacion(mensaje, 'error');
            }
        });
        
        usuarioIdPendiente = null;
    });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/usuarios/user.blade.php ENDPATH**/ ?>