
<div style="width: 100%; max-width: 700px; margin: 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 28px; color: #FE7743;">Crear Candidato</h2>
    <form id="form-crear-candidato" action="{{ route('candidatos.store') }}" method="POST" autocomplete="off">
        @csrf

        <!-- idCandidatos se llena automáticamente por Laravel -->

        <!-- Nombre, Apellido_P, Apellido_M -->
        <div style="display: flex; gap: 16px;">
            <div style="flex: 1;">
                <label>Nombre(s): *</label>
                <input type="text" name="Nombre" required maxlength="25" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Apellido Paterno: *</label>
                <input type="text" name="Apellido_P" required maxlength="20" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Apellido Materno: *</label>
                <input type="text" name="Apellido_M" required maxlength="20" style="width: 100%;">
            </div>
        </div>

        <!-- Edad y Teléfono -->
        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Edad: *</label>
                <input type="number" name="Edad" required min="18" max="255" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Teléfono Móvil:</label>
                <input type="tel" name="Telefono_M" maxlength="15" pattern="[0-9]+" placeholder="Solo números" style="width: 100%;">
            </div>
        </div>

        <!-- Estado -->
        <div style="margin-top: 18px;">
            <label>Estado:</label>
            <select name="Estado" required style="width: 100%;">
                <option value="">Seleccione un estado</option>
                @foreach(['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'] as $estado)
                    <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-top: 18px;">
            <label>Ruta:</label>
            <input type="text" name="Ruta" maxlength="30" style="width: 100%;">
        </div>

        <!-- Escolaridad -->
        <div style="margin-top: 18px;">
            <label>Escolaridad: *</label>
            <select name="Escolaridad" required style="width: 100%;">
                <option value="">Seleccione escolaridad</option>
                <option value="Primaria">Primaria</option>
                <option value="Secundaria terminada">Secundaria terminada</option>
                <option value="Bachillerato trunco">Bachillerato trunco</option>
                <option value="Bachillerato terminado">Bachillerato terminado</option>
                <option value="Técnico superior">Técnico superior</option>
                <option value="Licenciatura trunca">Licenciatura trunca</option>
                <option value="Licenciatura terminada">Licenciatura terminada</option>
                <option value="Postgrado">Postgrado</option>
            </select>
        </div>

        <!-- Correo -->
        <div style="margin-top: 18px;">
            <label>Correo:</label>
            <input type="email" name="Correo" maxlength="30" placeholder="ejemplo@correo.com" style="width: 100%;">
        </div>

        <!-- Experiencia -->
        <div style="margin-top: 18px;">
            <label>Experiencia: *</label>
            <input type="text" name="Experiencia" required maxlength="10" placeholder="Ejemplo: 2 años" style="width: 100%;">
        </div>

        <!-- Fecha_Postulacion -->
        <div style="margin-top: 18px;">
            <label>Fecha de Postulación:</label>
            <input type="date" name="Fecha_Postulacion" style="width: 100%;">
        </div>

        <!-- id_PuestoCandidatoFK -->
        <div style="margin-top: 18px;">
            <label>Puesto:</label>
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 1;">
                    <select id="selectPuesto" name="id_PuestoCandidatoFK" style="width: 100%;">
                        <option value="">Seleccione un puesto</option>
                        @if(isset($puestos))
                            @foreach($puestos as $puesto)
                                <option value="{{ $puesto->idPuestos }}">{{ $puesto->Nombre_Puesto }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="button" id="btnNuevoPuesto" style="padding: 8px 12px; background: #28a745; color: #fff; border: none; border-radius: 4px; white-space: nowrap;">+ Nuevo Puesto</button>
            </div>
        </div>

        <!-- eliminado_en se maneja automáticamente cuando se da de baja -->
        <!-- timestamps se manejan automáticamente por Laravel -->

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <a href="{{ route('candidatos.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 20px; background: #e74c3c; color: #fff; border-radius: 4px; text-decoration: none;">Cancelar</a>
            <button type="submit" style="padding: 8px 20px; background: #3498db; color: #fff; border: none; border-radius: 4px;">Guardar</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('#form-crear-candidato').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            // First load the index page
            $.get("{{ route('candidatos.index') }}", function(html) {
                $('#main-content-overlay').html(html);
                // Then show success message
                showAlert('Candidato creado exitosamente', 'success');
            });
        },
        error: function(xhr) {
            let errorMessage = 'Error al guardar el candidato';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors)[0];
            }
            showAlert(errorMessage, 'error');
        }
    });
});

$(document).on('click', '#btnNuevoPuesto', function() {
    $.get("{{ route('puestos.create') }}", function(html) {
        $('#modalPuestoContent').html(html);
        $('#modal-nuevo-puesto').fadeIn(150).css('display', 'flex');
    });
});

// Cerrar modal al dar click en la X o fuera del contenido
$(document).on('click', '#btn-cerrar-modal-puesto', function() {
    $('#modal-nuevo-puesto').fadeOut(150);
});
$(document).on('click', '#modal-nuevo-puesto', function(e) {
    if (e.target === this) $('#modal-nuevo-puesto').fadeOut(150);
});

// Guardar el puesto por AJAX
$(document).on('submit', '#formCrearPuesto', function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            $('#modal-nuevo-puesto').fadeOut(150);
            // Recarga el select de puestos y selecciona el nuevo por ID, evitando caché
            $.ajax({
                url: "{{ route('puestos.lista') }}",
                type: 'GET',
                cache: false,
                success: function(data) {
                    $('#selectPuesto').html(data);
                    if(response.id){
                        $('#selectPuesto').val(response.id);
                    }
                }
            });
        },
        error: function(xhr) {
            $('#modalPuestoContent').html(xhr.responseText);
        }
    });
});

// Add the showAlert function if not already present
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
</script>

<!-- Modal personalizado para crear puesto -->
<div id="modal-nuevo-puesto" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; overflow-y:auto; padding:20px;">
    <div id="modal-nuevo-puesto-content" style="background:#fff; padding:32px 24px; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,0.3); min-width:350px; max-width:90vw; width:600px; margin:auto; border:3px solid #447D9B; position:relative; opacity:1;">
       
        <div id="modalPuestoContent">
            <!-- Aquí se carga el formulario AJAX -->
        </div>
        <button id="btn-cerrar-modal-puesto" style="position:absolute; top:10px; right:14px; background:none; border:none; font-size:22px; color:#e74c3c; cursor:pointer; z-index:1;">&times;</button>
    </div>
</div>

<style>
#modal-nuevo-puesto {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.4);
    z-index: 9999;
    /* Lo importante para centrar: */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
}
#modal-nuevo-puesto-content {
    animation: modalIn 0.2s;
    background: #fff !important;
    opacity: 1 !important;
    max-height: 85vh;
    overflow-y: auto;
    margin: auto;
    position: relative;
}
@keyframes modalIn {
    from { transform: scale(0.9); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

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
    transform: translateY(-10px);
    transition: opacity 0.3s, transform 0.3s;
}

.alert-float.show {
    opacity: 1;
    transform: translateY(0);
}

.alert-success {
    background-color: #28a745;
}

.alert-error {
    background-color: #dc3545;
}
</style>

