<div style="padding: 20px;">
    <!-- Mensaje de éxito temporal -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h2 style="margin-bottom: 20px; display: flex; align-items: center;">
            Empleados
            @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'crear'))
            <a href="{{ route('empleados.create') }}" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Agregar empleado">
                <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
            </a>
            @endif
        </h2>
    </div>

    <!-- Tabla de empleados -->
    <table style="width:100%; border-collapse: collapse; background: #fff;">
        <thead>
            <tr style="background: #FE7743; color: #fff;">
                <th style="padding: 8px; border: 1px solid #ddd;">Nombre</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Apellido Paterno</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Apellido Materno</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Estado (Zona)</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Fecha Ingreso</th>
                <th style="padding: 8px; border: 1px solid #ddd; width: 250px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($empleados as $empleado)
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Nombre }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Apellido_Paterno }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Apellido_Materno }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Estado ?? 'N/A' }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $empleado->Fecha_Ingreso ?? 'N/A' }}</td>
                        <td class="text-center">
                            <div style="display: flex; justify-content: space-around; gap: 12px;">
                                @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'ver'))
                                <a href="{{ route('empleados.show', $empleado->IdEmpleados) }}" 
                                   class="ajax-link"
                                   style="background: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                                    <img src="{{ asset('images/ver.png') }}" alt="Ver" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #17a2b8; margin-top: 4px;">Ver</div>
                                </a>
                                @endif
                                @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'editar'))
                                <a href="{{ route('empleados.edit', $empleado->IdEmpleados) }}" 
                                   class="ajax-link"
                                   style="background: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-decoration: none;">
                                    <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #FFC107; margin-top: 4px;">Editar</div>
                                </a>
                                @endif
                                @if (App\Helpers\PermissionHelper::hasPermission('empleados', 'eliminar'))
                                <button type="button" 
                                        onclick="confirmarDesactivacion({{ $empleado->IdEmpleados }}, '{{ addslashes($empleado->Nombre . ' ' . $empleado->Apellido_Paterno) }}')"
                                        style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                                    <img src="{{ asset('images/eliminar.png') }}" alt="Desactivar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #e74c3c; margin-top: 4px;">Desactivar</div>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #999;">
                            No hay empleados registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Funciones JavaScript para enviar solicitudes AJAX -->
<script>
    function confirmarDesactivacion(id, nombre) {
        if (confirm(`¿Estás seguro de desactivar a ${nombre}? Esta acción moverá al empleado al módulo de eliminados y se registrará automáticamente la fecha y hora de egreso.`)) {
            // Mostrar que se está procesando
            mostrarNotificacion(`Procesando desactivación...`, 'info');
            
            $.ajax({
                url: `/empleados/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Reemplazar el contenido actual con la respuesta
                    $('#main-content-overlay').html(response);
                    
                    // Mostrar mensaje de éxito
                    mostrarNotificacion(`${nombre} ha sido desactivado correctamente`);
                },
                error: function(xhr, status, error) {
                    // Mostrar información detallada del error
                    console.error("Error al desactivar empleado:", xhr);
                    console.error("Status:", status);
                    console.error("Error:", error);
                    console.error("Response Text:", xhr.responseText);
                    
                    let errorMsg = "Error al procesar la desactivación";
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ": " + xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg += ": " + response.message;
                            }
                        } catch (e) {
                            errorMsg += ". Detalles en consola.";
                        }
                    }
                    
                    mostrarNotificacion(errorMsg, "error");
                }
            });
        }
    }
    
    function mostrarNotificacion(mensaje, tipo = 'success') {
        // Crear el elemento de notificación si no existe
        if (!$('#notificacion-temporal').length) {
            $('body').append(`
                <div id="notificacion-temporal" style="position: fixed; bottom: 20px; right: 20px; 
                     padding: 15px 25px; border-radius: 4px; z-index: 9999; display: none; color: white;
                     box-shadow: 0 4px 8px rgba(0,0,0,0.2); font-weight: bold;">
                </div>
            `);
        }
        
        // Establecer el color según el tipo
        let color;
        switch(tipo) {
            case 'success': color = '#4CAF50'; break;
            case 'error': color = '#f44336'; break;
            case 'info': color = '#2196F3'; break;
            default: color = '#4CAF50';
        }
        
        // Configurar y mostrar la notificación
        $('#notificacion-temporal')
            .css('background-color', color)
            .text(mensaje)
            .fadeIn(300);
        
        // Ocultar después de 3 segundos
        setTimeout(function() {
            $('#notificacion-temporal').fadeOut(300);
        }, 3000);
    }
</script>
