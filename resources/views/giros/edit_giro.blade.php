<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
    <title>Editar Giro</title>
</head>
<body>
    <div class="container">
        <h1>Editar Giro</h1>

        <form action="{{ route('giros.update', ['giro' => $giro->idGiros]) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="Nombre">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $giro->Nombre) }}" required>
            @error('Nombre')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label for="Descripcion">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion">{{ old('Descripcion', $giro->Descripcion) }}</textarea>
            @error('Descripcion')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn">Actualizar Giro</button>
        </form>
    </div>
</body>
</html>
