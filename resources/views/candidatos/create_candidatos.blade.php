@extends('layouts.main')

@section('title', 'Crear Candidato')

@section('content')
<div style="width: 100%; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px; margin: 0;">
    <h2 style="text-align: center; margin-bottom: 28px; color: #FE7743;">Crear Candidato</h2>
    <form id="form-crear-candidato" action="{{ route('candidatos.store') }}" method="POST" autocomplete="off">
        @csrf

        <!-- IdCandidatos se llena automáticamente por Laravel -->

        <!-- Nombre, Apellido_Paterno, Apellido_Materno -->
        <div style="display: flex; gap: 16px;">
            <div style="flex: 1;">
                <label>Nombre(s): *</label>
                <input type="text" name="Nombre" required maxlength="25" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Apellido Paterno: *</label>
                <input type="text" name="Apellido_Paterno" required maxlength="20" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Apellido Materno: *</label>
                <input type="text" name="Apellido_Materno" required maxlength="20" style="width: 100%;">
            </div>
        </div>

        <!-- Edad y Teléfono -->
        <div style="display: flex; gap: 16px; margin-top: 18px;">
            <div style="flex: 1;">
                <label>Edad: *</label>
                <input type="number" name="Edad" required min="18" max="255" style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <label>Teléfono: *</label>
                <input type="tel" name="Telefono" required maxlength="15" pattern="[0-9]+" placeholder="Solo números" style="width: 100%;">
            </div>
        </div>

        <!-- Estado -->
        <div style="margin-top: 18px;">
            <label>Estado: *</label>
            <select name="Estado" required style="width: 100%;">
                <option value="">Seleccione un estado</option>
                @foreach(['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'] as $estado)
                    <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-top: 18px;">
            <label>Ruta y/o Cadena:</label>
            <input type="text" name="Ruta" maxlength="30" style="width: 100%;">
        </div>

        <!-- Escolaridad -->
        <div style="margin-top: 18px;">
            <label>Escolaridad: *</label>
            <select name="Escolaridad" required style="width: 100%;" class="form-select">
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
            <label>Correo: *</label>
            <input type="email" name="Correo" required maxlength="30" placeholder="ejemplo@correo.com" style="width: 100%;">
        </div>

        <!-- Experiencia -->
        <div style="margin-top: 18px;">
            <label>Experiencia: *</label>
            <input type="text" name="Experiencia" required maxlength="10" placeholder="Ejemplo: 2 años" style="width: 100%;">
        </div>

        <!-- Fecha_Postulacion -->
        <div style="margin-top: 18px;">
            <label>Fecha de Postulación: *</label>
            <input type="date" name="Fecha_Postulacion" required style="width: 100%;">
        </div>

        <!-- IdPuestoCandidatoFK -->
        <div style="margin-top: 18px;">
            <label>Puesto:</label>
            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <div style="flex: 1;">
                    <select id="selectPuesto" name="IdPuestoCandidatoFK" style="width: 100%;">
                        @include('puestos.lista')
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
// Configurar CSRF token para todas las peticiones AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
    },
    xhrFields: {
        withCredentials: true
    }
});

$('#form-crear-candidato').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    
    // Prevenir múltiples envíos
    if (submitBtn.prop('disabled')) {
        return false;
    }
    
    // Deshabilitar botón mientras se procesa
    submitBtn.prop('disabled', true).text('Guardando...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            console.log('✅ Candidato creado exitosamente');
            console.log('📄 Respuesta del servidor:', response);
            
            // Verificar si hay contenedor principal
            if ($('#main-content-overlay').length > 0) {
                console.log('📦 Contenedor #main-content-overlay encontrado');
                
                // Si el servidor ya devolvió la vista actualizada
                if (typeof response === 'string' && response.includes('Candidatos') && response.length > 500) {
                    console.log('✅ Usando respuesta directa del servidor');
                    $('#main-content-overlay').html(response);
                    showAlert('Candidato creado exitosamente', 'success');
                } else {
                    console.log('🔄 Cargando vista actualizada de candidatos...');
                    // Cargar vista actualizada de candidatos en el contenedor principal
                    $.get("{{ route('candidatos.index') }}", function(html) {
                        console.log('✅ Vista de candidatos cargada');
                        $('#main-content-overlay').html(html);
                        showAlert('Candidato creado exitosamente', 'success');
                    }).fail(function(xhr, status, error) {
                        console.error('❌ Error al cargar vista de candidatos:', status, error);
                        // Fallback: recargar la página
                        showAlert('Candidato creado. Recargando página...', 'success');
                        setTimeout(function() {
                            window.location.href = "{{ route('candidatos.index') }}";
                        }, 1500);
                    });
                }
            } else {
                console.log('❌ Contenedor #main-content-overlay no encontrado, redirigiendo');
                // Si no hay contenedor principal, redirigir
                window.location.href = "{{ route('candidatos.index') }}";
            }
        },
        error: function(xhr) {
            console.error('❌ Error al crear candidato:', xhr);
            let errorMessage = 'Error al guardar el candidato';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors)[0];
            } else if (xhr.responseText) {
                errorMessage = 'Error en el servidor: ' + xhr.status;
            }
            showAlert(errorMessage, 'error');
        },
        complete: function() {
            // Rehabilitar botón al finalizar (éxito o error)
            submitBtn.prop('disabled', false).text('Guardar');
        }
    });
    
    return false;
});

$(document).on('click', '#btnNuevoPuesto', function() {
    $.get("{{ route('puestos.create') }}?modal=true", function(html) {
        $('#modalPuestoContent').html(html);
        $('#modal-nuevo-puesto').fadeIn(150).css('display', 'flex');
        
        // Configurar campos dinámicos después de cargar el modal
        configurarCamposDinamicos();
    });
});

// Configurar campos dinámicos del modal
function configurarCamposDinamicos() {
    // Conocimientos
    $(document).off('click', '#add-conocimiento-modal').on('click', '#add-conocimiento-modal', function() {
        const container = $('#conocimientos-list-modal');
        const newField = $('<div class="input-group mb-2"><input type="text" name="Conocimientos[]" class="form-control" value=""><button type="button" class="btn btn-danger remove-conocimiento-modal">-</button></div>');
        container.append(newField);
    });
    
    // Funciones
    $(document).off('click', '#add-funcion-modal').on('click', '#add-funcion-modal', function() {
        const container = $('#funciones-list-modal');
        const newField = $('<div class="input-group mb-2"><input type="text" name="Funciones[]" class="form-control" value=""><button type="button" class="btn btn-danger remove-funcion-modal">-</button></div>');
        container.append(newField);
    });
    
    // Habilidades
    $(document).off('click', '#add-habilidad-modal').on('click', '#add-habilidad-modal', function() {
        const container = $('#habilidades-list-modal');
        const newField = $('<div class="input-group mb-2"><input type="text" name="Habilidades[]" class="form-control" value=""><button type="button" class="btn btn-danger remove-habilidad-modal">-</button></div>');
        container.append(newField);
    });
    
    // Remover campos
    $(document).off('click', '.remove-conocimiento-modal, .remove-funcion-modal, .remove-habilidad-modal')
              .on('click', '.remove-conocimiento-modal, .remove-funcion-modal, .remove-habilidad-modal', function() {
        const container = $(this).closest('div[id$="-list-modal"]');
        if (container.find('.input-group').length > 1) {
            $(this).closest('.input-group').remove();
        } else {
            alert('Debe mantener al menos un campo');
        }
    });
}

// Cerrar modal al dar click en la X o fuera del contenido
$(document).on('click', '#btn-cerrar-modal-puesto', function() {
    $('#modal-nuevo-puesto').fadeOut(150);
});
$(document).on('click', '#modal-nuevo-puesto', function(e) {
    if (e.target === this) $('#modal-nuevo-puesto').fadeOut(150);
});

// Guardar el puesto por AJAX
$(document).on('submit', '#form-crear-puesto', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    
    // Prevenir múltiples envíos
    if (submitBtn.prop('disabled')) {
        return false;
    }
    
    console.log('📤 Enviando formulario de puesto desde modal');
    submitBtn.prop('disabled', true).text('Guardando...');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(response) {
            console.log('✅ Puesto creado exitosamente:', response);
            $('#modal-nuevo-puesto').fadeOut(150);
            
            // Actualizar el select de puestos
            $.ajax({
                url: "{{ route('puestos.options') }}",
                type: 'GET',
                cache: false,
                success: function(data) {
                    console.log('✅ Lista de puestos actualizada');
                    $('#selectPuesto').html(data);
                    
                    // Seleccionar el nuevo puesto creado (múltiples formatos de respuesta)
                    var puestoId = null;
                    if(response.puesto && response.puesto.idPuestos){
                        puestoId = response.puesto.idPuestos;
                    } else if(response.id) {
                        puestoId = response.id;
                    } else if(response.puesto_id) {
                        puestoId = response.puesto_id;
                    }
                    
                    if(puestoId) {
                        $('#selectPuesto').val(puestoId);
                        console.log('🎯 Puesto seleccionado automáticamente:', puestoId);
                        showAlert('Puesto creado y seleccionado exitosamente', 'success');
                    } else {
                        console.log('⚠️ No se pudo determinar el ID del puesto creado');
                        showAlert('Puesto creado exitosamente', 'success');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('❌ Error al actualizar lista de puestos:', status, error, xhr.responseText);
                    if(xhr.status === 401 || xhr.status === 419) {
                        showAlert('Sesión expirada. Recarga la página.', 'error');
                    } else {
                        showAlert('Puesto creado, pero hubo un error al actualizar la lista', 'success');
                    }
                }
            });
        },
        error: function(xhr) {
            console.error('❌ Error al crear puesto:', xhr);
            let errorMessage = 'Error al guardar el puesto';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors)[0];
            }
            $('#modalPuestoContent').html('<div class="alert alert-danger">' + errorMessage + '</div>' + $('#modalPuestoContent').html());
        },
        complete: function() {
            submitBtn.prop('disabled', false).text('Guardar');
        }
    });
    
    return false;
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
        
        <!-- Botón cerrar más visible y más abajo -->
        <button id="btn-cerrar-modal-puesto" style="position:absolute; top:50px; right:15px; background:#e74c3c; border:3px solid #c82333; font-size:24px; color:white; cursor:pointer; z-index:1000; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; box-shadow:0 4px 8px rgba(0,0,0,0.4);">&times;</button>
        
        <div id="modalPuestoContent">
            <!-- Aquí se carga el formulario AJAX -->
        </div>
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

#btn-cerrar-modal-puesto:hover {
    background: #dc3545 !important;
    transform: scale(1.15);
    transition: all 0.3s ease;
    box-shadow: 0 6px 12px rgba(0,0,0,0.6);
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
@endsection