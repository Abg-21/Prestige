<div style="width: 100%; max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
        <h2 style="margin-bottom: 0; color: #FE7743; font-weight: bold; display: flex; align-items: center;">
            Editar Giro
        </h2>
    </div>
    <form id="form-editar-giro" action="{{ route('giros.update', ['giro' => $giro->idGiros]) }}" method="POST" autocomplete="off">
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

$(document).on('submit', '#form-editar-giro', function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var data = form.serialize();

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(response) {
            showAlert('Se guardaron los cambios correctamente', 'success');
            setTimeout(function() {
                $.get("{{ route('giros.index') }}", function(html) {
                    $('#main-content-overlay').html(html);
                });
            }, 1000);
        },
        error: function(xhr) {
            let msg = 'Hubo un error al hacer los cambios';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            showAlert(msg, 'error');
        }
    });
});
</script>

