<!-- VISTA ACTUALIZADA - VERSION CORREGIDA CON VALIDACIONES -->
<div style="width: 100%; max-width: 700px; margin: 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 28px; color: #FE7743;">Crear Puesto</h2>
    <form id="form-crear-puesto" action="<?php echo e(route('puestos.store')); ?>" method="POST" autocomplete="off">
        <?php echo csrf_field(); ?>

        <div style="display: flex; gap: 16px;">
            <div style="flex: 1;">
                <label>Categoría:</label>
                <select name="Categoría" required style="width: 100%;" class="form-select">
                    <option value="">Seleccionar</option>
                    <option value="Promovendedor">Promovendedor</option>
                    <option value="Promotor">Promotor</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div style="flex: 2;">
                <label>Nombre del puesto:</label>
                <input type="text" name="Puesto" value="<?php echo e(old('Puesto')); ?>" required style="width: 100%;">
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Giro:</label>
            <div class="input-group mb-3">
                <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                    <option value="" disabled <?php echo e(old('id_GiroPuestoFK') ? '' : 'selected'); ?>>Seleccione un giro</option>
                    <?php $__currentLoopData = $giros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $giro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($giro->idGiros); ?>" <?php echo e(old('id_GiroPuestoFK') == $giro->idGiros ? 'selected' : ''); ?>>
                            <?php echo e($giro->Nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Cliente:</label>                    
            <div class="input-group mb-3">
                <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                    <option value="" disabled <?php echo e(old('id_ClientePuestoFK') ? '' : 'selected'); ?>>Seleccione un cliente</option>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cliente->idClientes); ?>" <?php echo e(old('id_ClientePuestoFK') == $cliente->idClientes ? 'selected' : ''); ?>>
                            <?php echo e($cliente->Nombre); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Ruta y/o Cadena:</label>
                <input type="text" name="Zona" value="<?php echo e(old('Zona')); ?>" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Estado(Zona):</label>
                <select name="Estado" required style="width: 100%;" class="form-select">
                    <option value="" disabled <?php echo e(old('Estado') ? '' : 'selected'); ?>>Seleccione un estado</option>
                    <?php
                        $listaEstados = [
                            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                            'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                            'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
                            'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
                            'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
                        ];
                    ?>
                    <?php $__currentLoopData = $listaEstados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($Estado); ?>" <?php echo e(old('Estado') == $Estado ? 'selected' : ''); ?>>
                            <?php echo e($Estado); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Edad:</label>
            <div style="display: flex; gap: 16px;">
                <div style="flex: 1;">
                    <?php $__currentLoopData = ['18-23', '24-30', '31-35']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rango): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input type="checkbox" id="edad_<?php echo e($rango); ?>" class="form-check-input" name="Edad[]" value="<?php echo e($rango); ?>"
                                <?php echo e(is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="edad_<?php echo e($rango); ?>"><?php echo e($rango); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div style="flex: 1;">
                    <?php $__currentLoopData = ['36-42', '43-51', '52-60']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rango): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input type="checkbox" id="edad_<?php echo e($rango); ?>" class="form-check-input" name="Edad[]" value="<?php echo e($rango); ?>"
                                <?php echo e(is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="edad_<?php echo e($rango); ?>"><?php echo e($rango); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Escolaridad:</label>
                <select name="Escolaridad" required style="width: 100%;" class="form-select">
                    <?php $__currentLoopData = ['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($nivel); ?>" <?php echo e(old('Escolaridad') == $nivel ? 'selected' : ''); ?>><?php echo e($nivel); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="flex: 1;">
                <label>Experiencia:</label>
                <input type="text" name="Experiencia" value="<?php echo e(old('Experiencia')); ?>" required style="width: 100%;">
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Conocimientos: <span style="color: red;">*</span></label>
            <div id="conocimientos-list">
                <?php if(is_array(old('Conocimientos'))): ?>
                    <?php $__currentLoopData = old('Conocimientos'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conocimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mb-2">
                            <input type="text" name="Conocimientos[]" class="form-control" value="<?php echo e($conocimiento); ?>" required placeholder="Ingrese conocimiento">
                            <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="input-group mb-2">
                        <input type="text" name="Conocimientos[]" class="form-control" value="" required placeholder="Ingrese conocimiento">
                        <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-conocimiento" class="btn btn-primary btn-sm mb-3">Agregar Conocimiento</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Funciones: <span style="color: red;">*</span></label>
            <div id="funciones-list">
                <?php if(is_array(old('Funciones'))): ?>
                    <?php $__currentLoopData = old('Funciones'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $funcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mb-2">
                            <input type="text" name="Funciones[]" class="form-control" value="<?php echo e($funcion); ?>" required placeholder="Ingrese función">
                            <button type="button" class="btn btn-danger remove-funcion">-</button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="input-group mb-2">
                        <input type="text" name="Funciones[]" class="form-control" value="" required placeholder="Ingrese función">
                        <button type="button" class="btn btn-danger remove-funcion">-</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-funcion" class="btn btn-primary btn-sm mb-3">Agregar Función</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Habilidades: <span style="color: red;">*</span></label>
            <div id="habilidades-list">
                <?php if(is_array(old('Habilidades'))): ?>
                    <?php $__currentLoopData = old('Habilidades'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habilidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="input-group mb-2">
                            <input type="text" name="Habilidades[]" class="form-control" value="<?php echo e($habilidad); ?>" required placeholder="Ingrese habilidad">
                            <button type="button" class="btn btn-danger remove-habilidad">-</button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="input-group mb-2">
                        <input type="text" name="Habilidades[]" class="form-control" value="" required placeholder="Ingrese habilidad">
                        <button type="button" class="btn btn-danger remove-habilidad">-</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-habilidad" class="btn btn-primary btn-sm mb-3">Agregar Habilidad</button>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <a href="<?php echo e(route('puestos.index')); ?>" id="btn-cancelar-puesto" class="btn btn-danger ajax-link" style="padding: 8px 20px; background: #dc3545 !important; color: white !important; text-decoration: none; border-radius: 4px; border: 1px solid #dc3545; display: inline-block !important; visibility: visible !important; opacity: 1 !important; z-index: 10;">Cancelar</a>
            <button type="submit" class="btn btn-success" style="padding: 8px 20px; background: #28a745; color: white; border: 1px solid #28a745; border-radius: 4px;">Guardar</button>
        </div>
    </form>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

$(document).on('submit', '#form-crear-puesto', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    
    // Prevenir múltiples envíos
    if (submitBtn.prop('disabled')) {
        console.log('⚠️ Formulario ya está siendo procesado');
        return false;
    }
    
    console.log('📤 INICIANDO validación del formulario de creación de puesto');
    
    // Validar que todos los campos dinámicos requeridos tengan contenido
    var validationErrors = [];
    
    // Contar conocimientos con contenido (SIN eliminar campos vacíos)
    var conocimientosCompletos = 0;
    form.find('input[name="Conocimientos[]"]').each(function() {
        var valor = $.trim($(this).val());
        console.log('📝 Conocimiento:', valor);
        if (valor !== '') {
            conocimientosCompletos++;
        }
    });
    console.log('📊 Total conocimientos válidos:', conocimientosCompletos);
    
    if (conocimientosCompletos === 0) {
        validationErrors.push('❌ Debe agregar al menos un conocimiento');
    }
    
    // Contar funciones con contenido
    var funcionesCompletas = 0;
    form.find('input[name="Funciones[]"]').each(function() {
        var valor = $.trim($(this).val());
        console.log('📝 Función:', valor);
        if (valor !== '') {
            funcionesCompletas++;
        }
    });
    console.log('📊 Total funciones válidas:', funcionesCompletas);
    
    if (funcionesCompletas === 0) {
        validationErrors.push('❌ Debe agregar al menos una función');
    }
    
    // Contar habilidades con contenido
    var habilidadesCompletas = 0;
    form.find('input[name="Habilidades[]"]').each(function() {
        var valor = $.trim($(this).val());
        console.log('📝 Habilidad:', valor);
        if (valor !== '') {
            habilidadesCompletas++;
        }
    });
    console.log('📊 Total habilidades válidas:', habilidadesCompletas);
    
    if (habilidadesCompletas === 0) {
        validationErrors.push('❌ Debe agregar al menos una habilidad');
    }
    
    // Validar que al menos un checkbox de edad esté seleccionado
    var edadSeleccionada = form.find('input[name="Edad[]"]:checked').length;
    console.log('📊 Rangos de edad seleccionados:', edadSeleccionada);
    if (edadSeleccionada === 0) {
        validationErrors.push('❌ Debe seleccionar al menos un rango de edad');
    }
    
    // DETENER EL ENVÍO si hay errores
    if (validationErrors.length > 0) {
        console.log('🚫 VALIDACIÓN FALLIDA - DETENIENDO ENVÍO:', validationErrors);
        showAlert(validationErrors[0], 'error');
        return false; // NO enviar el formulario
    }
    
    console.log('✅ VALIDACIÓN EXITOSA - Procediendo con envío AJAX:', {
        conocimientos: conocimientosCompletos,
        funciones: funcionesCompletas,
        habilidades: habilidadesCompletas,
        edades: edadSeleccionada
    });
    
    // Limpiar campos vacíos antes de enviar
    form.find('input[name="Conocimientos[]"], input[name="Funciones[]"], input[name="Habilidades[]"]').each(function() {
        if ($.trim($(this).val()) === '') {
            $(this).remove();
        }
    });
    
    // Deshabilitar botón mientras se procesa
    submitBtn.prop('disabled', true).text('Guardando...');
    console.log('🔄 Enviando formulario por AJAX...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        },
        dataType: 'html', // Esperar HTML, no JSON
        cache: false,
        success: function(response, textStatus, jqXHR) {
            console.log('✅ Respuesta AJAX exitosa desde create_puesto');
            console.log('Content-Type:', jqXHR.getResponseHeader('Content-Type'));
            console.log('Response length:', response.length);
            console.log('Text Status:', textStatus);
            
            // Asegurar que el response es válido
            if (response && typeof response === 'string' && response.trim().length > 50) {
                console.log('📄 Actualizando contenido principal...');
                
                // Actualizar contenido principal
                $('#main-content-overlay').html(response);
                
                // Mostrar mensaje de éxito
                showAlert('Puesto creado correctamente', 'success');
                
                console.log('✅ Vista actualizada exitosamente');
            } else {
                console.log('📄 Respuesta vacía o inválida, redirigiendo por seguridad...');
                window.location.href = "<?php echo e(route('puestos.index')); ?>";
            }
        },
        error: function(xhr) {
            console.error('❌ Error en el formulario desde create_puesto:', xhr);
            var msg = 'Hubo un error al crear el puesto';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                var errors = xhr.responseJSON.errors;
                var firstError = Object.values(errors)[0];
                msg = Array.isArray(firstError) ? firstError[0] : firstError;
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.status === 422) {
                msg = 'Por favor revise los datos del formulario';
            } else if (xhr.status === 500) {
                msg = 'Error del servidor, intente nuevamente';
            }
            
            showAlert(msg, 'error');
            console.log('🔄 Manteniendo formulario visible para corrección');
        },
        complete: function() {
            // Rehabilitar botón al finalizar (éxito o error)
            submitBtn.prop('disabled', false).text('Guardar');
        }
    });
    
    return false;
});

$(function() {
    // Función para agregar conocimientos
    $('#add-conocimiento').click(function() {
        console.log('🔧 Agregando nuevo conocimiento');
        $('#conocimientos-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Conocimientos[]" class="form-control" value="" required placeholder="Ingrese conocimiento">
                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
            </div>
        `);
    });
    
    // Función para remover conocimientos
    $(document).on('click', '.remove-conocimiento', function() {
        console.log('🗑️ Removiendo conocimiento');
        var container = $('#conocimientos-list');
        if (container.children().length > 1) {
            $(this).closest('.input-group').remove();
        } else {
            showAlert('Debe mantener al menos un campo de conocimiento', 'error');
        }
    });

    // Función para agregar funciones
    $('#add-funcion').click(function() {
        console.log('🔧 Agregando nueva función');
        $('#funciones-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Funciones[]" class="form-control" value="" required placeholder="Ingrese función">
                <button type="button" class="btn btn-danger remove-funcion">-</button>
            </div>
        `);
    });
    
    // Función para remover funciones
    $(document).on('click', '.remove-funcion', function() {
        console.log('🗑️ Removiendo función');
        var container = $('#funciones-list');
        if (container.children().length > 1) {
            $(this).closest('.input-group').remove();
        } else {
            showAlert('Debe mantener al menos un campo de función', 'error');
        }
    });

    // Función para agregar habilidades
    $('#add-habilidad').click(function() {
        console.log('🔧 Agregando nueva habilidad');
        $('#habilidades-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Habilidades[]" class="form-control" value="" required placeholder="Ingrese habilidad">
                <button type="button" class="btn btn-danger remove-habilidad">-</button>
            </div>
        `);
    });
    
    // Función para remover habilidades
    $(document).on('click', '.remove-habilidad', function() {
        console.log('🗑️ Removiendo habilidad');
        var container = $('#habilidades-list');
        if (container.children().length > 1) {
            $(this).closest('.input-group').remove();
        } else {
            showAlert('Debe mantener al menos un campo de habilidad', 'error');
        }
    });
    
    console.log('✅ Event listeners para campos dinámicos configurados');
    
    // Event listener específico para el botón cancelar
    $(document).on('click', '#btn-cancelar-puesto', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🔄 Botón cancelar clickeado');
        
        $.get("<?php echo e(route('puestos.index')); ?>", function(html) {
            $('#main-content-overlay').html(html);
            console.log('✅ Vista de puestos cargada');
        }).fail(function() {
            console.error('❌ Error al cargar la vista de puestos');
            showAlert('Error al cargar la página', 'error');
        });
    });
    
    // Event listener genérico para otros ajax-link si los hay
    $(document).on('click', '.ajax-link:not(#btn-cancelar-puesto)', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('🔄 Cargando vista:', url);
        
        $.get(url, function(html) {
            $('#main-content-overlay').html(html);
            console.log('✅ Vista cargada correctamente');
        }).fail(function() {
            console.error('❌ Error al cargar la vista');
            showAlert('Error al cargar la página', 'error');
        });
    });
});
</script><?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/create_puesto.blade.php ENDPATH**/ ?>