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

