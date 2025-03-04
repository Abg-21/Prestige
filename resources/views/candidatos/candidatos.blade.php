<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Candidatos</title>
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


    <h1>Listado de Candidatos</h1>
    <a href="{{ route('candidatos.create') }}">Crear Candidato</a>
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidatos as $candidato)
                <tr>
                    <td>{{ $candidato->idCandidatos }}</td>
                    <td>{{ $candidato->Nombre }}</td>
                    <td>{{ $candidato->puesto->Puesto ?? 'Sin asignar' }}</td>
                    <td>{{ $candidato->Correo }}</td>
                    <td>
                        <a href="{{ route('candidatos.edit', $candidato) }}">Editar</a>
                        <form action="{{ route('candidatos.destroy', $candidato) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                        <form action="{{ route('empleados.store') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="idCandidatos" value="{{ $candidato->idCandidatos }}">
                            <button type="submit" onclick="return confirm('¿Seguro que deseas aprobar al candidato?')">Aprobar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
