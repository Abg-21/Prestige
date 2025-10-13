<div style="padding: 20px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <h2 style="margin-bottom: 20px; margin-right: 24px; display: flex; align-items: center;">
                Empleados
            </h2>
            @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'crear'))
            <a href="{{ route('empleados.create') }}" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Agregar empleado">
                <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
            </a>
            @endif
        </div>
    </div>
    @if($empleados->isEmpty())
        <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
            No hay empleados registrados.
        </div>
    @else
        <table style="width:100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #FE7743; color: #fff;">
                    <th style="padding: 8px; border: 1px solid #ddd;">Nombre</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Apellido Paterno</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Apellido Materno</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Zona</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Puesto</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Email</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Nombre }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Apellido_P }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Apellido_M }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Ciudad }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->puesto->Puesto}}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Correo }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <div style="display: flex; gap: 24px;">
                                <a href="{{ route('empleados.show', $empleado->idEmpleado) }}" class="ajax-link" style="text-align: center;">
                                    <img src="{{ asset('images/ver.png') }}" alt="Ver" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Ver</div>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado->idEmpleado) }}" class="ajax-link" style="text-align: center;">
                                    <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Editar</div>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline-block; text-align: center;">
                                    @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'eliminar'))
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar al empleado?')" style="background: none; border: none; padding: 0; cursor: pointer;">
                                        <img src="{{ asset('images/eliminar.png') }}" alt="Eliminar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                    </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
