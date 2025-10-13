<div style="padding: 20px;">
    <!-- Mensaje de éxito temporal -->
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between;">
        <h2 style="margin-bottom: 20px; display: flex; align-items: center;">
            Candidatos
            @if (App\Helpers\PermissionHelper::hasPermission('candidatos', 'crear'))
            <a href="{{ route('candidatos.create') }}" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Agregar candidato">
                <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
            </a>
            @endif
        </h2>
    </div>

    <table style="width:100%; border-collapse: collapse; background: #fff;">
        <thead>
            <tr style="background: #FE7743; color: #fff;">
                <th style="padding: 8px; border: 1px solid #ddd;">Nombre</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Apellido Paterno</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Apellido Materno</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Edad</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Estado</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Puesto</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Fecha Postulación</th>
                <th style="padding: 8px; border: 1px solid #ddd; width: 250px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidatos as $candidato)
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Nombre }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Apellido_P }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Apellido_M }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Edad }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Estado }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->puesto->Puesto ?? 'Sin asignar' }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $candidato->Fecha_Postulacion ? $candidato->Fecha_Postulacion->format('d/m/Y') : 'N/A' }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        <div style="display: flex; justify-content: space-around; gap: 12px;">
                            @if (App\Helpers\PermissionHelper::hasPermission('candidatos', 'aprobar'))
                            <button 
                                type="button" 
                                onclick="confirmarAprobacion({{ $candidato->idCandidatos }}, '{{ $candidato->Nombre }} {{ $candidato->Apellido_P }}')"
                                style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('images/aprobar.png') }}" alt="Aprobar" style="width: 32px; height: 32px;">
                                <div style="font-size: 13px; color: #4CAF50; margin-top: 4px;">Aprobar</div>
                            </button>
                            @endif
                            @if (App\Helpers\PermissionHelper::hasPermission('candidatos', 'rechazar'))
                            <button 
                                type="button" 
                                onclick="confirmarRechazo({{ $candidato->idCandidatos }}, '{{ $candidato->Nombre }} {{ $candidato->Apellido_P }}')"
                                style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('images/denegar.png') }}" alt="Rechazar" style="width: 32px; height: 32px;">
                                <div style="font-size: 13px; color: #f44336; margin-top: 4px;">Rechazar</div>
                            </button>
                            @endif
                            <a 
                                href="{{ route('candidatos.edit', $candidato->idCandidatos) }}" 
                                class="ajax-link"
                                style="background: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center; text-decoration: none;"
                                @if(!App\Helpers\PermissionHelper::hasPermission('candidatos', 'editar')) disabled @endif>
                                <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                <div style="font-size: 13px; color: #FFC107; margin-top: 4px;">Editar</div>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Funciones JavaScript para enviar solicitudes AJAX -->
<script>
    function confirmarAprobacion(id, nombre) {
        if (confirm(`¿Estás seguro de convertir a ${nombre} en empleado?`)) {
            // Mostrar que se está procesando
            mostrarNotificacion(`Procesando...`, 'info');
            
            $.ajax({
                url: `{{ url('/candidatos') }}/${id}/approve`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Reemplazar el contenido actual con la respuesta
                    $('#main-content-overlay').html(response);
                    
                    // Mostrar mensaje de éxito
                    mostrarNotificacion(`${nombre} ahora es un empleado`);
                },
                error: function(xhr, status, error) {
                    // Mostrar información detallada del error
                    console.error("Error al aprobar candidato:", xhr.responseText);
                    let errorMsg = "Error al procesar la solicitud";
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ": " + xhr.responseJSON.message;
                    }
                    
                    mostrarNotificacion(errorMsg, "error");
                }
            });
        }
    }
    
    function confirmarRechazo(id, nombre) {
        if (confirm(`¿Estás seguro de rechazar a ${nombre}?`)) {
            // Mostrar que se está procesando
            mostrarNotificacion(`Procesando...`, 'info');
            
            $.ajax({
                url: `{{ route('candidatos.reject', '') }}/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Reemplazar el contenido actual con la respuesta
                    $('#main-content-overlay').html(response);
                    
                    // Mostrar mensaje de éxito
                    mostrarNotificacion(`${nombre} ha sido rechazado`);
                },
                error: function(xhr, status, error) {
                    // Mostrar información detallada del error
                    console.error("Error al rechazar candidato:", xhr.responseText);
                    let errorMsg = "Error al procesar la solicitud";
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += ": " + xhr.responseJSON.message;
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