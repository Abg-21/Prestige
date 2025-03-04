<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Editar Rol: {{ $role->name }}</h2>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Permisos</label>
                <div class="form-check">
                    @foreach($permissions as $permission)
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    @if($role && method_exists($role, 'hasPermissionTo') && $role->hasPermissionTo($permission->name)) checked @endif
                    class="form-check-input">

                        <label class="form-check-label">{{ $permission->name }}</label><br>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
