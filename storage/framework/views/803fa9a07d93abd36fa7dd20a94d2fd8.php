<div style="max-width: 900px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 32px; color: #FE7743;">Registrar Empleado</h2>
    <form id="form-crear-empleado" action="<?php echo e(route('empleados.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <table style="width:100%; border-collapse: separate; border-spacing: 0 18px;">
            <tr>
                <td style="font-weight:bold; width: 18%;">Nombre:</td>
                <td style="width: 32%;">
                    <input type="text" name="Nombre" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold; width: 18%;">Apellido Paterno:</td>
                <td style="width: 32%;">
                    <input type="text" name="Apellido_Paterno" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Apellido Materno:</td>
                <td>
                    <input type="text" name="Apellido_Materno" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Edad:</td>
                <td>
                    <input type="number" name="Edad" min="18" max="99" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Teléfono:</td>
                <td>
                    <input type="text" name="Telefono" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Estado:</td>
                <td>
                    <select name="Estado" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($estado); ?>"><?php echo e($estado); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Ruta:</td>
                <td>
                    <input type="text" name="Ruta" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Escolaridad:</td>
                <td>
                    <select name="Escolaridad" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = ['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $esc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($esc); ?>"><?php echo e($esc); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Correo:</td>
                <td>
                    <input type="email" name="Correo" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Experiencia:</td>
                <td>
                    <input type="text" name="Experiencia" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Fecha Ingreso:</td>
                <td>
                    <input type="date" name="Fecha_Ingreso" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Fecha Egreso:</td>
                <td>
                    <input type="date" name="Fecha_Egreso" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">CURP:</td>
                <td>
                    <input type="text" name="Curp" maxlength="18" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">NSS:</td>
                <td>
                    <input type="text" name="NSS" maxlength="11" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">RFC:</td>
                <td>
                    <input type="text" name="RFC" maxlength="13" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Código Postal:</td>
                <td>
                    <input type="text" name="Codigo_Postal" maxlength="5" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Folio:</td>
                <td>
                    <input type="text" name="Folio" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Código:</td>
                <td>
                    <input type="text" name="Codigo" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background-color: #f8f9fa;" placeholder="Código único">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">No. Cuenta:</td>
                <td>
                    <input type="text" name="No_Cuenta" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Tipo de Cuenta:</td>
                <td>
                    <input type="text" name="Tipo_Cuenta" maxlength="15" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Sueldo:</td>
                <td>
                    <input type="number" name="Sueldo" step="0.01" min="0" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Puesto:</td>
                <td colspan="3">
                    <select name="IdPuestoEmpleadoFK" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($puesto->idPuestos); ?>"><?php echo e($puesto->Puesto); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </td>
            </tr>
        </table>
        <div style="margin-top: 36px; text-align: right; display: flex; gap: 12px; justify-content: flex-end;">
            <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-danger ajax-link" style="padding: 10px 28px; background: #e74c3c; color: #fff; border-radius: 4px; text-decoration: none; font-size: 16px;">Cancelar</a>
            <button type="submit" style="padding: 10px 28px; background: #FE7743; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">Registrar</button>
        </div>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\Prestige\resources\views/empleados/create_empleado.blade.php ENDPATH**/ ?>