<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
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

    <h1>Lista de Clientes</h1>

    <button type="button" onclick="window.location.href='{{ route('clientes.create') }}'">Crear nuevo cliente</button>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr>
                <td>{{ $cliente->idClientes }}</td>
                <td>{{ $cliente->Nombre }}</td>
                <td>{{ $cliente->Telefono }}</td>
                <td>{{ $cliente->Descripcion }}</td>
                <td>
                    <button type="button" onclick="window.location.href='{{ route('clientes.edit', ['cliente' => $cliente->idClientes]) }}'">Editar</button>
                    <form id="deleteForm-{{ $cliente->idClientes }}" action="{{ route('clientes.destroy', ['cliente' => $cliente->idClientes]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $cliente->idClientes }})">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        function confirmDelete(clienteId) {
            if (confirm('El cliente está relacionado con uno o más puestos, ¿seguro que deseas eliminarlo?')) {
                document.getElementById('deleteForm-' + clienteId).submit();
            }
        }
    </script>
</body>
</html>
