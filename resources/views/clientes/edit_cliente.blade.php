<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
</head>
<body>
    <h1>Editar Cliente</h1>

    <form action="{{ route('clientes.update', ['cliente' => $cliente->idClientes]) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $cliente->Nombre) }}" required>
        @error('Nombre')
            <div>{{ $message }}</div>
        @enderror

        <label for="Telefono">Teléfono:</label>
        <input type="text" name="Telefono" id="Telefono" value="{{ old('Telefono', $cliente->Telefono) }}">
        @error('Telefono')
            <div>{{ $message }}</div>
        @enderror

        <label for="Descripcion">Descripción:</label>
        <input type="text" name="Descripcion" id="Descripcion" value="{{ old('Descripcion', $cliente->Descripcion) }}">
        @error('Descripcion')
            <div>{{ $message }}</div>
        @enderror

        <button type="submit">Actualizar Cliente</button>
    </form>
</body>
</html>
