<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Puestos</title>
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

    <h1>Lista de Puestos</h1>
    <a href="{{ route('puestos.create') }}">Crear nuevo Puesto</a>
    
    <table class="styled-table">
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Puesto</th>
                <th>Giro</th>
                <th>Cliente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($puestos->isEmpty())
                <tr>
                    <td colspan="5">No hay puestos registrados.</td>
                </tr>
            @else
                @foreach($puestos as $puesto)
                    <tr>
                        <td>{{ $puesto->Categoría }}</td>
                        <td>{{ $puesto->Puesto }}</td>
                        <td>{{ $puesto->giro ? $puesto->giro->Nombre : 'Sin giro' }}</td>
                        <td>{{ $puesto->cliente ? $puesto->cliente->Nombre : 'Sin cliente' }}</td>
                        <td>
                            <a href="{{ route('puestos.edit', $puesto->idPuestos) }}">Editar</a>
                            <form action="{{ route('puestos.destroy', $puesto->idPuestos) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este puesto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>
