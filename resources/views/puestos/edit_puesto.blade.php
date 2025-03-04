<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Puesto</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Editar Puesto</h1>

        <form action="{{ route('puestos.update', $puesto->idPuestos) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Mostrar errores de validación -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="Categoría" class="form-label">Categoría</label>
                <select class="form-select" id="Categoría" name="Categoría" required>
                    <option value="">Seleccionar</option>
                    <option value="Promovendedor" {{ old('Categoría', $puesto->Categoría) == 'Promovendedor' ? 'selected' : '' }}>Promovendedor</option>
                    <option value="Promotor" {{ old('Categoría', $puesto->Categoría) == 'Promotor' ? 'selected' : '' }}>Promotor</option>
                    <option value="Supervisor" {{ old('Categoría', $puesto->Categoría) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="Otro" {{ old('Categoría', $puesto->Categoría) == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="Puesto" class="form-label">Nombre del Puesto</label>
                <input type="text" class="form-control" id="Puesto" name="Puesto" value="{{ old('Puesto', $puesto->Puesto) }}" required>
            </div>

            <div class="mb-3">
                <label for="selectGiro" class="form-label">Giro</label>
                <select id="selectGiro" name="id_GiroPuestoFK" class="form-select" required>
                    <option value="">Seleccione un giro</option>
                    @foreach ($giros as $giro)
                        <option value="{{ $giro->idGiros }}" {{ old('id_GiroPuestoFK', $puesto->id_GiroPuestoFK) == $giro->idGiros ? 'selected' : '' }}>{{ $giro->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="selectCliente" class="form-label">Cliente</label>
                <select id="selectCliente" name="id_ClientePuestoFK" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->idClientes }}" {{ old('id_ClientePuestoFK', $puesto->id_ClientePuestoFK) == $cliente->idClientes ? 'selected' : '' }}>{{ $cliente->Nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="Zona" class="form-label">Zona</label>
                <input type="text" class="form-control" id="Zona" name="Zona" value="{{ old('Zona', $puesto->Zona) }}">
            </div>

            <div class="mb-3">
                <label for="Estado" class="form-label">Estado</label>
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

            <div class="mb-3">
                <label for="Edad" class="form-label">Edad</label>
                @php
                    $edadSeleccionada = explode(', ', $puesto->Edad);
                @endphp
                <div class="d-flex flex-wrap">
                    @foreach (['18-23', '24-30', '31-35', '36-42', '43-51', '52-60'] as $rango)
                        <div class="form-check me-3">
                            <input type="checkbox" class="form-check-input" name="Edad[]" value="{{ $rango }}" 
                                {{ in_array($rango, $edadSeleccionada) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $rango }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <label for="Escolaridad" class="form-label">Escolaridad</label>
                <select id="Escolaridad" name="Escolaridad" class="form-select" required>
                    @foreach (['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $nivel)
                        <option value="{{ $nivel }}" {{ old('Escolaridad', $puesto->Escolaridad) == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('puestos.index') }}" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Puesto</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/modal_cliente_giro.js') }}"></script>
    <script src="{{ asset('js/form_puestos.js') }}"></script>
</body>
</html>
