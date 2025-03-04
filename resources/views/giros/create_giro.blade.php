<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
    <title>Crear Nuevo Giro</title>
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Giro</h1>

        <form action="{{ route('giros.store') }}" method="POST">
            @csrf

            <label for="Nombre">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" required>
            @error('Nombre')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <label for="Descripcion">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion">{{ old('Descripcion') }}</textarea>
            @error('Descripcion')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn">Crear Giro</button>
        </form>
    </div>
</body>
</html>
