ELIMINADOS DEBUG - FUNCIONANDO

<h2>Usuario actual:</h2>
<p>Logueado: {{ auth()->check() ? 'SÍ' : 'NO' }}</p>
@if(auth()->check())
    <p>ID: {{ auth()->id() }}</p>
    <p>Nombre: {{ auth()->user()->nombre ?? auth()->user()->name ?? 'N/A' }}</p>
    <p>Roles: 
        @if(auth()->user()->roles)
            {{ auth()->user()->roles->pluck('nombre')->join(', ') }}
        @else
            Sin roles
        @endif
    </p>
@endif

<h2>Permisos:</h2>
<p>Eliminados: {{ \App\Helpers\PermissionHelper::hasPermission('eliminados') ? 'SÍ' : 'NO' }}</p>

<h2>Datos:</h2>
<p>Total eliminados: {{ \App\Models\Eliminado::count() }}</p>
<p>No restaurados: {{ \App\Models\Eliminado::whereNull('restaurado_en')->count() }}</p>

<h2>Test de controlador:</h2>
<a href="{{ route('eliminados.index') }}" style="background: #FE7743; color: white; padding: 10px; text-decoration: none; border-radius: 4px;">IR A ELIMINADOS</a>

<script>
console.log('Debug cargado correctamente');
</script>