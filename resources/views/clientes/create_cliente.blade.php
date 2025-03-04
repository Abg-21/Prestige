<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Cliente</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
</head>
<body>
    <h1>Crear Nuevo Cliente</h1>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <label for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" required>
        @error('Nombre')
            <div>{{ $message }}</div>
        @enderror

        <label for="Telefono">Teléfono:</label>
        <input type="text" name="Telefono" id="Telefono" value="{{ old('Telefono') }}">
        @error('Telefono')
            <div>{{ $message }}</div>
        @enderror

        <label for="Descripcion">Descripción:</label>
        <input type="text" name="Descripcion" id="Descripcion" value="{{ old('Descripcion') }}">
        @error('Descripcion')
            <div>{{ $message }}</div>
        @enderror

        <button type="submit">Crear Cliente</button>
    </form>
</body>
</html>
