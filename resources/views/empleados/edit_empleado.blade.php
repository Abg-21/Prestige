<div style="max-width: 900px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 32px; color: #FE7743;">Editar Empleado</h2>
    <form id="form-editar-empleado" action="{{ route('empleados.update', $empleado->IdEmpleados) }}" method="POST">
        @csrf
        @method('PUT')
        <table style="width:100%; border-collapse: separate; border-spacing: 0 18px;">
            <tr>
                <td style="font-weight:bold; width: 18%;">Nombre:</td>
                <td style="width: 32%;">
                    <input type="text" name="Nombre" value="{{ $empleado->Nombre }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold; width: 18%;">Apellido Paterno:</td>
                <td style="width: 32%;">
                    <input type="text" name="Apellido_Paterno" value="{{ $empleado->Apellido_Paterno }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Apellido Materno:</td>
                <td>
                    <input type="text" name="Apellido_Materno" value="{{ $empleado->Apellido_Materno }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Edad:</td>
                <td>
                    <input type="number" name="Edad" value="{{ $empleado->Edad }}" min="18" max="99" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Teléfono:</td>
                <td>
                    <input type="text" name="Telefono" value="{{ $empleado->Telefono }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Estado:</td>
                <td>
                    <select name="Estado" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach(['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'] as $estado)
                            <option value="{{ $estado }}" {{ $empleado->Estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Ruta:</td>
                <td>
                    <input type="text" name="Ruta" value="{{ $empleado->Ruta }}" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Escolaridad:</td>
                <td>
                    <select name="Escolaridad" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach(['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $esc)
                            <option value="{{ $esc }}" {{ $empleado->Escolaridad == $esc ? 'selected' : '' }}>{{ $esc }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Correo:</td>
                <td>
                    <input type="email" name="Correo" value="{{ $empleado->Correo }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Experiencia:</td>
                <td>
                    <input type="text" name="Experiencia" value="{{ $empleado->Experiencia }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Fecha Ingreso:</td>
                <td>
                    <input type="date" name="Fecha_Ingreso" value="{{ $empleado->Fecha_Ingreso ? $empleado->Fecha_Ingreso->format('Y-m-d') : '' }}" required style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Fecha Egreso:</td>
                <td>
                    <input type="date" name="Fecha_Egreso" value="{{ $empleado->Fecha_Egreso ? $empleado->Fecha_Egreso->format('Y-m-d') : '' }}" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">CURP:</td>
                <td>
                    <input type="text" name="Curp" value="{{ $empleado->Curp }}" maxlength="18" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">NSS:</td>
                <td>
                    <input type="text" name="NSS" value="{{ $empleado->NSS }}" maxlength="11" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">RFC:</td>
                <td>
                    <input type="text" name="RFC" value="{{ $empleado->RFC }}" maxlength="13" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Código Postal:</td>
                <td>
                    <input type="text" name="Codigo_Postal" value="{{ $empleado->Codigo_Postal }}" maxlength="5" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Folio:</td>
                <td>
                    <input type="text" name="Folio" value="{{ $empleado->Folio }}" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">No. Cuenta:</td>
                <td>
                    <input type="text" name="No_Cuenta" value="{{ $empleado->No_Cuenta }}" maxlength="10" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Tipo de Cuenta:</td>
                <td>
                    <input type="text" name="Tipo_Cuenta" value="{{ $empleado->Tipo_Cuenta }}" maxlength="15" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
                <td style="font-weight:bold;">Sueldo:</td>
                <td>
                    <input type="number" name="Sueldo" value="{{ $empleado->Sueldo }}" step="0.01" min="0" style="width: 95%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Puesto:</td>
                <td colspan="3">
                    <select name="IdPuestoEmpleadoFK" required style="width: 98%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach($puestos as $puesto)
                            <option value="{{ $puesto->idPuestos }}" {{ $empleado->IdPuestoEmpleadoFK == $puesto->idPuestos ? 'selected' : '' }}>{{ $puesto->Puesto }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
        <div style="margin-top: 36px; text-align: right;">
            <button type="button" onclick="window.history.back()" class="ajax-link" 
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
$('#form-editar-empleado').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            alert('Empleado actualizado exitosamente');
            $('.ajax-link[href="{{ route('empleados.index') }}"]').click();
        },
        error: function(xhr) {
            alert('Error al actualizar el empleado');
        }
    });
});
</script>
