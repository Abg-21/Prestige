<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/st.css') }}">
    <title>Lista de Giros</title>
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

    <div class="container">
        <h1>Lista de Giros</h1>

        <a href="{{ route('giros.create') }}" class="btn">Crear Nuevo Giro</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($giros as $giro)
                <tr>
                    <td>{{ $giro->idGiros }}</td>
                    <td>{{ $giro->Nombre }}</td>
                    <td>{{ $giro->Descripcion }}</td>
                    <td>
                        <a href="{{ route('giros.edit', ['giro' => $giro->idGiros]) }}" class="btn-small">Editar</a>
                        <form id="deleteForm-{{ $giro->idGiros }}" action="{{ route('giros.destroy', ['giro' => $giro->idGiros]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-small" onclick="confirmDelete({{ $giro->idGiros }})">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($errors->any())
    <div class="alert alert-warning">
        {{ $errors->first() }}
        @if (session('giroIdError'))
            <button 
                type="button" 
                onclick="confirmDelete({{ session('giroIdError') }})">
                Proceder de todas formas
            </button>
        @endif
    </div>
    @endif
    <!-- Enlace al archivo JavaScript externo -->
    <script src="{{ asset('js/giro.js') }}"></script>
</body>
</html>
