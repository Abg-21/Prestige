<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Documentos</title>
    <link rel="stylesheet" href="{{ asset('css/st.css') }}">
    <style>
       .home-icon {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .home-icon img {
            width: 25px;  /* Tamaño más pequeño */
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Icono de hogar en la esquina superior izquierda -->
    <a href="{{ route('menu') }}" class="home-icon">
        <img src="{{ asset('images/hogar.png') }}" alt="Inicio">
    </a>

    <div class="container">
        <h1>Gestión de Documentos</h1>

        <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="idEmpleadoFK" class="form-label">Empleado</label>
                <select name="idEmpleadoFK" id="idEmpleadoFK" class="form-select" required>
                    <option value="" disabled selected>Selecciona un empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->idEmpleado }}">{{ $empleado->Nombre }} {{ $empleado->Apellido_P }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="TipoArchivo" class="form-label">Tipo de Archivo</label>
                <select name="TipoArchivo" id="TipoArchivo" class="form-select" required>
                    <option value="PDF">PDF</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="Archivo" class="form-label">Subir Archivos (PDF)</label>
                <input type="file" name="Archivo[]" id="Archivo" class="form-control" multiple accept=".pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Subir Documento</button>
        </form>

        <hr>

        <h2>Lista de Documentos</h2>
            @if($empleados && count($empleados) > 0)
                @foreach($empleados as $empleado)
                    <h3>{{ $empleado->Nombre }} {{ $empleado->Apellido_P }}</h3>
                    @if($empleado->documentos && count($empleado->documentos) > 0)
                        @foreach($empleado->documentos as $documento)
                            <div class="d-flex justify-content-between">
                                <p>Archivo PDF</p>
                                <div>
                                    <a href="{{ route('documentos.download', $documento) }}" class="btn btn-success btn-sm">Descargar</a>
                                    <a href="{{ Storage::url($documento->RutaArchivo) }}" target="_blank" class="btn btn-info btn-sm">Ver</a>
                                    <form action="{{ route('documentos.destroy', $documento) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No hay documentos para este empleado.</p>
                    @endif
                @endforeach
            @else
                <p>No hay empleados registrados.</p>
            @endif
    </div>
</body>
</html>
