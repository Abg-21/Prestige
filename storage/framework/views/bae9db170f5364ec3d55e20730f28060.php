<div style="max-width: 900px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 32px; color: #FE7743;">Editar Empleado</h2>
    <form id="form-editar-empleado" action="<?php echo e(route('empleados.update', $empleado->IdEmpleados)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <table style="width:100%; border-collapse: separate; border-spacing: 0 18px;">
            <tr>
                <td style="font-weight:bold; width: 18%;">Nombre:</td>
                <td style="width: 32%;">
                    <input type="text" name="Nombre" value="<?php echo e($empleado->Nombre); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold; width: 18%;">Apellido Paterno:</td>
                <td style="width: 32%;">
                    <input type="text" name="Apellido_Paterno" value="<?php echo e($empleado->Apellido_Paterno); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Apellido Materno:</td>
                <td>
                    <input type="text" name="Apellido_Materno" value="<?php echo e($empleado->Apellido_Materno); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Edad:</td>
                <td>
                    <input type="number" name="Edad" value="<?php echo e($empleado->Edad); ?>" min="18" max="99" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Teléfono:</td>
                <td>
                    <input type="text" name="Telefono" value="<?php echo e($empleado->Telefono); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Estado:</td>
                <td>
                    <select name="Estado" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($estado); ?>" <?php echo e($empleado->Estado == $estado ? 'selected' : ''); ?>><?php echo e($estado); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Ruta:</td>
                <td>
                    <input type="text" name="Ruta" value="<?php echo e($empleado->Ruta); ?>" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Escolaridad:</td>
                <td>
                    <select name="Escolaridad" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = ['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $esc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($esc); ?>" <?php echo e($empleado->Escolaridad == $esc ? 'selected' : ''); ?>><?php echo e($esc); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Correo:</td>
                <td>
                    <input type="email" name="Correo" value="<?php echo e($empleado->Correo); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Experiencia:</td>
                <td>
                    <input type="text" name="Experiencia" value="<?php echo e($empleado->Experiencia); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Fecha Ingreso:</td>
                <td>
                    <input type="date" name="Fecha_Ingreso" value="<?php echo e($empleado->Fecha_Ingreso ? $empleado->Fecha_Ingreso->format('Y-m-d') : ''); ?>" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Fecha Egreso:</td>
                <td>
                    <input type="date" name="Fecha_Egreso" value="<?php echo e($empleado->Fecha_Egreso ? $empleado->Fecha_Egreso->format('Y-m-d') : ''); ?>" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">CURP:</td>
                <td>
                    <input type="text" name="Curp" value="<?php echo e($empleado->Curp); ?>" maxlength="18" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">NSS:</td>
                <td>
                    <input type="text" name="NSS" value="<?php echo e($empleado->NSS); ?>" maxlength="11" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">RFC:</td>
                <td>
                    <input type="text" name="RFC" value="<?php echo e($empleado->RFC); ?>" maxlength="13" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Código Postal:</td>
                <td>
                    <input type="text" name="Codigo_Postal" value="<?php echo e($empleado->Codigo_Postal); ?>" maxlength="5" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Folio:</td>
                <td>
                    <input type="text" name="Folio" value="<?php echo e($empleado->Folio); ?>" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Código:</td>
                <td>
                    <input type="text" name="Codigo" value="<?php echo e($empleado->Codigo); ?>" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f8f9fa;" placeholder="Código único">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">No. Cuenta:</td>
                <td>
                    <input type="text" name="No_Cuenta" value="<?php echo e($empleado->No_Cuenta); ?>" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Tipo de Cuenta:</td>
                <td>
                    <input type="text" name="Tipo_Cuenta" value="<?php echo e($empleado->Tipo_Cuenta); ?>" maxlength="15" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Sueldo:</td>
                <td>
                    <input type="number" name="Sueldo" value="<?php echo e($empleado->Sueldo); ?>" step="0.01" min="0" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Puesto:</td>
                <td colspan="3">
                    <select name="IdPuestoEmpleadoFK" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($puesto->idPuestos); ?>" <?php echo e($empleado->IdPuestoEmpleadoFK == $puesto->idPuestos ? 'selected' : ''); ?>><?php echo e($puesto->Puesto); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
        </table>
        <div style="margin-top: 36px; text-align: right;">
            <button type="button" onclick="cargarEmpleados()" 
                    style="padding: 10px 24px; background: #e74c3c; color: #fff; border: none; border-radius: 4px; margin-right: 10px;">
                Cancelar
            </button>
            <button type="submit" 
                    style="padding: 10px 24px; background: #27ae60; color: #fff; border: none; border-radius: 4px;">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
<script>
function cargarEmpleados() {
    $.ajax({
        url: '<?php echo e(route('empleados.index')); ?>',
        type: 'GET',
        success: function(response) {
            $('#main-content-overlay').html(response);
        },
        error: function(xhr) {
            alert('Error al cargar la lista de empleados');
        }
    });
}

$('#form-editar-empleado').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    
    // Mostrar que se está procesando
    $('button[type="submit"]').prop('disabled', true).text('Guardando...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            $('#main-content-overlay').html(response);
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar empleado:', xhr);
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            
            let errorMsg = 'Error al actualizar el empleado';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                errorMsg += ':\n' + errors.join('\n');
            } else if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMsg += ': ' + response.message;
                    }
                } catch (e) {
                    errorMsg += '. Ver consola para más detalles.';
                }
            }
            
            alert(errorMsg);
            
            // Restaurar botón
            $('button[type="submit"]').prop('disabled', false).text('Guardar cambios');
        }
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/empleados/edit_empleado.blade.php ENDPATH**/ ?>