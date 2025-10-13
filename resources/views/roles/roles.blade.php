<div style="padding: 20px;">
    <h2 style="margin-bottom: 20px; color: rgb(0, 0, 0);">Gestión de Roles
        @if (App\Helpers\PermissionHelper::hasPermission('roles', 'crear'))
        <a href="{{ route('roles.create') }}" class="btn btn-primary ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center; background-color: #C57F1B; border-color: #C57F1B;" title="Crear nuevo rol">
            <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
        </a>
        @endif
    </h2>
    
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #fff3cd; color: #856404;">
            {{ session('error') }}
        </div>
    @endif

    @if($roles->isEmpty())
        <div style="padding: 20px; color: #6c757d; font-style: italic; text-align: center;">
            No hay roles registrados.
        </div>
    @else
        <table style="width:100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #447D9B; color: #fff;">
                    <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Nombre</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Descripción</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Usuarios Asignados</th>
                    <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $rol)
                    <tr>
                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $rol->id }}</td>
                        <td style="padding: 12px; border: 1px solid #ddd;">
                            <strong>{{ $rol->nombre }}</strong>
                        </td>
                        <td style="padding: 12px; border: 1px solid #ddd;">{{ $rol->descripcion }}</td>
                        <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                            @if($rol->usuarios && $rol->usuarios->count() > 0)
                                <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                                    {{ $rol->usuarios->count() }} usuario(s)
                                </span>
                            @else
                                <span style="color: #6c757d; font-style: italic;">Sin usuarios</span>
                            @endif
                        </td>
                        <td style="padding: 12px; border: 1px solid #ddd;">
                            <div style="display: flex; gap: 24px; justify-content: center;">
                                <a href="{{ route('roles.edit', $rol->id) }}" class="ajax-link" style="text-align: center;">
                                    <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Editar</div>
                                </a>
                                @if($rol->usuarios->count() == 0)
                                    @if (App\Helpers\PermissionHelper::hasPermission('roles', 'eliminar'))
                                    <button type="button" class="btn-eliminar-rol" data-id="{{ $rol->id }}" style="background: none; border: none; padding: 0; cursor: pointer; text-align: center;">
                                        <img src="{{ asset('images/eliminar.png') }}" alt="Eliminar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                    </button>
                                    @endif
                                @else
                                    <div style="text-align: center; opacity: 0.5;" title="No se puede eliminar porque tiene usuarios asignados">
                                        <img src="{{ asset('images/eliminar.png') }}" alt="No disponible" style="width: 32px; height: 32px; filter: grayscale(100%);">
                                        <div style="font-size: 13px; color: #6c757d;">No disponible</div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
$(document).on('click', '.btn-eliminar-rol', function(e) {
    e.preventDefault();
    var rolId = $(this).data('id');
    
    if (confirm('¿Estás seguro de que deseas eliminar este rol?')) {
        // Crear formulario para enviar DELETE
        var form = $('<form>', {
            'method': 'POST',
            'action': '/roles/' + rolId
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_method',
            'value': 'DELETE'
        }));
        
        $('body').append(form);
        form.submit();
    }
});
</script>