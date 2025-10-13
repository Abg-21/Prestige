<div style="max-width: 500px; margin: 30px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px;">
        <h2 style="text-align: center; margin-bottom: 32px; color: #FE7743">Editar Cliente</h2>
        <form id="form-editar-cliente" action="{{ route('clientes.update', $cliente->idClientes) }}" method="POST" style="display: flex; flex-direction: column; align-items: center;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Nombre" style="font-weight:bold;">Nombre:</label>
                <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $cliente->Nombre) }}" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                @error('Nombre')
                    <div style="color:#e74c3c;">{{ $message }}</div>
                @enderror
            </div>
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Telefono" style="font-weight:bold;">Teléfono:</label>
                <input type="text" name="Telefono" id="Telefono" value="{{ old('Telefono', $cliente->Telefono) }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                @error('Telefono')
                    <div style="color:#e74c3c;">{{ $message }}</div>
                @enderror
            </div>
            <div style="margin-bottom: 18px; width: 100%;">
                <label for="Descripcion" style="font-weight:bold;">Descripción:</label>
                <input type="text" name="Descripcion" id="Descripcion" value="{{ old('Descripcion', $cliente->Descripcion) }}" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                @error('Descripcion')
                    <div style="color:#e74c3c;">{{ $message }}</div>
                @enderror
            </div>
            <div style="display: flex; justify-content: center; gap: 16px; margin-top: 28px; width: 100%;">
                <a href="{{ route('clientes.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 20px; background: #e74c3c; color: #fff; border-radius: 4px; text-decoration: none; text-align: center;">Cancelar</a>
                <button type="submit" style="padding: 8px 20px; background: #3498db; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div> <!-- Cierre del div principal del formulario -->

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
    $(document).on('submit', '#form-editar-cliente', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Mostrar mensaje de éxito
                    showAlert('Se guardaron los cambios correctamente', 'success');
                    
                    // Actualizar la vista con la lista de clientes
                    setTimeout(function() {
                        $.get("{{ route('clientes.index') }}", function(html) {
                            $('#main-content-overlay').html(html);
                        });
                    }, 1000); // Espera 1 segundo para que se vea el mensaje
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = '';
                    for (var key in errors) {
                        errorMsg += errors[key][0] + '\n';
                    }
                    showAlert(errorMsg, 'error');
                } else {
                    showAlert('Error al guardar los cambios', 'error');
                }
            }
        });
    });

    function showAlert(message, type) {
        // Remover alertas existentes
        $('.alert-float').remove();
        
        // Crear nueva alerta
        var alert = $('<div class="alert-float alert-' + type + '">' + message + '</div>');
        $('body').append(alert);
        
        // Mostrar alerta
        setTimeout(function() {
            alert.addClass('show');
        }, 10);
        
        // Remover alerta después de 2 segundos
        setTimeout(function() {
            alert.removeClass('show');
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 2000);
    }
    </script>

