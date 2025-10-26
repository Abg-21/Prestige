<h1>Vista de Eliminados - Versión Simple</h1>

@if($eliminados->count() > 0)
    <h3>Registros eliminados encontrados: {{ $eliminados->count() }}</h3>
    
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <tr style="background: #FE7743; color: white;">
            <th>ID</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Eliminado por</th>
            <th>Fecha</th>
        </tr>
        @foreach($eliminados as $eliminado)
        <tr>
            <td>{{ $eliminado->idEliminados }}</td>
            <td>{{ $eliminado->eliminable_type ?? $eliminado->tipo }}</td>
            <td>
                @if($eliminado->eliminable)
                    {{ $eliminado->eliminable->Nombre ?? $eliminado->eliminable->nombre ?? 'N/A' }}
                @else
                    No disponible
                @endif
            </td>
            <td>{{ $eliminado->usuario->nombre ?? $eliminado->usuario->nombre_usuario ?? 'Sistema' }}</td>
            <td>{{ $eliminado->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>
@else
    <h3>No hay registros eliminados</h3>
@endif

<hr>
<p><a href="{{ route('eliminados.index') }}">Ir a vista completa</a></p>