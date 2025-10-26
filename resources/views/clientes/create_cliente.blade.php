<div style="width: 100%; max-width: 800px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 32px;">
        <h2 style="margin-bottom: 0; color: #FE7743; font-weight: bold;">
            Crear Nuevo Cliente
        </h2>
    </div>

    <form id="form-crear-cliente" action="{{ route('clientes.store') }}" method="POST" style="width: 100%;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
            <div style="width: 100%;">
                <label for="Nombre" style="display: block; font-weight: bold; margin-bottom: 8px;">Nombre:</label>
                <input type="text" 
                    name="Nombre" 
                    id="Nombre" 
                    value="{{ old('Nombre') }}" 
                    required 
                    class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px;">
                @error('Nombre')
                    <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="width: 100%;">
                <label for="Telefono" style="display: block; font-weight: bold; margin-bottom: 8px;">Teléfono:</label>
                <input type="text" 
                    name="Telefono" 
                    id="Telefono" 
                    value="{{ old('Telefono') }}" 
                    class="form-control"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px;">
                @error('Telefono')
                    <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="margin-bottom: 28px;">
            <label for="Descripcion" style="display: block; font-weight: bold; margin-bottom: 8px;">Descripción:</label>
            <textarea 
                name="Descripcion" 
                id="Descripcion" 
                class="form-control"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; min-height: 100px; font-size: 15px; resize: vertical;">{{ old('Descripcion') }}</textarea>
            @error('Descripcion')
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 32px;">
            <a href="{{ route('clientes.index') }}" 
                class="btn btn-danger ajax-link" 
                style="padding: 10px 24px; background: #e74c3c; color: #fff; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Cancelar
            </a>
            <button type="submit" 
                style="padding: 10px 24px; background: #FE7743; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                Guardar Cliente
            </button>
        </div>
    </form>
</div>

<script type="text/javascript">
// Interceptar formulario con JavaScript inmediato
(function() {
    // Esperar a que el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormHandler);
    } else {
        initFormHandler();
    }
    
    function initFormHandler() {
        const form = document.getElementById('form-crear-cliente');
        if (!form) {
            console.log('Formulario no encontrado');
            return;
        }
        
        console.log('Formulario encontrado, configurando AJAX');
        
        // Prevenir submit por defecto
        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('¡FORMULARIO INTERCEPTADO CON AJAX!');
            
            // Obtener datos del formulario
            const formData = new FormData(form);
            
            // Cambiar botón a estado de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Guardando...';
            submitBtn.disabled = true;
            
            console.log('Enviando petición AJAX...');
            
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
                console.log('Respuesta recibida:', response.status, response.statusText);
                
                // Si la respuesta es un redirect (302, 301, etc)
                if (response.redirected || response.status === 302 || response.status === 301) {
                    console.log('Respuesta es redirect, cargando URL:', response.url);
                    // Cargar la página de destino del redirect
                    return fetch(response.url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }).then(res => res.text());
                }
                
                // Si no es redirect, obtener el texto
                if (!response.ok) {
                    throw new Error('Error HTTP: ' + response.status);
                }
                
                return response.text();
            })
            .then(function(html) {
                console.log('HTML recibido, longitud:', html.length);
                console.log('Primeros 200 caracteres:', html.substring(0, 200));
                
                // IMPORTANTE: Restaurar botón SIEMPRE
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Actualizar el contenido principal con la lista de clientes
                const contenidoPrincipal = document.querySelector('#main-content-overlay');
                if (contenidoPrincipal) {
                    contenidoPrincipal.innerHTML = html;
                    console.log('Contenido actualizado exitosamente');
                    
                    // Mostrar notificación de éxito
                    mostrarNotificacion('Cliente creado correctamente', 'success');
                } else {
                    console.error('No se encontró #main-content-overlay');
                    console.log('Selectores disponibles:', document.querySelector('div').className);
                }
            })
            .catch(function(error) {
                console.error('Error en AJAX:', error);
                // Restaurar botón
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                mostrarNotificacion('Error al crear el cliente: ' + error.message, 'error');
            });
            
            return false; // Asegurar que no se envíe normalmente
        };
    }
    
    function mostrarNotificacion(mensaje, tipo) {
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
</script>

