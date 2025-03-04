<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Empleado</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
</head>
<body>
    <h1>Detalles del Empleado</h1>
    <p><strong>ID:</strong> {{ $empleado->idEmpleado }}</p>
    <p><strong>Nombre:</strong> {{ $empleado->Nombre }}</p>
    <p><strong>Puesto:</strong> {{ $empleado->puesto->Puesto }}</p>
    <p><strong>Email:</strong> {{ $empleado->Correo }}</p>
    <p><strong>Comentarios:</strong> {{ $empleado->Comentarios }}</p>
    <a href="{{ route('empleados.index') }}">Volver al listado</a>
</body>
</html>