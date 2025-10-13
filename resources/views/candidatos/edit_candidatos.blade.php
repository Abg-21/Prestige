<style>
    html, body {
        height: auto !important;
        overflow-y: auto !important;
    }
    
    #main-content-overlay {
        height: auto !important;
        max-height: calc(100vh - 150px) !important;
        overflow-y: auto !important;
    }
    
    .container {
        margin-bottom: 40px;
        height: auto !important;
        min-height: auto !important;
    }
    
    form, .row, .col-md-6, .mb-3 {
        height: auto !important;
        min-height: auto !important;
    }
</style>

<div class="container p-4" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 1000px; margin: 0 auto;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #333; font-size: 24px; margin: 0;">
            <i class="fas fa-user-edit" style="color: #FE7743; margin-right: 10px;"></i>
            Editar Candidato
        </h2>
        <a href="{{ route('candidatos.index') }}" class="ajax-link btn btn-sm" style="background-color: #f8f9fa; color: #333; border: 1px solid #ddd;">
            <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('candidatos.update', $candidato->IdCandidatos) }}" method="POST" class="ajax-form">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Información Personal -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" class="form-control" name="Nombre" value="{{ old('Nombre', $candidato->Nombre) }}" 
                           required maxlength="25">
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido Paterno *</label>
                    <input type="text" class="form-control" name="Apellido_P" value="{{ old('Apellido_P', $candidato->Apellido_P) }}" 
                           required maxlength="20">
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido Materno *</label>
                    <input type="text" class="form-control" name="Apellido_M" value="{{ old('Apellido_M', $candidato->Apellido_M) }}" 
                           required maxlength="20">
                </div>

                <div class="mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="Nombre" value="{{ old('Nombre', $candidato->Nombre) }}" required maxlength="25">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido Paterno *</label>
                            <input type="text" class="form-control" name="Apellido_Paterno" value="{{ old('Apellido_Paterno', $candidato->Apellido_Paterno) }}" required maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido Materno *</label>
                            <input type="text" class="form-control" name="Apellido_Materno" value="{{ old('Apellido_Materno', $candidato->Apellido_Materno) }}" required maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Edad *</label>
                            <input type="number" class="form-control" name="Edad" value="{{ old('Edad', $candidato->Edad) }}" required min="18" max="99">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" class="form-control" name="Telefono" value="{{ old('Telefono', $candidato->Telefono) }}" required maxlength="15">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-control" name="Estado">
                                @foreach(['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'] as $estado)
                                    <option value="{{ $estado }}" {{ $candidato->Estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ruta</label>
                            <input type="text" class="form-control" name="Ruta" value="{{ old('Ruta', $candidato->Ruta) }}" maxlength="30">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Escolaridad *</label>
                            <select class="form-control" name="Escolaridad" required>
                                @foreach(['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $esc)
                                    <option value="{{ $esc }}" {{ $candidato->Escolaridad == $esc ? 'selected' : '' }}>{{ $esc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo *</label>
                            <input type="email" class="form-control" name="Correo" value="{{ old('Correo', $candidato->Correo) }}" required maxlength="30">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Experiencia *</label>
                            <input type="text" class="form-control" name="Experiencia" value="{{ old('Experiencia', $candidato->Experiencia) }}" required maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Postulación *</label>
                            <input type="date" class="form-control" name="Fecha_Postulacion" value="{{ old('Fecha_Postulacion', $candidato->Fecha_Postulacion ? $candidato->Fecha_Postulacion->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Puesto</label>
                            <select class="form-control" name="IdPuestoCandidatoFK">
                                @foreach($puestos as $puesto)
                                    <option value="{{ $puesto->idPuestos }}" {{ $candidato->IdPuestoCandidatoFK == $puesto->idPuestos ? 'selected' : '' }}>{{ $puesto->Puesto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        @foreach($puestos as $puesto)
                            <option value="{{ $puesto->idPuestos }}" 
                                    {{ old('id_PuestoCandidatoFK', $candidato->id_PuestoCandidatoFK) == $puesto->idPuestos ? 'selected' : '' }}>
                                {{ $puesto->Puesto }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('candidatos.index') }}" class="ajax-link btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn" style="background-color: #FE7743; color: white;">
                <i class="fas fa-save me-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<!-- Agrega Font Awesome para los iconos si no lo tienes ya -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
// Código para manejar envío de formulario vía AJAX y permanecer en el carrusel
$(document).ready(function() {
    $('.ajax-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                // Mostrar mensaje de éxito
                mostrarNotificacion('Candidato actualizado correctamente', 'success');
                
                // Redirigir al listado de candidatos dentro del contenedor principal
                cargarContenido("{{ route('candidatos.index') }}");
            },
            error: function(xhr) {
                // Mostrar errores si hay alguno
                var errors = xhr.responseJSON;
                if (errors && errors.errors) {
                    var errorMsg = '';
                    $.each(errors.errors, function(key, value) {
                        errorMsg += value + '<br>';
                    });
                    mostrarNotificacion(errorMsg, 'error');
                } else {
                    mostrarNotificacion('Error al procesar la solicitud', 'error');
                }
            }
        });
    });
    
    // Función para mostrar notificaciones
    function mostrarNotificacion(mensaje, tipo) {
        // Crear el elemento de notificación si no existe
        if (!$('#notificacion-temporal').length) {
            $('body').append(`
                <div id="notificacion-temporal" style="position: fixed; bottom: 20px; right: 20px; 
                     padding: 15px 25px; border-radius: 4px; z-index: 9999; display: none; color: white;
                     box-shadow: 0 4px 8px rgba(0,0,0,0.2); font-weight: bold; min-width: 280px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="mensaje-notificacion">${mensaje}</span>
                        ${tipo === 'error' ? '<button id="cerrar-notificacion" style="background: transparent; border: none; color: white; font-size: 18px; cursor: pointer; margin-left: 10px;">×</button>' : ''}
                    </div>
                </div>
            `);
        } else {
            $('#mensaje-notificacion').html(mensaje);
        }
        
        // Establecer el color según el tipo
        let color;
        let duracion; // Duración en milisegundos
        
        switch(tipo) {
            case 'success': 
                color = '#4CAF50'; 
                duracion = 3000; // 3 segundos para mensajes de éxito
                break;
            case 'error': 
                color = '#f44336'; 
                duracion = 8000; // 8 segundos para mensajes de error
                break;
            case 'info': 
                color = '#2196F3'; 
                duracion = 3000;
                break;
            default: 
                color = '#4CAF50';
                duracion = 3000;
        }
        
        // Configurar y mostrar la notificación
        $('#notificacion-temporal')
            .css('background-color', color)
            .fadeIn(300);
        
        // Para los mensajes que no son de error, establecer un temporizador
        if (duracion) {
            setTimeout(function() {
                $('#notificacion-temporal').fadeOut(300, function() {
                    $(this).remove();
                });
            }, duracion);
        }
        
        // Configurar evento de clic para el botón de cierre
        $('#cerrar-notificacion').on('click', function() {
            $('#notificacion-temporal').fadeOut(300, function() {
                $(this).remove();
            });
        });
    }
    
    // Función para cargar contenido vía AJAX
    function cargarContenido(url) {
        $('#main-content-overlay').load(url);
    }
});
</script>