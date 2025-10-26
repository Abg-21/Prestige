<div class="container" style="max-width: 800px; margin-top: 40px;">
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 28px;">
        <h1 class="text-center mb-4" style="color: #FE7743;">Editar Puesto</h1>
        <div id="erroresForm" class="alert alert-danger d-none"></div>
        <form id="formEditarPuesto" action="{{ route('puestos.update', $puesto->idPuestos) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Categoría" class="form-label fw-bold">Categoría</label>
                    <select class="form-select" id="Categoría" name="Categoría" required>
                        <option value="">Seleccionar</option>
                        <option value="Promovendedor" {{ old('Categoría', $puesto->Categoría) == 'Promovendedor' ? 'selected' : '' }}>Promovendedor</option>
                        <option value="Promotor" {{ old('Categoría', $puesto->Categoría) == 'Promotor' ? 'selected' : '' }}>Promotor</option>
                        <option value="Supervisor" {{ old('Categoría', $puesto->Categoría) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Otro" {{ old('Categoría', $puesto->Categoría) == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Puesto" class="form-label fw-bold">Nombre del Puesto</label>
                    <input type="text" class="form-control" id="Puesto" name="Puesto" value="{{ old('Puesto', $puesto->Puesto) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="selectGiro" class="form-label fw-bold">Giro</label>
                    <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                        <option value="">Seleccione un giro</option>
                        @foreach ($giros as $giro)
                            <option value="{{ $giro->idGiros }}" {{ old('id_GiroPuestoFK', $puesto->id_GiroPuestoFK) == $giro->idGiros ? 'selected' : '' }}>{{ $giro->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="selectCliente" class="form-label fw-bold">Cliente</label>
                    <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idClientes }}" {{ old('id_ClientePuestoFK', $puesto->id_ClientePuestoFK) == $cliente->idClientes ? 'selected' : '' }}>{{ $cliente->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Zona" class="form-label fw-bold">Zona</label>
                    <input type="text" class="form-control" id="Zona" name="Zona" value="{{ old('Zona', $puesto->Zona) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Estado" class="form-label fw-bold">Estado</label>
                    <select id="Estado" name="Estado" class="form-select" required>
                        @php
                            $listaEstados = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                                'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                                'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 'Nayarit', 
                                'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 
                                'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'
                            ];
                        @endphp
                        @foreach ($listaEstados as $estado)
                            <option value="{{ $estado }}" {{ old('Estado', $puesto->Estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Edad</label>
                    @php
                        $edadSeleccionada = is_array(old('Edad')) ? old('Edad') : explode(', ', $puesto->Edad);
                    @endphp
                    <div class="d-flex flex-wrap gap-2">
                        @foreach (['18-23', '24-30', '31-35', '36-42', '43-51', '52-60'] as $rango)
                            <div class="form-check me-3">
                                <input type="checkbox" class="form-check-input" name="Edad[]" value="{{ $rango }}"
                                    {{ in_array($rango, $edadSeleccionada) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $rango }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Escolaridad" class="form-label fw-bold">Escolaridad</label>
                    <select id="Escolaridad" name="Escolaridad" class="form-select" required>
                        @foreach (['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $nivel)
                            <option value="{{ $nivel }}" {{ old('Escolaridad', $puesto->Escolaridad) == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Experiencia" class="form-label fw-bold">Experiencia</label>
                    <input type="text" class="form-control" id="Experiencia" name="Experiencia" value="{{ old('Experiencia', $puesto->Experiencia) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Conocimientos</label>
                    <div id="conocimientos-list">
                        @php
                            $conocimientos = old('Conocimientos', $puesto->Conocimientos ?? []);
                            if (!is_array($conocimientos)) $conocimientos = json_decode($conocimientos, true) ?? (is_string($conocimientos) ? explode(',', $conocimientos) : []);
                        @endphp
                        @foreach($conocimientos as $conocimiento)
                            <div class="input-group mb-2">
                                <input type="text" name="Conocimientos[]" class="form-control" value="{{ $conocimiento }}">
                                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                            </div>
                        @endforeach
                        @if(empty($conocimientos))
                            <div class="input-group mb-2">
                                <input type="text" name="Conocimientos[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-conocimiento" class="btn btn-primary btn-sm mb-3">Agregar Conocimiento</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Funciones</label>
                    <div id="funciones-list">
                        @php
                            $funciones = old('Funciones', $puesto->Funciones ?? []);
                            if (!is_array($funciones)) $funciones = json_decode($funciones, true) ?? (is_string($funciones) ? explode(',', $funciones) : []);
                        @endphp
                        @foreach($funciones as $funcion)
                            <div class="input-group mb-2">
                                <input type="text" name="Funciones[]" class="form-control" value="{{ $funcion }}">
                                <button type="button" class="btn btn-danger remove-funcion">-</button>
                            </div>
                        @endforeach
                        @if(empty($funciones))
                            <div class="input-group mb-2">
                                <input type="text" name="Funciones[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-funcion">-</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-funcion" class="btn btn-primary btn-sm mb-3">Agregar Función</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Habilidades</label>
                    <div id="habilidades-list">
                        @php
                            $habilidades = old('Habilidades', $puesto->Habilidades ?? []);
                            if (!is_array($habilidades)) $habilidades = json_decode($habilidades, true) ?? (is_string($habilidades) ? explode(',', $habilidades) : []);
                        @endphp
                        @foreach($habilidades as $habilidad)
                            <div class="input-group mb-2">
                                <input type="text" name="Habilidades[]" class="form-control" value="{{ $habilidad }}">
                                <button type="button" class="btn btn-danger remove-habilidad">-</button>
                            </div>
                        @endforeach
                        @if(empty($habilidades))
                            <div class="input-group mb-2">
                                <input type="text" name="Habilidades[]" class="form-control" value="">
                                <button type="button" class="btn btn-danger remove-habilidad">-</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-habilidad" class="btn btn-primary btn-sm mb-3">Agregar Habilidad</button>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('puestos.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 20px;">Cancelar</a>
                <button type="submit" class="btn" style="background: #FE7743; color: #fff;">Actualizar Puesto</button>
            </div>
        </form>
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

// Interceptar formulario con JavaScript inmediato para puestos
(function() {
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPuestoFormHandler);
    } else {
        initPuestoFormHandler();
    }
    
    function initPuestoFormHandler() {
        const form = document.getElementById('formEditarPuesto');
        if (!form) {
            console.log('Formulario de puesto no encontrado');
            return;
        }
        
        console.log('Formulario de puesto encontrado, configurando AJAX');
        
        // Prevenir submit por defecto
        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('¡FORMULARIO DE PUESTO INTERCEPTADO CON AJAX!');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            
            // Cambiar botón a estado de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Guardando...';
            submitBtn.disabled = true;
            
            console.log('Enviando petición AJAX de puesto...');
            
            // Hacer petición AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                console.log('Respuesta de puesto recibida:', response.status, response.statusText);
                
                // Si la respuesta es un redirect (302, 301, etc)
                if (response.redirected || response.status === 302 || response.status === 301) {
                    console.log('Respuesta es redirect, cargando URL:', response.url);
                    return fetch(response.url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(res => res.text());
                }
                
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                
                return response.text();
            })
            .then(function(html) {
                console.log('Puesto actualizado, HTML recibido:', html.length, 'caracteres');
                console.log('Primeros 200 caracteres:', html.substring(0, 200));
                
                // IMPORTANTE: Restaurar botón SIEMPRE
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Actualizar con la lista de puestos
                const contenidoPrincipal = document.querySelector('#main-content-overlay');
                if (contenidoPrincipal) {
                    contenidoPrincipal.innerHTML = html;
                    mostrarNotificacionPuesto('Puesto actualizado correctamente', 'success');
                } else {
                    console.error('No se encontró #main-content-overlay');
                }
            })
            .catch(function(error) {
                console.error('Error al actualizar puesto:', error);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                mostrarNotificacionPuesto('Error al actualizar el puesto: ' + error.message, 'error');
            });
            
            return false;
        };
    }
    
    function mostrarNotificacionPuesto(mensaje, tipo) {
        const div = document.createElement('div');
        div.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; padding: 15px; border-radius: 4px; color: white; font-weight: bold;';
        div.style.backgroundColor = tipo === 'success' ? '#28a745' : '#dc3545';
        div.textContent = mensaje;
        
        document.body.appendChild(div);
        
        setTimeout(function() {
            if (div.parentNode) {
                div.parentNode.removeChild(div);
            }
        }, 3000);
    }
})();

// Agregar y eliminar dinámicamente conocimientos, funciones y habilidades
function initDynamicFields() {
    // Agregar conocimiento
    $('#add-conocimiento').on('click', function() {
        $('#conocimientos-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Conocimientos[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-conocimiento">-</button>
            </div>
        `);
    });

    // Eliminar conocimiento
    $(document).on('click', '.remove-conocimiento', function() {
        $(this).closest('.input-group').remove();
    });

    // Agregar función
    $('#add-funcion').on('click', function() {
        $('#funciones-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Funciones[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-funcion">-</button>
            </div>
        `);
    });

    // Eliminar función
    $(document).on('click', '.remove-funcion', function() {
        $(this).closest('.input-group').remove();
    });

    // Agregar habilidad
    $('#add-habilidad').on('click', function() {
        $('#habilidades-list').append(`
            <div class="input-group mb-2">
                <input type="text" name="Habilidades[]" class="form-control" value="">
                <button type="button" class="btn btn-danger remove-habilidad">-</button>
            </div>
        `);
    });

    // Eliminar habilidad
    $(document).on('click', '.remove-habilidad', function() {
        $(this).closest('.input-group').remove();
    });
}

$(document).ready(function() {
    initDynamicFields();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/modal_cliente_giro.js') }}"></script>
<script src="{{ asset('js/form_puestos.js') }}"></script>

