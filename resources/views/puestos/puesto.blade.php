<div style="padding: 20px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <h2 style="margin-bottom: 20px; margin-right: 24px; display: flex; align-items: center;">
                Puestos
            </h2>
            <a href="{{ route('puestos.create') }}" class="ajax-link" style="margin-left: 15px; display: inline-flex; align-items: center;" title="Agregar un puesto">
                @if (App\Helpers\PermissionHelper::hasPermission('puestos', 'crear'))
                <img src="{{ asset('images/agregar-usuario.png') }}" alt="Agregar" style="width: 28px; height: 28px; vertical-align: middle;">
                @endif
            </a>
        </div>
    </div>
    @if($puestos->isEmpty())
        <div style="padding: 20px; color: #e74c3c; font-weight: bold;">
            No hay puestos registrados.
        </div>
    @else
        <table style="width:100%; border-collapse: collapse; background: #fff;">
            <thead>
                <tr style="background: #447D9B; color: #fff;">
                    <th style="padding: 8px; border: 1px solid #ddd;">ID</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Categoría</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Puesto</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Giro</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Cliente</th>
                    <th style="padding: 8px; border: 1px solid #ddd;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($puestos as $puesto)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $puesto->idPuestos }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $puesto->Categoría }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $puesto->Puesto }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $puesto->giro ? $puesto->giro->Nombre : 'Sin giro' }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">{{ $puesto->cliente ? $puesto->cliente->Nombre : 'Sin cliente' }}</td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <div style="display: flex; gap: 24px; justify-content: center;">
                                <a href="{{ route('puestos.edit', $puesto->idPuestos) }}" class="ajax-link" style="text-align: center;">
                                    <img src="{{ asset('images/editar.png') }}" alt="Editar" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #555;">Editar</div>
                                </a>
                                <a href="{{ route('puestos.show', $puesto->idPuestos) }}" class="ajax-link" style="text-align: center;">
                                    <img src="{{ asset('images/ver.png') }}" alt="Ver" style="width: 32px; height: 32px;">
                                    <div style="font-size: 13px; color: #447D9B;">Ver</div>
                                </a>
                                <button type="button"
                                    @if (App\Helpers\PermissionHelper::hasPermission('puestos', 'eliminar'))
                                    <button type="button"
                                        class="btn-eliminar-puesto"
                                        data-id="{{ $puesto->idPuestos }}"
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
    @endif
</div>

<!-- Modal de confirmación de eliminación -->
<div id="modal-confirmar-eliminar" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:32px 24px; border-radius:8px; box-shadow:0 2px 12px rgba(0,0,0,0.2); text-align:center; min-width:300px;">
        <div style="font-size:18px; margin-bottom:24px;">¿Estás seguro de eliminar este puesto?</div>
        <button id="btn-confirmar-eliminar" style="padding:8px 24px; background:#e74c3c; color:#fff; border:none; border-radius:4px; margin-right:12px; cursor:pointer;">Sí, eliminar</button>
        <button id="btn-cancelar-eliminar" style="padding:8px 24px; background:#447D9B; color: white; border:none; border-radius:4px; cursor:pointer;">Cancelar</button>
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

// Mostrar alerta si viene de una creación exitosa
$(document).ready(function() {
    @if(session('success'))
        showAlert('{{ session('success') }}', 'success');
    @endif
});

var puestoIdPendiente = null;

$(document).on('click', '.btn-eliminar-puesto', function(e) {
    e.preventDefault();
    puestoIdPendiente = $(this).data('id');
    $('#modal-confirmar-eliminar').fadeIn(150).css('display', 'flex');
});

$(document).on('click', '#btn-confirmar-eliminar', function() {
    if (!puestoIdPendiente) return;
    
    $.ajax({
        url: "{{ url('puestos') }}/" + puestoIdPendiente,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
        },
        success: function(response) {
            if (response.success) {
                showAlert('Puesto eliminado correctamente', 'success');
                setTimeout(function() {
                    $.get("{{ route('puestos.index') }}", function(html) {
                        $('#main-content-overlay').html(html);
                    });
                }, 1000);
            }
            $('#modal-confirmar-eliminar').fadeOut(150);
        },
        error: function(xhr) {
            if (xhr.status === 409 && xhr.responseJSON.confirm) {
                $('#modal-confirmar-eliminar').fadeOut(150);
                if (confirm(xhr.responseJSON.message)) {
                    $.ajax({
                        url: "{{ url('puestos') }}/" + puestoIdPendiente,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE',
                            force: true
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('Puesto eliminado correctamente', 'success');
                                setTimeout(function() {
                                    $.get("{{ route('puestos.index') }}", function(html) {
                                        $('#main-content-overlay').html(html);
                                    });
                                }, 1000);
                            }
                        },
                        error: function() {
                            showAlert('Hubo un error al intentar eliminar el puesto', 'error');
                        }
                    });
                }
            } else {
                showAlert('Hubo un error al intentar eliminar el puesto', 'error');
            }
            $('#modal-confirmar-eliminar').fadeOut(150);
        }
    });
    
    puestoIdPendiente = null;
});

$(document).on('click', '#btn-cancelar-eliminar', function() {
    $('#modal-confirmar-eliminar').fadeOut(150);
    puestoIdPendiente = null;
});
</script>