<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title>
    <link rel="stylesheet" href="{{ asset('css/styles_general.css') }}">
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

    <h1>Listado de Empleados</h1>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->idEmpleado }}</td>
                    <td>{{ $empleado->Nombre }}</td>
                    <td>{{ $empleado->puesto->Puesto}}</td>
                    <td>{{ $empleado->Correo }}</td>
                    <td>
                        <a href="{{ route('empleados.show', $empleado->idEmpleado) }}">Ver</a>
                        <a href="{{ route('empleados.edit', $empleado->idEmpleado) }}">Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado->idEmpleado) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar al empleado?')">Eliminar</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
