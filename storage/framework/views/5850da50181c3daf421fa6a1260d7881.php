<!-- filepath: resources/views/puestos/form_puesto_ajax.blade.php -->
<div style="width: 100%; max-width: 700px; margin: 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 28px; color: #FE7743;">Crear Puesto</h2>
    <form id="form-crear-puesto" action="<?php echo e(route('puestos.store')); ?>" method="POST" autocomplete="off">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="from_modal" value="1">

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
            <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                <option value="" disabled <?php echo e(old('id_GiroPuestoFK') ? '' : 'selected'); ?>>Seleccione un giro</option>
                <?php $__currentLoopData = $giros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $giro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($giro->idGiros); ?>" <?php echo e(old('id_GiroPuestoFK') == $giro->idGiros ? 'selected' : ''); ?>>
                        <?php echo e($giro->Nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="margin-top: 18px;">
            <label>Cliente:</label>
            <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                <option value="" disabled <?php echo e(old('id_ClientePuestoFK') ? '' : 'selected'); ?>>Seleccione un cliente</option>
                <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cliente->idClientes); ?>" <?php echo e(old('id_ClientePuestoFK') == $cliente->idClientes ? 'selected' : ''); ?>>
                        <?php echo e($cliente->Nombre); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Ruta:</label>
                <input type="text" name="Zona" value="<?php echo e(old('Zona')); ?>" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Estado (Zona):</label>
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
            <label>Conocimientos:</label>
            <div id="conocimientos-list-modal">
                <div class="input-group mb-2">
                    <input type="text" name="Conocimientos[]" class="form-control" value="" placeholder="Ingrese conocimiento">
                    <button type="button" class="btn btn-danger remove-conocimiento-ajax">-</button>
                </div>
            </div>
            <button type="button" id="add-conocimiento-ajax" class="btn btn-primary btn-sm mb-3">Agregar Conocimiento</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Funciones:</label>
            <div id="funciones-list-modal">
                <div class="input-group mb-2">
                    <input type="text" name="Funciones[]" class="form-control" value="" placeholder="Ingrese función">
                    <button type="button" class="btn btn-danger remove-funcion-ajax">-</button>
                </div>
            </div>
            <button type="button" id="add-funcion-ajax" class="btn btn-primary btn-sm mb-3">Agregar Función</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Habilidades:</label>
            <div id="habilidades-list-modal">
                <div class="input-group mb-2">
                    <input type="text" name="Habilidades[]" class="form-control" value="" placeholder="Ingrese habilidad">
                    <button type="button" class="btn btn-danger remove-habilidad-ajax">-</button>
                </div>
            </div>
            <button type="button" id="add-habilidad-ajax" class="btn btn-primary btn-sm mb-3">Agregar Habilidad</button>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <button type="submit" style="padding: 8px 20px; background: #FE7743; color: #fff; border: none; border-radius: 4px;">Guardar</button>
        </div>
    </form>
</div>

<script>
// JavaScript específico para el modal de puestos
$(document).ready(function() {
    console.log('🔧 Configurando campos dinámicos del modal de puestos');
    
    // Función para agregar conocimientos en el modal
    $('#add-conocimiento-ajax').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('📝 Agregando conocimiento en modal AJAX');
        $('#conocimientos-list-modal').append(`
            <div class="input-group mb-2">
                <input type="text" name="Conocimientos[]" class="form-control" value="" placeholder="Ingrese conocimiento">
                <button type="button" class="btn btn-danger remove-conocimiento-ajax">-</button>
            </div>
        `);
    });
    
    // Función para remover conocimientos en el modal - Usar event delegation específico
    $('#conocimientos-list-modal').off('click', '.remove-conocimiento-ajax').on('click', '.remove-conocimiento-ajax', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🗑️ Removiendo conocimiento del modal AJAX');
        // No permitir remover si es el último campo
        if ($('#conocimientos-list-modal .input-group').length > 1) {
            $(this).closest('.input-group').remove();
        }
    });

    // Función para agregar funciones en el modal
    $('#add-funcion-ajax').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('📝 Agregando función en modal AJAX');
        $('#funciones-list-modal').append(`
            <div class="input-group mb-2">
                <input type="text" name="Funciones[]" class="form-control" value="" placeholder="Ingrese función">
                <button type="button" class="btn btn-danger remove-funcion-ajax">-</button>
            </div>
        `);
    });
    
    // Función para remover funciones en el modal - Usar event delegation específico
    $('#funciones-list-modal').off('click', '.remove-funcion-ajax').on('click', '.remove-funcion-ajax', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🗑️ Removiendo función del modal AJAX');
        // No permitir remover si es el último campo
        if ($('#funciones-list-modal .input-group').length > 1) {
            $(this).closest('.input-group').remove();
        }
    });

    // Función para agregar habilidades en el modal
    $('#add-habilidad-ajax').off('click').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('📝 Agregando habilidad en modal AJAX');
        $('#habilidades-list-modal').append(`
            <div class="input-group mb-2">
                <input type="text" name="Habilidades[]" class="form-control" value="" placeholder="Ingrese habilidad">
                <button type="button" class="btn btn-danger remove-habilidad-ajax">-</button>
            </div>
        `);
    });
    
    // Función para remover habilidades en el modal - Usar event delegation específico
    $('#habilidades-list-modal').off('click', '.remove-habilidad-ajax').on('click', '.remove-habilidad-ajax', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🗑️ Removiendo habilidad del modal AJAX');
        // No permitir remover si es el último campo
        if ($('#habilidades-list-modal .input-group').length > 1) {
            $(this).closest('.input-group').remove();
        }
    });
    
    // Validación al enviar el formulario del modal
    $('#form-crear-puesto').off('submit').on('submit', function(e) {
        // Validar conocimientos
        var conocimientosCompletos = 0;
        $(this).find('input[name="Conocimientos[]"]').each(function() {
            if ($.trim($(this).val()) !== '') {
                conocimientosCompletos++;
            }
        });
        
        // Validar funciones
        var funcionesCompletas = 0;
        $(this).find('input[name="Funciones[]"]').each(function() {
            if ($.trim($(this).val()) !== '') {
                funcionesCompletas++;
            }
        });
        
        // Validar habilidades
        var habilidadesCompletas = 0;
        $(this).find('input[name="Habilidades[]"]').each(function() {
            if ($.trim($(this).val()) !== '') {
                habilidadesCompletas++;
            }
        });
        
        // Mostrar errores específicos si no hay campos llenos
        var errores = [];
        if (conocimientosCompletos === 0) {
            errores.push('❌ Debe agregar al menos un conocimiento');
        }
        if (funcionesCompletas === 0) {
            errores.push('❌ Debe agregar al menos una función');
        }
        if (habilidadesCompletas === 0) {
            errores.push('❌ Debe agregar al menos una habilidad');
        }
        
        if (errores.length > 0) {
            e.preventDefault();
            alert(errores[0]); // Mostrar el primer error
            return false;
        }
        
        // Si todo está bien, continuar con el envío
        console.log('✅ Formulario modal válido, enviando...');
    });
    
    // Asegurar que todos los inputs del formulario sean clickeables
    setTimeout(function() {
        $('#form-crear-puesto input, #form-crear-puesto select, #form-crear-puesto textarea').css({
            'pointer-events': 'auto',
            'user-select': 'text',
            'cursor': 'text'
        });
        
        $('#form-crear-puesto select').css({
            'cursor': 'pointer'
        });
        
        console.log('✅ Inputs del formulario habilitados correctamente');
    }, 100);
    
    console.log('✅ Event listeners del modal AJAX configurados correctamente');
});
</script>

<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/puestos/form_puesto_ajax.blade.php ENDPATH**/ ?>