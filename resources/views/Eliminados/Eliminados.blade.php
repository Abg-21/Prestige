<div style="padding: 20px;">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="margin: 0;">Registros Eliminados</h2>
        
        <select id="tipoRegistro" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px;">
            <option value="">Todos los registros</option>
            <option value="candidatos">Candidatos</option>
            <option value="empleados">Empleados</option>
            <option value="puestos">Puestos</option>
            <!-- Añade más opciones según tus tablas -->
        </select>
    </div>

    <table style="width:100%; border-collapse: collapse; background: #fff;">
        <thead>
            <tr style="background: #FE7743; color: #fff;">
                <th style="padding: 8px; border: 1px solid #ddd;">Tipo</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Nombre/Identificador</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Motivo</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Eliminado Por</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Fecha Eliminación</th>
                <th style="padding: 8px; border: 1px solid #ddd; width: 150px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eliminados as $eliminado)
                <tr class="registro-eliminado" data-tipo="{{ strtolower($eliminado->tipo) }}">
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $eliminado->tipo }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        @if($eliminado->tipo === 'Candidato')
                            {{ $eliminado->eliminable->Nombre }} {{ $eliminado->eliminable->Apellido_P }}
                        @elseif($eliminado->tipo === 'Empleado')
                            {{ $eliminado->eliminable->Nombre }} {{ $eliminado->eliminable->Apellido_P }}
                        @else
                            {{ $eliminado->eliminable->nombre ?? 'N/A' }}
                        @endif
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $eliminado->motivo }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $eliminado->usuario->nombre_usuario }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        {{ $eliminado->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 12px;">
                            <button 
                                onclick="verDetalle('{{ $eliminado->tipo }}', {{ $eliminado->id }})"
                                style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('images/ver.png') }}" alt="Ver" style="width: 32px; height: 32px;">
                                <div style="font-size: 13px; color: #555;">Ver</div>
                            </button>
                            
                            <button 
                                onclick="confirmarRestauracion('{{ $eliminado->tipo }}', {{ $eliminado->id }}, '{{ $eliminado->eliminable->Nombre ?? 'este registro' }}')"
                                style="background: none; border: none; padding: 5px; cursor: pointer; display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('images/restaurar.png') }}" alt="Restaurar" style="width: 32px; height: 32px;">
                                <div style="font-size: 13px; color: #4CAF50;">Restaurar</div>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tipoRegistro = document.getElementById('tipoRegistro');
    const rows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const tipoSeleccionado = tipoRegistro.value.toLowerCase();

        rows.forEach(row => {
            const tipo = row.dataset.tipo;
            const text = row.textContent.toLowerCase();
            const matchesTipo = tipoSeleccionado === '' || tipo === tipoSeleccionado;
            const matchesSearch = text.includes(searchTerm);

            if (matchesTipo && matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    tipoRegistro.addEventListener('change', filterTable);
});
</script>