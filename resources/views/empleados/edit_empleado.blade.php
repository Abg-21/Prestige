<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
</head>
<body>
    <h1>Editar Empleado</h1>
    <form action="{{ route('empleados.update', $empleado->idEmpleado) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="Nombre">Nombre:</label>
        <input type="text" id="Nombre" name="Nombre" value="{{ $empleado->Nombre }}">
        <label for="Correo">Correo:</label>
        <input type="email" id="Correo" name="Correo" value="{{ $empleado->Correo }}">
        <button type="submit">Guardar cambios</button>
    </form>
    <a href="{{ route('empleados.index') }}">Cancelar</a>
</body>
</html>
