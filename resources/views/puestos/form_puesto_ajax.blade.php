<!-- filepath: resources/views/puestos/form_puesto_ajax.blade.php -->
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
                <button type="button" class="btn btn-secondary" id="btnNuevoGiro" style="margin-left: 8px;">Crear nuevo giro</button>
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
                <button type="button" class="btn btn-secondary" id="btnNuevoCliente" style="margin-left: 8px;">Crear nuevo cliente</button>
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
                <input type="text" name="Experiencia" value="{{ old('Experiencia') }}" required style="width: 100%;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 28px;">
            <button type="submit" style="padding: 8px 20px; background: #FE7743; color: #fff; border: none; border-radius: 4px;">Guardar</button>
        </div>
    </form>
</div>