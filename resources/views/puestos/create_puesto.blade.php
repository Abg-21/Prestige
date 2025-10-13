<div style="width: 100%; max-width: 700px; margin: 0 auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
    <h2 style="text-align: center; margin-bottom: 28px; color: #FE7743;">Crear Puesto</h2>
    <form id="form-crear-puesto" action="{{ route('puestos.store') }}" method="POST" autocomplete="off">
        @csrf

        <div style="display: flex; gap: 16px;">
            <div style="flex: 1;">
                <label>Categoría:</label>
                <select name="Categoría" required style="width: 100%;" class="form-select">
                    <option value="">Seleccionar</option>
                    <option value="Promovendedor">Promovendedor</option>
                    <option value="Promotor">Promotor</option>
                    <option value="Supervisor">Supervisor</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div style="flex: 2;">
                <label>Nombre del puesto:</label>
                <input type="text" name="Puesto" value="{{ old('Puesto') }}" required style="width: 100%;">
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Giro:</label>
            <div class="input-group mb-3">
                <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                    <option value="" disabled {{ old('id_GiroPuestoFK') ? '' : 'selected' }}>Seleccione un giro</option>
                    @foreach ($giros as $giro)
                        <option value="{{ $giro->idGiros }}" {{ old('id_GiroPuestoFK') == $giro->idGiros ? 'selected' : '' }}>
                            {{ $giro->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Cliente:</label>                    
            <div class="input-group mb-3">
                <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                    <option value="" disabled {{ old('id_ClientePuestoFK') ? '' : 'selected' }}>Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->idClientes }}" {{ old('id_ClientePuestoFK') == $cliente->idClientes ? 'selected' : '' }}>
                            {{ $cliente->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Zona:</label>
                <input type="text" name="Zona" value="{{ old('Zona') }}" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Estado:</label>
                <select name="Estado" required style="width: 100%;" class="form-select">
                    <option value="" disabled {{ old('Estado') ? '' : 'selected' }}>Seleccione un estado</option>
                    @php
                        $listaEstados = [
                            'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                            'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                            'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
                            'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
                            'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
                        ];
                    @endphp
                    @foreach ($listaEstados as $Estado)
                        <option value="{{ $Estado }}" {{ old('Estado') == $Estado ? 'selected' : '' }}>
                            {{ $Estado }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Edad:</label>
            <div style="display: flex; gap: 16px;">
                <div style="flex: 1;">
                    @foreach (['18-23', '24-30', '31-35'] as $rango)
                        <div class="form-check">
                            <input type="checkbox" id="edad_{{ $rango }}" class="form-check-input" name="Edad[]" value="{{ $rango }}"
                                {{ is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edad_{{ $rango }}">{{ $rango }}</label>
                        </div>
                    @endforeach
                </div>
                <div style="flex: 1;">
                    @foreach (['36-42', '43-51', '52-60'] as $rango)
                        <div class="form-check">
                            <input type="checkbox" id="edad_{{ $rango }}" class="form-check-input" name="Edad[]" value="{{ $rango }}"
                                {{ is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edad_{{ $rango }}">{{ $rango }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Escolaridad:</label>
                <select name="Escolaridad" required style="width: 100%;" class="form-select">
                    @foreach (['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $nivel)
                        <option value="{{ $nivel }}" {{ old('Escolaridad') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1;">
                <label>Experiencia:</label>
                <input type="text" name="Experiencia" value="{{old('Experiencia') }}" required style="width: 100%;">
            </div>
        </div>

        <div style="margin-top: 18px;">
            <label>Conocimientos:</label>
            <div id="conocimientos-list">
                @if(is_array(old('Conocimientos')))
                    @foreach(old('Conocimientos') as $conocimiento)
                        <div class="input-group mb-2">
                            <input type="text" name="Conocimientos[]" class="form-control" value="{{ $conocimiento }}">
                            <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2">
                        <input type="text" name="Conocimientos[]" class="form-control" value="">
                        <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-conocimiento" class="btn btn-primary btn-sm mb-3">Agregar Conocimiento</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Funciones:</label>
            <div id="funciones-list">
                @if(is_array(old('Funciones')))
                    @foreach(old('Funciones') as $funcion)
                        <div class="input-group mb-2">
                            <input type="text" name="Funciones[]" class="form-control" value="{{ $funcion }}">
                            <button type="button" class="btn btn-danger remove-funcion">-</button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2">
                        <input type="text" name="Funciones[]" class="form-control" value="">
                        <button type="button" class="btn btn-danger remove-funcion">-</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-funcion" class="btn btn-primary btn-sm mb-3">Agregar Función</button>
        </div>

        <div style="margin-top: 18px;">
            <label>Habilidades:</label>
            <div id="habilidades-list">
                @if(is_array(old('Habilidades')))
                    @foreach(old('Habilidades') as $habilidad)
                        <div class="input-group mb-2">
                            <input type="text" name="Habilidades[]" class="form-control" value="{{ $habilidad }}">
                            <button type="button" class="btn btn-danger remove-habilidad">-</button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2">
                        <input type="text" name="Habilidades[]" class="form-control" value="">
                        <button type="button" class="btn btn-danger remove-habilidad">-</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-habilidad" class="btn btn-primary btn-sm mb-3">Agregar Habilidad</button>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <a href="{{ route('puestos.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 20px;">Cancelar</a>
            <button type="submit" class="btn btn-success" style="padding: 8px 20px;">Guardar</button>
        </div>
    </form>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

$(document).on('submit', '#form-crear-puesto', function(e) {
    e.preventDefault();
    e.stopPropagation(); // Evitar que otros manejadores interfieran
    
    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    
    // Prevenir múltiples envíos
    if (submitBtn.prop('disabled')) {
        console.log('⚠️ Formulario ya está siendo procesado');
        return false;
    }
    
    console.log('📤 Enviando formulario de creación de puesto');
    
    // Deshabilitar botón mientras se procesa
    submitBtn.prop('disabled', true).text('Guardando...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            console.log('✅ Respuesta exitosa:', response);
            if (response.success) {
                $.get("{{ route('puestos.index') }}", function(html) {
                    $('#main-content-overlay').html(html);
                    showAlert('Se guardaron los datos correctamente', 'success');
                });
            }
        },
        error: function(xhr) {
            console.error('❌ Error en el formulario:', xhr);
            let msg = 'Hubo un error al hacer los cambios';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                msg = Object.values(xhr.responseJSON.errors)[0];
            }
            showAlert(msg, 'error');
        },
        complete: function() {
            // Rehabilitar botón al finalizar (éxito o error)
            submitBtn.prop('disabled', false).text('Guardar');
        }
    });
    
    return false; // Asegurar que no se procese por otros manejadores
});

$(function() {
    $('#add-conocimiento').click(function() {
        $('#conocimientos-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Conocimientos[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
            </div>
        `);
    });
    $(document).on('click', '.remove-conocimiento', function() {
        $(this).closest('.input-group').remove();
    });

    $('#add-funcion').click(function() {
        $('#funciones-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Funciones[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-funcion">-</button>
            </div>
        `);
    });
    $(document).on('click', '.remove-funcion', function() {
        $(this).closest('.input-group').remove();
    });

    $('#add-habilidad').click(function() {
        $('#habilidades-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Habilidades[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-habilidad">-</button>
            </div>
        `);
    });
    $(document).on('click', '.remove-habilidad', function() {
        $(this).closest('.input-group').remove();
    });
});
</script>