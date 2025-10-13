{{-- filepath: resources/views/empleados/show_empleado.blade.php --}}

@if(!$empleado)
    <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
        No hay empleados registrados.
    </div>
@else
    <div style="max-width: 1000px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px; color: #3498db;">Detalle de Empleado</h2>
        <table style="width:100%; border-collapse: separate; border-spacing: 0 18px;">
            <tr>
                <td style="font-weight:bold;">Nombre:</td>
                <td>{{ $empleado->Nombre }}</td>
                <td style="font-weight:bold;">Apellido Paterno:</td>
                <td>{{ $empleado->Apellido_Paterno }}</td>
                <td style="font-weight:bold;">Apellido Materno:</td>
                <td>{{ $empleado->Apellido_Materno }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Edad:</td>
                <td>{{ $empleado->Edad }}</td>
                <td style="font-weight:bold;">Teléfono:</td>
                <td>{{ $empleado->Telefono }}</td>
                <td style="font-weight:bold;">Estado:</td>
                <td>{{ $empleado->Estado }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Ruta:</td>
                <td>{{ $empleado->Ruta }}</td>
                <td style="font-weight:bold;">Escolaridad:</td>
                <td>{{ $empleado->Escolaridad }}</td>
                <td style="font-weight:bold;">Correo:</td>
                <td>{{ $empleado->Correo }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Experiencia:</td>
                <td>{{ $empleado->Experiencia }}</td>
                <td style="font-weight:bold;">Fecha Ingreso:</td>
                <td>{{ $empleado->Fecha_Ingreso }}</td>
                <td style="font-weight:bold;">Fecha Egreso:</td>
                <td>{{ $empleado->Fecha_Egreso ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">CURP:</td>
                <td>{{ $empleado->Curp }}</td>
                <td style="font-weight:bold;">NSS:</td>
                <td>{{ $empleado->NSS }}</td>
                <td style="font-weight:bold;">RFC:</td>
                <td>{{ $empleado->RFC }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Código Postal:</td>
                <td>{{ $empleado->Codigo_Postal }}</td>
                <td style="font-weight:bold;">Folio:</td>
                <td>{{ $empleado->Folio }}</td>
                <td style="font-weight:bold;">No. Cuenta:</td>
                <td>{{ $empleado->No_Cuenta }}</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Tipo de Cuenta:</td>
                <td>{{ $empleado->Tipo_Cuenta }}</td>
                <td style="font-weight:bold;">Sueldo:</td>
                <td>{{ $empleado->Sueldo }}</td>
                <td style="font-weight:bold;">Puesto:</td>
                <td>{{ $empleado->puesto?->Puesto ?? 'Sin puesto' }}</td>
            </tr>
        </table>
        <div style="margin-top: 36px; text-align: right;">
            <a href="{{ route('empleados.index') }}" class="ajax-link" style="padding: 10px 24px; background: #3498db; color: #fff; border-radius: 4px; text-decoration: none;">Volver al listado</a>
        </div>
    </div>
@endif
