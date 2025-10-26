<div style="width: 100%; max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
        <h2 style="margin-bottom: 0; color: #FE7743; font-weight: bold; display: flex; align-items: center;">
            Crear Nuevo Giro
        </h2>
    </div>
    <form id="form-crear-giro" action="<?php echo e(route('giros.store')); ?>" method="POST" autocomplete="off">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom: 22px;">
            <label for="Nombre" style="font-weight: bold;">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="<?php echo e(old('Nombre')); ?>" required class="form-control" style="margin-top: 6px;">
            <?php $__errorArgs = ['Nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom: 22px;">
            <label for="Descripcion" style="font-weight: bold;">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion" class="form-control" style="margin-top: 6px;"><?php echo e(old('Descripcion')); ?></textarea>
            <?php $__errorArgs = ['Descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <a href="<?php echo e(route('giros.index')); ?>" class="btn btn-danger ajax-link" style="padding: 8px 20px;">Cancelar</a>
            <button type="submit" class="btn btn-success" style="padding: 8px 20px;">Crear Giro</button>
        </div>
    </form>
</div>

<script type="text/javascript">
// Interceptar formulario con JavaScript inmediato
(function() {
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormHandler);
    } else {
        initFormHandler();
    }
    
    function initFormHandler() {
        const form = document.getElementById('form-crear-giro');
        if (!form) {
            console.log('Formulario de giro no encontrado');
            return;
        }
        
        console.log('Formulario de giro encontrado, configurando AJAX');
        
        // Prevenir submit por defecto
        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('¡FORMULARIO DE GIRO INTERCEPTADO CON AJAX!');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            
            // Cambiar botón a estado de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creando...';
            submitBtn.disabled = true;
            
            console.log('Enviando petición AJAX para giro...');
            
            // Hacer petición AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                console.log('Respuesta recibida para giro:', response.status, response.statusText);
                
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
                console.log('HTML de giros recibido, longitud:', html.length);
                console.log('Primeros 200 caracteres:', html.substring(0, 200));
                
                // IMPORTANTE: Restaurar botón SIEMPRE
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Actualizar el contenido principal con la lista de giros
                const contenidoPrincipal = document.querySelector('#main-content-overlay');
                if (contenidoPrincipal) {
                    contenidoPrincipal.innerHTML = html;
                    console.log('Contenido de giros actualizado exitosamente');
                    
                    // Mostrar notificación de éxito
                    mostrarNotificacion('Giro creado correctamente', 'success');
                } else {
                    console.error('No se encontró #main-content-overlay');
                    console.log('Selectores disponibles:', document.querySelector('div').className);
                }
            })
            .catch(function(error) {
                console.error('Error en AJAX de giro:', error);
                // Restaurar botón
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                mostrarNotificacion('Error al crear el giro: ' + error.message, 'error');
            });
            
            return false; // Asegurar que no se envíe normalmente
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

<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/giros/create_giro.blade.php ENDPATH**/ ?>