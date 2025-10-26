<div style="width: 100%; max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
        <h2 style="margin-bottom: 0; color: #FE7743; font-weight: bold; display: flex; align-items: center;">
            Editar Giro
        </h2>
    </div>
    <form id="form-editar-giro" action="{{ route('giros.update', $giro->idGiros) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 22px;">
            <label for="Nombre" style="font-weight: bold;">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $giro->Nombre) }}" required class="form-control" style="margin-top: 6px;">
            @error('Nombre')
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 22px;">
            <label for="Descripcion" style="font-weight: bold;">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion" class="form-control" style="margin-top: 6px;">{{ old('Descripcion', $giro->Descripcion) }}</textarea>
            @error('Descripcion')
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: center; gap: 18px; margin-top: 32px;">
            <a href="{{ route('giros.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 28px;">Cancelar</a>
            <button type="submit" class="btn" style="background-color: #FE7743; color: white; border: none; padding: 8px 28px;">
                Guardar Cambios
            </button>
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

<script type="text/javascript">
// Interceptar formulario de edición de giro con JavaScript inmediato
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditGiroFormHandler);
    } else {
        initEditGiroFormHandler();
    }
    
    function initEditGiroFormHandler() {
        const form = document.getElementById('form-editar-giro');
        if (!form) {
            console.log('Formulario de edición de giro no encontrado');
            return;
        }
        
        console.log('Formulario de edición de giro encontrado, configurando AJAX');
        
        form.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('¡FORMULARIO DE EDICIÓN DE GIRO INTERCEPTADO!');
            
            const formData = new FormData(form);
            
            // Cambiar botón a estado de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Guardando...';
            submitBtn.disabled = true;
            
            console.log('Enviando actualización de giro...');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                console.log('Respuesta de edición de giro recibida:', response.status, response.statusText);
                
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
                console.log('Giro actualizado, HTML recibido:', html.length, 'caracteres');
                console.log('Primeros 200 caracteres:', html.substring(0, 200));
                
                // IMPORTANTE: Restaurar botón SIEMPRE
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Actualizar con la lista de giros
                const contenidoPrincipal = document.querySelector('#main-content-overlay');
                if (contenidoPrincipal) {
                    contenidoPrincipal.innerHTML = html;
                    mostrarNotificacion('Giro actualizado correctamente', 'success');
                } else {
                    console.error('No se encontró #main-content-overlay');
                }
            })
            .catch(function(error) {
                console.error('Error al actualizar giro:', error);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                mostrarNotificacion('Error al actualizar el giro: ' + error.message, 'error');
            });
            
            return false;
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

