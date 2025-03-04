<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">

</head>
<body>
    <h1 class="titulo-principal">Editar Candidato</h1>

    <form action="{{ route('candidatos.update', $candidato->idCandidatos) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="Nombre">Nombre:</label>
        <input type="text" id="Nombre" name="Nombre" value="{{ old('Nombre', $candidato->Nombre) }}" required>

        <label for="Apellido_P">Apellido Paterno:</label>
        <input type="text" id="Apellido_P" name="Apellido_P" value="{{ old('Apellido_P', $candidato->Apellido_P) }}" required>

        <label for="Apellido_M">Apellido Materno:</label>
        <input type="text" id="Apellido_M" name="Apellido_M" value="{{ old('Apellido_M', $candidato->Apellido_M) }}" required>

        <label for="id_PuestoCandidatoFK">Puesto:</label>
        <select id="id_PuestoCandidatoFK" name="id_PuestoCandidatoFK" required>
            <option value="">Seleccione un puesto</option>
            @foreach ($puestos as $puesto)
                <option value="{{ $puesto->idPuestos }}" {{ old('id_PuestoCandidatoFK', $candidato->id_PuestoCandidatoFK) == $puesto->idPuestos ? 'selected' : '' }}>
                    {{ $puesto->Puesto }}
                </option>
            @endforeach
        </select>

        <label for="Correo">Correo:</label>
        <input type="email" id="Correo" name="Correo" value="{{ old('Correo', $candidato->Correo) }}">

        <label for="Escolaridad">Escolaridad:</label>
        <select id="Escolaridad" name="Escolaridad" required>
            @foreach(['Primaria', 'Secundaria terminada', 'Bachillerato trunco', 'Bachillerato terminado', 'Técnico superior', 'Licenciatura trunca', 'Licenciatura terminada', 'Postgrado'] as $option)
                <option value="{{ $option }}" {{ old('Escolaridad', $candidato->Escolaridad) === $option ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>

        <label for="Experiencia">Experiencia (en años):</label>
        <input type="number" id="Experiencia" name="Experiencia" value="{{ old('Experiencia', $candidato->Experiencia) }}" required>

        <label for="Comentarios">Comentarios:</label>
        <textarea id="Comentarios" name="Comentarios" rows="4">{{ old('Comentarios', $candidato->Comentarios) }}</textarea>

        <button type="submit">Actualizar</button>
    </form>

    <p>
        <a href="{{ route('candidatos.index') }}">Cancelar</a>
    </p>
</body>
</html>
