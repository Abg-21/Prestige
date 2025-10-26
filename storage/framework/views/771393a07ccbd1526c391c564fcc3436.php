<div class="container" style="max-width: 800px; margin-top: 40px;">
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 28px;">
        <h1 class="text-center mb-4" style="color: #FE7743;">Editar Puesto</h1>
        <div id="erroresForm" class="alert alert-danger d-none"></div>
        <form id="formEditarPuesto" action="<?php echo e(route('puestos.update', $puesto->idPuestos)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Categoría" class="form-label fw-bold">Categoría</label>
                    <select class="form-select" id="Categoría" name="Categoría" required>
                        <option value="">Seleccionar</option>
                        <option value="Promovendedor" <?php echo e(old('Categoría', $puesto->Categoría) == 'Promovendedor' ? 'selected' : ''); ?>>Promovendedor</option>
                        <option value="Promotor" <?php echo e(old('Categoría', $puesto->Categoría) == 'Promotor' ? 'selected' : ''); ?>>Promotor</option>
                        <option value="Supervisor" <?php echo e(old('Categoría', $puesto->Categoría) == 'Supervisor' ? 'selected' : ''); ?>>Supervisor</option>
                        <option value="Otro" <?php echo e(old('Categoría', $puesto->Categoría) == 'Otro' ? 'selected' : ''); ?>>Otro</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Puesto" class="form-label fw-bold">Nombre del Puesto</label>
                    <input type="text" class="form-control" id="Puesto" name="Puesto" value="<?php echo e(old('Puesto', $puesto->Puesto)); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="selectGiro" class="form-label fw-bold">Giro</label>
                    <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                        <option value="">Seleccione un giro</option>
                        <?php $__currentLoopData = $giros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $giro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($giro->idGiros); ?>" <?php echo e(old('id_GiroPuestoFK', $puesto->id_GiroPuestoFK) == $giro->idGiros ? 'selected' : ''); ?>><?php echo e($giro->Nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="selectCliente" class="form-label fw-bold">Cliente</label>
                    <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                        <option value="">Seleccione un cliente</option>
                        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cliente->idClientes); ?>" <?php echo e(old('id_ClientePuestoFK', $puesto->id_ClientePuestoFK) == $cliente->idClientes ? 'selected' : ''); ?>><?php echo e($cliente->Nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Zona" class="form-label fw-bold">Zona</label>
                    <input type="text" class="form-control" id="Zona" name="Zona" value="<?php echo e(old('Zona', $puesto->Zona)); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Estado" class="form-label fw-bold">Estado</label>
                    <select id="Estado" name="Estado" class="form-select" required>
                        <?php
                            $listaEstados = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                                'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                                'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
                                'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
                                'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
                            ];
                        ?>
                        <?php $__currentLoopData = $listaEstados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($estado); ?>" <?php echo e(old('Estado', $puesto->Estado) == $estado ? 'selected' : ''); ?>><?php echo e($estado); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Edad</label>
                    <?php
                        $edadSeleccionada = is_array(old('Edad')) ? old('Edad') : explode(', ', $puesto->Edad);
                    ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__currentLoopData = ['18-23', '24-30', '31-35', '36-42', '43-51', '52-60']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rango): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check me-3">
                                <input type="checkbox" class="form-check-input" name="Edad[]" value="<?php echo e($rango); ?>"
                                    <?php echo e(in_array($rango, $edadSeleccionada) ? 'checked' : ''); ?>>
                                <label class="form-check-label"><?php echo e($rango); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Escolaridad" class="form-label fw-bold">Escolaridad</label>
                    <select id="Escolaridad" name="Escolaridad" class="form-select" required>
                        <?php $__currentLoopData = ['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($nivel); ?>" <?php echo e(old('Escolaridad', $puesto->Escolaridad) == $nivel ? 'selected' : ''); ?>><?php echo e($nivel); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Experiencia" class="form-label fw-bold">Experiencia</label>
                    <input type="text" class="form-control" id="Experiencia" name="Experiencia" value="<?php echo e(old('Experiencia', $puesto->Experiencia)); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Conocimientos</label>
                    <div id="conocimientos-list">
                        <?php
                            $conocimientos = old('Conocimientos', $puesto->Conocimientos ?? []);
                            if (!is_array($conocimientos)) $conocimientos = json_decode($conocimientos, true) ?? (is_string($conocimientos) ? explode(',', $conocimientos) : []);
                        ?>
                        <?php $__currentLoopData = $conocimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conocimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Conocimientos[]" class="form-control" value="<?php echo e($conocimiento); ?>">
                                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(empty($conocimientos)): ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Conocimientos[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-conocimiento" class="btn btn-primary btn-sm mb-3">Agregar Conocimiento</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Funciones</label>
                    <div id="funciones-list">
                        <?php
                            $funciones = old('Funciones', $puesto->Funciones ?? []);
                            if (!is_array($funciones)) $funciones = json_decode($funciones, true) ?? (is_string($funciones) ? explode(',', $funciones) : []);
                        ?>
                        <?php $__currentLoopData = $funciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $funcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Funciones[]" class="form-control" value="<?php echo e($funcion); ?>">
                                <button type="button" class="btn btn-danger remove-funcion">-</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(empty($funciones)): ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Funciones[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-funcion">-</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-funcion" class="btn btn-primary btn-sm mb-3">Agregar Función</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Habilidades</label>
                    <div id="habilidades-list">
                        <?php
                            $habilidades = old('Habilidades', $puesto->Habilidades ?? []);
                            if (!is_array($habilidades)) $habilidades = json_decode($habilidades, true) ?? (is_string($habilidades) ? explode(',', $habilidades) : []);
                        ?>
                        <?php $__currentLoopData = $habilidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habilidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Habilidades[]" class="form-control" value="<?php echo e($habilidad); ?>">
                                <button type="button" class="btn btn-danger remove-habilidad">-</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(empty($habilidades)): ?>
                            <div class="input-group mb-2">
                                <input type="text" name="Habilidades[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-habilidad">-</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-habilidad" class="btn btn-primary btn-sm mb-3">Agregar Habilidad</button>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="<?php echo e(route('puestos.index')); ?>" class="btn btn-danger ajax-link" style="padding: 8px 20px;">Cancelar</a>
                <button type="submit" class="btn" style="background: #FE7743; color: #fff;">Actualizar Puesto</button>
            </div>
        </form>
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

// Interceptar formulario con JavaScript inmediato para puestos
(function() {
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPuestoFormHandler);
    } else {
        initPuestoFormHandler();
    }
    
    function initPuestoFormHandler() {
        const form = document.getElementById('formEditarPuesto');
        if (!form) {
            console.log('Formulario de puesto no encontrado');
            return;
        }
        
        console.log('Formulario de puesto encontrado, configurando AJAX');
        
        // Prevenir submit por defecto
        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('¡FORMULARIO DE PUESTO INTERCEPTADO CON AJAX!');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            
            // Cambiar botón a estado de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Guardando...';
            submitBtn.disabled = true;
            
            console.log('Enviando petición AJAX de puesto...');
            
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
                console.log('Respuesta de puesto recibida:', response.status, response.statusText);
                
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
                console.log('Puesto actualizado, HTML recibido:', html.length, 'caracteres');
                console.log('Primeros 200 caracteres:', html.substring(0, 200));
                
                // IMPORTANTE: Restaurar botón SIEMPRE
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Actualizar con la lista de puestos
                const contenidoPrincipal = document.querySelector('#main-content-overlay');
                if (contenidoPrincipal) {
                    contenidoPrincipal.innerHTML = html;
                    mostrarNotificacionPuesto('Puesto actualizado correctamente', 'success');
                } else {
                    console.error('No se encontró #main-content-overlay');
                }
            })
            .catch(function(error) {
                console.error('Error al actualizar puesto:', error);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                mostrarNotificacionPuesto('Error al actualizar el puesto: ' + error.message, 'error');
            });
            
            return false;
        };
    }
    
    function mostrarNotificacionPuesto(mensaje, tipo) {
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

// Agregar y eliminar dinámicamente conocimientos, funciones y habilidades
function initDynamicFields() {
    // Agregar conocimiento
    $('#add-conocimiento').on('click', function() {
        $('#conocimientos-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Conocimientos[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
            </div>
        `);
    });

    // Eliminar conocimiento
    $(document).on('click', '.remove-conocimiento', function() {
        $(this).closest('.input-group').remove();
    });

    // Agregar función
    $('#add-funcion').on('click', function() {
        $('#funciones-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Funciones[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-funcion">-</button>
            </div>
        `);
    });

    // Eliminar función
    $(document).on('click', '.remove-funcion', function() {
        $(this).closest('.input-group').remove();
    });

    // Agregar habilidad
    $('#add-habilidad').on('click', function() {
        $('#habilidades-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Habilidades[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-habilidad">-</button>
            </div>
        `);
    });

    // Eliminar habilidad
    $(document).on('click', '.remove-habilidad', function() {
        $(this).closest('.input-group').remove();
    });
}

$(document).ready(function() {
    initDynamicFields();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo e(asset('js/modal_cliente_giro.js')); ?>"></script>
<script src="<?php echo e(asset('js/form_puestos.js')); ?>"></script>

<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/edit_puesto.blade.php ENDPATH**/ ?>