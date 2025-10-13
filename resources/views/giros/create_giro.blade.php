<div style="width: 100%; max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px;">
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
        <h2 style="margin-bottom: 0; color: #FE7743; font-weight: bold; display: flex; align-items: center;">
            Crear Nuevo Giro
        </h2>
    </div>
    <form id="form-crear-giro" action="{{ route('giros.store') }}" method="POST" autocomplete="off">
        @csrf

        <div style="margin-bottom: 22px;">
            <label for="Nombre" style="font-weight: bold;">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" required class="form-control" style="margin-top: 6px;">
            @error('Nombre')
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom: 22px;">
            <label for="Descripcion" style="font-weight: bold;">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion" class="form-control" style="margin-top: 6px;">{{ old('Descripcion') }}</textarea>
            @error('Descripcion')
                <div style="color: #e74c3c; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <a href="{{ route('giros.index') }}" class="btn btn-danger ajax-link" style="padding: 8px 20px;">Cancelar</a>
            <button type="submit" class="btn btn-success" style="padding: 8px 20px;">Crear Giro</button>
        </div>
    </form>
</div>

