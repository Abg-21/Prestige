<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Puesto</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1>Crear Puesto</h1>
    <!-- Formulario -->
    <form action="{{ route('puestos.store') }}" method="POST">
        
        @csrf
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
                <option value="Promovendedor">Promovendedor</option>
                <option value="Promotor">Promotor</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        

        <label for="Puesto">Nombre del puesto:</label>
        <input type="text" id="Puesto" name="Puesto" value="{{ old('Puesto') }}" required>

        <label for="selectGiro">Giro:</label>
        <select id="selectGiro" name="id_GiroPuestoFK" required>
            <option value="" disabled {{ old('id_GiroPuestoFK') ? '' : 'selected' }}>Seleccione un giro</option>
            @foreach ($giros as $giro)
                <option value="{{ $giro->idGiros }}" {{ old('id_GiroPuestoFK') == $giro->idGiros ? 'selected' : '' }}>
                    {{ $giro->Nombre }}
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalNuevoGiro">Crear nuevo giro</button>

        <label for="selectCliente">Cliente:</label>
        <select id="selectCliente" name="id_ClientePuestoFK" required>
            <option value="" disabled {{ old('id_ClientePuestoFK') ? '' : 'selected' }}>Seleccione un cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->idClientes }}" {{ old('id_ClientePuestoFK') == $cliente->idClientes ? 'selected' : '' }}>
                    {{ $cliente->Nombre }}
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">Crear nuevo cliente</button>

        
        <div class="mb-3">
            <label for="Zona" class="form-label">Zona</label>
            <input type="text" class="form-control" id="Zona" name="Zona">
        </div>

        <label for="Estado">Estado:</label>
        <select id="Estado" name="Estado" required>
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

        <label for="Edad">Edad:</label>
        <div class="row">
            <div class="col">
                @foreach (['18-23', '24-30', '31-35'] as $rango)
                    <div class="form-check">
                        <input type="checkbox" 
                            id="edad_{{ $rango }}"  
                            class="form-check-input" 
                            name="Edad[]" 
                            value="{{ $rango }}"
                            {{ is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="edad_{{ $rango }}">{{ $rango }}</label>
                    </div>
                @endforeach
            </div>
            <div class="col">
                @foreach (['36-42', '43-51', '52-60'] as $rango)
                    <div class="form-check">
                        <input type="checkbox" 
                            id="edad_{{ $rango }}"  
                            class="form-check-input" 
                            name="Edad[]" 
                            value="{{ $rango }}"
                            {{ is_array(old('Edad')) && in_array($rango, old('Edad')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="edad_{{ $rango }}">{{ $rango }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label for="escolaridad" class="form-label">Escolaridad:</label>
            <select id="escolaridad" name="Escolaridad" class="form-select" required>
                @foreach (['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $nivel)
                    <option value="{{ $nivel }}" {{ old('Escolaridad') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="experiencia" class="form-label">Experiencia:</label>
            <input type="text" id="experiencia" name="Experiencia" value="{{ old('Experiencia') }}" class="form-control" required>
        </div>

        <div id="autoFields" class="d-none">
            <div class="mb-3">
                <label for="Conocimientos" class="form-label">Conocimientos</label>
                <textarea class="form-control" id="Conocimientos" name="Conocimientos" required></textarea>
            </div>
            <div class="mb-3">
                <label for="Funciones" class="form-label">Funciones</label>
                <textarea class="form-control" id="Funciones" name="Funciones" required></textarea>
            </div>
            <div class="mb-3">
                <label for="Habilidades" class="form-label">Habilidades</label>
                <textarea class="form-control" id="Habilidades" name="Habilidades" required></textarea>
            </div>
        </div>

        <div id="dynamicFields" class="d-none">
            <div class="mb-3">
                <label for="Conocimientos">Conocimientos:</label>
                <div id="conocimientos-container" class="d-flex gap-2 flex-wrap">
                <input type="text" name="Conocimientos[]" class="form-control mb-2">
                </div>
                <div class="d-flex justify-content-start gap-2">	
                    <button type="button" class="btn btn-primary" onclick="addInput('conocimientos-container', 'Conocimientos[]')">Agregar</button>
                    <button type="button" class="btn btn-danger" onclick="removeLastInput('conocimientos-container')">Quitar</button>
                </div>
            </div>
            <div class="mb-3">
                <label for="Funciones">Funciones:</label>
                <div id="funciones-container" class="d-flex gap-2 flex-wrap">
                    <input type="text" name="Funciones[]" class="form-control mb-2" >
                </div>
                <div class="d-flex justify-content-start gap-2">	
                    <button type="button" class="btn btn-primary" onclick="addInput('funciones-container', 'Funciones[]')">Agregar</button>
                    <button type="button" class="btn btn-danger" onclick="removeLastInput('funciones-container')">Quitar</button>
                </div>
            </div>
            <div class="mb-3">
                <label for="Habilidades">Habilidades:</label>
                <div id="habilidades-container" class="d-flex gap-2 flex-wrap">
                    <input type="text" name="Habilidades[]" class="form-control mb-2" >
                </div>
                <div class="d-flex justify-content-start gap-2">	
                    <button type="button" class="btn btn-primary" onclick="addInput('habilidades-container', 'Habilidades[]')">Agregar</button>
                    <button type="button" class="btn btn-danger" onclick="removeLastInput('habilidades-container')">Quitar</button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('puestos.index') }}" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

    </form>
<!-- Modal para Nuevo Cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoClienteLabel">Nuevo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearCliente">
                    @csrf
                    <div class="mb-3">
                        <label for="nombreCliente" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombreCliente" name="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefonoCliente" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefonoCliente" name="Telefono">
                    </div>
                    <div class="mb-3">
                        <label for="descripcionCliente" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcionCliente" name="Descripcion"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nuevo Giro -->
    <div class="modal fade" id="modalNuevoGiro" tabindex="-1" aria-labelledby="modalNuevoGiroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoGiroLabel">Nuevo Giro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearGiro">
                        @csrf
                        <div class="mb-3">
                            <label for="nombreGiro" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreGiro" name="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionGiro" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcionGiro" name="Descripcion"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/modal_cliente_giro.js') }}"></script>
    <script src="{{ asset('js/form_puestos.js') }}"></script>
</body>
</html>
