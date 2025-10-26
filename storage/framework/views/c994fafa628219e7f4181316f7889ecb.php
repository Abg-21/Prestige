<div style="max-width: 500px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px; color: #FE7743">Editar Cliente</h2>
        <form id="form-editar-cliente" action="<?php echo e(route('clientes.update', $cliente->idClientes)); ?>" method="POST" style="display: flex; flex-direction: column; align-items: center;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Nombre" style="font-weight:bold;">Nombre:</label>
                <input type="text" name="Nombre" id="Nombre" value="<?php echo e(old('Nombre', $cliente->Nombre)); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <?php $__errorArgs = ['Nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color:#e74c3c;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Telefono" style="font-weight:bold;">Teléfono:</label>
                <input type="text" name="Telefono" id="Telefono" value="<?php echo e(old('Telefono', $cliente->Telefono)); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <?php $__errorArgs = ['Telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color:#e74c3c;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Descripcion" style="font-weight:bold;">Descripción:</label>
                <input type="text" name="Descripcion" id="Descripcion" value="<?php echo e(old('Descripcion', $cliente->Descripcion)); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <?php $__errorArgs = ['Descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div style="color:#e74c3c;"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div style="display: flex; justify-content: center; gap: 16px; margin-top: 28px; width: 100%;">
                <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-danger ajax-link" style="padding: 8px 20px; background: #e74c3c; color: #fff; border-radius: 4px; text-decoration: none; text-align: center;">Cancelar</a>
                <button type="submit" style="padding: 8px 20px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div> <!-- Cierre del div principal del formulario -->

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

    <script type="text/javascript">
    // Interceptar formulario de edición con JavaScript inmediato
    (function() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEditFormHandler);
        } else {
            initEditFormHandler();
        }
        
        function initEditFormHandler() {
            const form = document.getElementById('form-editar-cliente');
            if (!form) {
                console.log('Formulario de edición de cliente no encontrado');
                return;
            }
            
            console.log('Formulario de edición de cliente encontrado, configurando AJAX');
            
            form.onsubmit = function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('¡FORMULARIO DE EDICIÓN DE CLIENTE INTERCEPTADO!');
                
                const formData = new FormData(form);
                
                // Cambiar botón a estado de carga
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Guardando...';
                submitBtn.disabled = true;
                
                console.log('Enviando actualización de cliente...');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    console.log('Respuesta de edición recibida:', response.status, response.statusText);
                    
                    // Si la respuesta es un redirect (302, 301, etc)
                    if (response.redirected || response.status === 302 || response.status === 301) {
                        console.log('Respuesta es redirect, cargando URL:', response.url);
                        return fetch(response.url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(res => res.text());
                    }
                    
                    if (!response.ok) {
                        throw new Error('Error HTTP: ' + response.status);
                    }
                    
                    return response.text();
                })
                .then(function(html) {
                    console.log('Cliente actualizado, HTML recibido:', html.length, 'caracteres');
                    console.log('Primeros 200 caracteres:', html.substring(0, 200));
                    
                    // IMPORTANTE: Restaurar botón SIEMPRE
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    
                    // Actualizar con la lista de clientes
                    const contenidoPrincipal = document.querySelector('#main-content-overlay');
                    if (contenidoPrincipal) {
                        contenidoPrincipal.innerHTML = html;
                        mostrarNotificacion('Cliente actualizado correctamente', 'success');
                    } else {
                        console.error('No se encontró #main-content-overlay');
                    }
                })
                .catch(function(error) {
                    console.error('Error al actualizar cliente:', error);
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    mostrarNotificacion('Error al actualizar el cliente: ' + error.message, 'error');
                });
                
                return false;
            };
        }
        
        function mostrarNotificacion(mensaje, tipo) {
            const div = document.createElement('div');
            div.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; padding: 15px; border-radius: 4px; color: white; font-weight: bold;';
            div.style.backgroundColor = tipo === 'success' ? '#28a745' : '#dc3545';
            div.textContent = mensaje;
            
            document.body.appendChild(div);
            
            setTimeout(function() {
                if (div.parentNode) {
                    div.parentNode.removeChild(div);
                }
            }, 3000);
        }
    })();
    </script>

<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/clientes/edit_cliente.blade.php ENDPATH**/ ?>