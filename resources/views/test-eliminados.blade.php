<!DOCTYPE html>
<html>
<head>
    <title>Test Eliminados</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Prueba de Eliminados</h1>
    
    <div>
        <h3>Información de Usuario:</h3>
        <p>Usuario logueado: {{ auth()->check() ? 'SI' : 'NO' }}</p>
        @if(auth()->check())
            <p>ID Usuario: {{ auth()->id() }}</p>
            <p>Nombre: {{ auth()->user()->nombre ?? auth()->user()->name ?? 'N/A' }}</p>
            <p>Roles: {{ auth()->user()->roles->pluck('nombre')->join(', ') ?? 'Sin roles' }}</p>
        @endif
    </div>
    
    <div>
        <h3>Permisos:</h3>
        <p>Permiso eliminados: {{ \App\Helpers\PermissionHelper::hasPermission('eliminados') ? 'SI' : 'NO' }}</p>
        <p>Permiso eliminados.ver: {{ \App\Helpers\PermissionHelper::hasPermission('eliminados', 'ver') ? 'SI' : 'NO' }}</p>
    </div>
    
    <div>
        <h3>Datos de Eliminados:</h3>
        <p>Total registros: {{ \App\Models\Eliminado::count() }}</p>
        <p>No restaurados: {{ \App\Models\Eliminado::whereNull('restaurado_en')->count() }}</p>
    </div>
    
    <div>
        <h3>Prueba de Ruta:</h3>
        <a href="{{ route('eliminados.index') }}">Ir a Eliminados</a>
    </div>
</body>
</html>