<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Editar Usuario: {{ $user->name }}</h2>

        <form action="{{ route('usuarios.update', $user->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="role" class="form-label">Seleccionar Rol</label>
                <select name="role" id="role" class="form-control">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary ajax-link">Volver</a>
        </form>
    </div>
</body>
</html>
