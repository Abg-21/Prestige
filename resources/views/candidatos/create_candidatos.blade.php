<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
</head>
<body>
    <h1>Crear Candidato</h1>
    <form action="{{ route('candidatos.store') }}" method="POST">
        @csrf
        
        <label>Nombre:</label>
        <input type="text" name="Nombre" required>

        <label>Apellido Paterno:</label>
        <input type="text" name="Apellido_P" required>

        <label>Apellido Materno:</label>
        <input type="text" name="Apellido_M" required>

        <label>Puesto:</label>
        <select name="id_PuestoCandidatoFK" required>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->idPuestos }}">{{ $puesto->Puesto }}</option>
            @endforeach
        </select>
        <button type="button" onclick="window.location.href='{{ route('puestos.create') }}'">Crear nuevo puesto</button>


        <label>Teléfono Móvil:</label>
        <input type="text" name="Telefono_M" maxlength="15" placeholder="Ej: 5512345678">

        <label>Teléfono Fijo:</label>
        <input type="text" name="Telefono_F" maxlength="15" placeholder="Ej: 5543211234">

        <label>Ciudad:</label>
        <input type="text" name="Ciudad" maxlength="30" placeholder="Ej: Ciudad de México">

        <label>Estado:</label>
        <input type="text" name="Estado" maxlength="30" placeholder="Ej: Estado de México">

        <label>Correo:</label>
        <input type="email" name="Correo" placeholder="Ej: ejemplo@correo.com">

        <label>Escolaridad:</label>
        <select name="Escolaridad">
            <option value="Primaria">Primaria</option>
            <option value="Secundaria terminada">Secundaria terminada</option>
            <option value="Bachillerato trunco">Bachillerato trunco</option>
            <option value="Bachillerato terminado">Bachillerato terminado</option>
            <option value="Técnico superior">Técnico superior</option>
            <option value="Licenciatura trunca">Licenciatura trunca</option>
            <option value="Licenciatura terminada">Licenciatura terminada</option>
            <option value="Postgrado">Postgrado</option>
        </select>

        <label>Experiencia (en años):</label>
        <input type="number" name="Experiencia" required min="0" placeholder="Ej: 2">

        <label>Comentarios:</label>
        <textarea name="Comentarios" maxlength="50" placeholder="Ingrese comentarios adicionales"></textarea>

        <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('candidatos.index') }}" class="btn btn-danger">Cancelar</a>
        <button type="submit">Guardar</button>
        </div>
    </form>
</body>
</html>
