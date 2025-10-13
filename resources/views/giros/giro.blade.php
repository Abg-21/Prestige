<div style="width: 100%; max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
        
        @if(session('success'))
            <div class="alert-success" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
            <div style="display: flex; align-items: center;">
                <h2 style="margin-bottom: 0; margin-right: 24px; color: #FE7743; font-weight: bold; display: flex; align-items: center;">
                    Giros
                </h2>
                <a href="{{ route('giros.create') }}" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Crear Nuevo Giro">
                    @if (App\Helpers\PermissionHelper::hasPermission('giros', 'crear'))
                    <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
                    @endif
                </a>
            </div>
        </div>
        @if($giros->isEmpty())
            <div style="padding: 20px; color: #e74c3c; font-weight: bold; text-align: center;">
                No hay giros registrados.
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width:100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden;">
                    <thead>
                        <tr style="background: #FE7743; color: #fff;">
                            <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Nombre</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Descripción</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($giros as $giro)
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $giro->idGiros }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $giro->Nombre }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $giro->Descripcion }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                <div style="display: flex; gap: 18px; justify-content: center;">
                                    <a href="{{ route('giros.edit', ['giro' => $giro->idGiros]) }}" class="ajax-link" style="text-align: center;">
                                        <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                        <div style="font-size: 13px; color: #555;">Editar</div>
                                    </a>
                                    <button type="button"
                                        @if (App\Helpers\PermissionHelper::hasPermission('giros', 'eliminar'))
                                        <button type="button"
                                            class="btn-eliminar-giro"
                                            data-id="{{ $giro->idGiros }}"
                                            style="background: none; border: none; padding: 0; cursor: pointer; text-align: center;">
                                            <img src="{{ asset('images/eliminar.png') }}" alt="Eliminar" style="width: 32px; height: 32px;">
                                            <div style="font-size: 13px; color: #e74c3c;">Eliminar</div>
                                        </button>
                                        @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if ($errors->any())
        <div style="margin-top: 18px; color: #e67e22; background: #fffbe6; border-radius: 8px; padding: 16px; font-weight: bold;">
            {{ $errors->first() }}
            @if (session('giroIdError'))
                <button 
                    type="button" 
                    onclick="confirmDelete({{ session('giroIdError') }})"
                    style="margin-left: 12px; background: #FE7743; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; cursor: pointer;">
                    Proceder de todas formas
                </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Modal de confirmación de eliminación -->
<div id="modal-confirmar-eliminar-giro" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:32px 24px; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.2); text-align:center; min-width:300px;">
        <div style="font-size:18px; margin-bottom:24px;">¿Estás seguro de eliminar este giro?</div>
        <button id="btn-confirmar-eliminar-giro" style="padding:8px 24px; background:#e74c3c; color:#fff; border:none; border-radius:4px; margin-right:12px; cursor:pointer;">Sí, eliminar</button>
        <button id="btn-cancelar-eliminar-giro" style="padding:8px 24px; background:#447D9B; color: white; border:none; border-radius:4px; cursor:pointer;">Cancelar</button>
    </div>
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

    var formEliminarPendiente = null;
var giroIdPendiente = null;

// Mostrar modal al intentar eliminar
$(document).on('click', '.btn-eliminar-giro', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    // Limpiar cualquier eliminación pendiente anterior
    giroIdPendiente = null;
    
    giroIdPendiente = $(this).data('id');
    console.log('🔍 Preparando eliminación de giro ID:', giroIdPendiente);
    
    // Solo mostrar modal si tenemos un ID válido
    if (giroIdPendiente) {
        $('#modal-confirmar-eliminar-giro').fadeIn(150).css('display', 'flex');
    } else {
        console.error('❌ No se pudo obtener ID del giro');
    }
    
    return false;
});

// Confirmar eliminación
$(document).on('click', '#btn-confirmar-eliminar-giro', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    if (!giroIdPendiente) {
        console.log('⚠️ No hay giro pendiente para eliminar');
        return false;
    }
    
    console.log('🗑️ Eliminando giro ID:', giroIdPendiente);
    
    $.ajax({
        url: "{{ url('giros') }}/" + giroIdPendiente,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
        },
        success: function(response) {
            console.log('✅ Respuesta del servidor:', response);
            if (response.success) {
                showAlert(response.message || 'Giro eliminado correctamente', 'success');
                setTimeout(function() {
                    $.get("{{ route('giros.index') }}", function(html) {
                        $('#main-content-overlay').html(html);
                    });
                }, 1000);
            } else {
                showAlert(response.message || 'Error al eliminar el giro', 'error');
            }
            $('#modal-confirmar-eliminar-giro').fadeOut(150);
        },
        error: function(xhr) {
            console.error('❌ Error en la eliminación:', xhr);
            
            if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.confirm) {
                $('#modal-confirmar-eliminar-giro').fadeOut(150);
                if (confirm(xhr.responseJSON.message)) {
                    $.ajax({
                        url: "{{ url('giros') }}/" + giroIdPendiente,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE',
                            force: true
                        },
                        success: function(response) {
                            console.log('✅ Respuesta forzada del servidor:', response);
                            if (response.success) {
                                showAlert(response.message || 'Giro eliminado correctamente', 'success');
                                setTimeout(function() {
                                    $.get("{{ route('giros.index') }}", function(html) {
                                        $('#main-content-overlay').html(html);
                                    });
                                }, 1000);
                            } else {
                                showAlert(response.message || 'Error al eliminar el giro', 'error');
                            }
                        },
                        error: function(xhr2) {
                            console.error('❌ Error en eliminación forzada:', xhr2);
                            var errorMsg = 'Hubo un error al intentar eliminar el giro';
                            if (xhr2.responseJSON && xhr2.responseJSON.message) {
                                errorMsg = xhr2.responseJSON.message;
                            }
                            showAlert(errorMsg, 'error');
                        }
                    });
                }
            } else {
                var errorMsg = 'Hubo un error al intentar eliminar el giro';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch (e) {
                        // Si no se puede parsear, mantener mensaje por defecto
                    }
                }
                showAlert(errorMsg, 'error');
                $('#modal-confirmar-eliminar-giro').fadeOut(150);
            }
        }
    });
    
    giroIdPendiente = null;
});

// Cancelar eliminación
$(document).on('click', '#btn-cancelar-eliminar-giro', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    console.log('❌ Cancelando eliminación de giro');
    
    $('#modal-confirmar-eliminar-giro').fadeOut(150);
    giroIdPendiente = null;
    
    return false;
});
    </script>
