<meta name="csrf-token" content="{{ csrf_token() }}">
<div style="padding: 20px;">
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="margin: 0; display: flex; align-items: center;">
            <img src="{{ asset('images/eliminar.png') }}" alt="Eliminados" style="width: 32px; height: 32px; margin-right: 10px;">
            Registros Eliminados
        </h2>
        
        <div style="display: flex; gap: 15px; align-items: end;">
            <div>
                <label for="searchInput" style="font-size: 14px; margin-bottom: 5px; display: block;">Buscar:</label>
                <input type="text" id="searchInput" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 250px;" placeholder="Buscar registros...">
            </div>
            <div>
                <label for="tipoRegistro" style="font-size: 14px; margin-bottom: 5px; display: block;">Filtrar por tipo:</label>
                <select id="tipoRegistro" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px;">
                    <option value="">Todos los registros</option>
                    <option value="Candidato">Candidatos</option>
                    <option value="Empleado">Empleados</option>
                    <option value="Usuario">Usuarios</option>
                    <option value="Cliente">Clientes</option>
                    <option value="Puesto">Puestos</option>
                </select>
            </div>
            <button type="button" onclick="limpiarFiltrosEliminados()" style="background: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; height: 42px;">
                Limpiar
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div id="tabla-eliminados-container">
        @include('Eliminados.tabla')
    </div>
</div>

<!-- Modal simple para confirmar restauración -->
<div id="modalConfirmarRestauracion" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; padding: 20px; min-width: 400px;">
        <h4 style="margin: 0 0 15px 0; color: #FE7743;">Confirmar Restauración</h4>
        <p>¿Está seguro de que desea restaurar <strong id="nombreRegistroModal"></strong>?</p>
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
            <button onclick="cerrarModalEliminados()" style="background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Cancelar</button>
            <button id="btnConfirmarRestauracion" style="background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Restaurar</button>
        </div>
    </div>
</div>

<script>
// Variables globales para evitar conflictos
var eliminadosTimeoutBusqueda;

function initEliminados() {
    console.log('Inicializando módulo de eliminados');
    
    const searchInput = document.getElementById('searchInput');
    const tipoRegistro = document.getElementById('tipoRegistro');

    if (searchInput && tipoRegistro) {
        // Filtro de búsqueda con debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(eliminadosTimeoutBusqueda);
            eliminadosTimeoutBusqueda = setTimeout(filtrarTablaEliminados, 300);
        });
        
        // Filtro por tipo
        tipoRegistro.addEventListener('change', filtrarTablaEliminados);
    }
}

function filtrarTablaEliminados() {
    const searchInput = document.getElementById('searchInput');
    const tipoRegistro = document.getElementById('tipoRegistro');
    
    if (!searchInput || !tipoRegistro) return;
    
    const searchTerm = searchInput.value;
    const tipoSeleccionado = tipoRegistro.value;
    
    const params = new URLSearchParams();
    if (searchTerm) params.append('buscar', searchTerm);
    if (tipoSeleccionado) params.append('tipo', tipoSeleccionado);
    
    fetch('/prestige/public/eliminados?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('tabla-eliminados-container');
        if (container && data.html) {
            container.innerHTML = data.html;
        }
    })
    .catch(error => {
        console.error('Error al filtrar eliminados:', error);
    });
}

function limpiarFiltrosEliminados() {
    const searchInput = document.getElementById('searchInput');
    const tipoRegistro = document.getElementById('tipoRegistro');
    
    if (searchInput) searchInput.value = '';
    if (tipoRegistro) tipoRegistro.value = '';
    
    filtrarTablaEliminados();
}

function mostrarModalEliminados() {
    document.getElementById('modalConfirmarRestauracion').style.display = 'block';
}

function cerrarModalEliminados() {
    document.getElementById('modalConfirmarRestauracion').style.display = 'none';
}

function confirmarRestauracionEliminados(tipo, id, nombre) {
    document.getElementById('nombreRegistroModal').textContent = nombre;
    
    const btnConfirmar = document.getElementById('btnConfirmarRestauracion');
    btnConfirmar.onclick = function() {
        restaurarRegistroEliminados(tipo, id);
    };
    
    mostrarModalEliminados();
}

function restaurarRegistroEliminados(tipo, id) {
    const btnConfirmar = document.getElementById('btnConfirmarRestauracion');
    const originalText = btnConfirmar.innerHTML;
    
    btnConfirmar.innerHTML = 'Restaurando...';
    btnConfirmar.disabled = true;
    
    fetch('/prestige/public/eliminados/' + tipo + '/' + id + '/restaurar', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cerrarModalEliminados();
            
            // Mostrar mensaje de éxito
            const alertDiv = document.createElement('div');
            alertDiv.style.cssText = 'margin-bottom: 20px; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;';
            alertDiv.innerHTML = data.message;
            
            const container = document.querySelector('[style*="padding: 20px"]');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);
            }
            
            // Recargar tabla después de 2 segundos
            setTimeout(() => {
                filtrarTablaEliminados();
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 2000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error al restaurar el registro');
        console.error(error);
    })
    .finally(() => {
        btnConfirmar.innerHTML = originalText;
        btnConfirmar.disabled = false;
    });
}

// Inicializar cuando se carga el módulo
document.addEventListener('DOMContentLoaded', function() {
    initEliminados();
});

// También inicializar cuando se carga via AJAX
if (document.readyState === 'complete') {
    initEliminados();
}
</script>