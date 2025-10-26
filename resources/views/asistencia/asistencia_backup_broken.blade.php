@php
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
@endphp

<div style="padding: 20px;">
    {{-- Encabezado --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #333;">
            📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }} 
            <small style="font-size: 12px; color: #666;">(v2.1 - {{ now()->format('H:i:s') }})</small>
        </h2>
        
        {{-- Botones de acción --}}
        <div style="display: flex; gap: 10px;">
            <button id="guardar-asistencia" class="btn btn-success" style="padding: 8px 16px;">
                💾 Guardar Asistencia
            </button>
            
            <button id="generar-reporte" class="btn btn-info" onclick="descargarReporteDirecto()" style="padding: 8px 16px;">
                📊 Generar Reporte
            </button>
        </div>
    </div>

    {{-- Filtros --}}
    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            {{-- Filtro por Estado --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Estado/Zona:</label>
                <select id="estado-select" style="padding: 5px; min-width: 150px;">
                    <option value="todos" {{ $estado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                    @foreach($estados as $est)
                        <option value="{{ $est }}" {{ $estado === $est ? 'selected' : '' }}>{{ $est }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filtro por Mes --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Mes:</label>
                <select id="mes-select" style="padding: 5px;">
                    @php
                        $mesesEspanol = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                    @endphp
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                            {{ $mesesEspanol[$i] }}
                        </option>
                    @endfor
                </select>
            </div>
            
            {{-- Filtro por Año --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Año:</label>
                <select id="año-select" style="padding: 5px;">
                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            {{-- Filtro por Período --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Período:</label>
                <select id="periodo-select" style="padding: 5px;">
                    <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>
                        Primera Quincena (1-15)
                    </option>
                    <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>
                        Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})
                    </option>
                </select>
            </div>
            
            <button id="aplicar-filtros" class="btn btn-primary" style="padding: 8px 16px;">
                🔍 Aplicar Filtros
            </button>
        </div>
    </div>

    {{-- Leyenda de Estados --}}
    <div style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;">
        <h4 style="margin: 0 0 10px 0; color: #333;">📌 Leyenda de Estados:</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 12px;">
            @php
                $estados_asistencia = \App\Models\Asistencia::ESTADOS;
            @endphp
            @foreach($estados_asistencia as $key => $estado_info)
                <div style="display: flex; align-items: center; gap: 5px;">
                    <span style="background: {{ $estado_info['color'] }}; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;">
                        {{ $estado_info['icono'] }}
                    </span>
                    <span>{{ $estado_info['nombre'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Información del período actual --}}
    <div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
        <strong>📅 Período actual:</strong> 
        {{ $periodo === 'primera_quincena' ? 'Días 1 al 15' : 'Días 16 al ' . cal_days_in_month(CAL_GREGORIAN, $mes, $año) }} 
        de {{ DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $año }}
        <br>
        <strong>👥 Total empleados mostrados:</strong> {{ $empleados->count() }}
        @if($estado !== 'todos')
            <strong> • Estado seleccionado:</strong> {{ $estado }}
        @endif
    </div>

    {{-- Tabla de Asistencia --}}
    <div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead style="background: #447D9B; color: white; position: sticky; top: 0;">
                <tr>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Empleado</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 150px;">Cliente</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 120px;">Estado</th>
                    
                    {{-- Días del período --}}
                    @foreach($diasDelPeriodo as $dia)
                        <th style="padding: 5px; border: 1px solid #ddd; min-width: 40px; text-align: center;">
                            {{ $dia }}
                        </th>
                    @endforeach
                    
                    {{-- Columna de totales --}}
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 100px;">Totales</th>
                    
                    {{-- Datos financieros --}}
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Bono</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Finiquito</th>
                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                    @php
                        $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;
                        $totales = $asistencia ? $asistencia->calcularTotales() : [];
                    @endphp
                    <tr style="border-bottom: 1px solid #eee;">
                        {{-- Información del empleado --}}
                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">
                            {{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            @if($empleado->puesto && $empleado->puesto->cliente)
                                {{ $empleado->puesto->cliente->Nombre }}
                            @else
                                <span style="color: #dc3545;">Sin cliente asignado</span>
                            @endif
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            {{ $empleado->Estado ?? 'Sin estado' }}
                        </td>
                        
                        {{-- Días de asistencia --}}
                        @foreach($diasDelPeriodo as $dia)
                            @php
                                $valorDia = $asistencia ? $asistencia->getAsistenciaDia($dia) : null;
                                // $color = $asistencia ? $asistencia->getColorDia($dia) : '#f8f9fa';
                                // $icono = $asistencia ? $asistencia->getIconoDia($dia) : '';
                                $color = '#f8f9fa'; // Color por defecto
                                $icono = ''; // Icono por defecto
                                $esDiaActual = ($dia == $diaActual && $mes == $mes && $año == $año);
                            @endphp
                            <td style="padding: 2px; border: 1px solid #ddd; text-align: center;">
                                <div class="dia-asistencia {{ $esDiaActual ? 'dia-actual' : '' }}" 
                                     data-empleado="{{ $empleado->IdEmpleados }}" 
                                     data-dia="{{ $dia }}"
                                     data-asistencia-id="{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}"
                                     onclick="abrirModalEstadosDirecto({{ $empleado->IdEmpleados }}, {{ $dia }}, '{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}')"
                                     style="background: {{ $color }}; 
                                            color: {{ in_array($color, ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }}; 
                                            padding: 5px; 
                                            border-radius: 3px; 
                                            cursor: pointer; 
                                            cursor: pointer; 
                                            font-weight: bold;
                                            min-height: 25px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            user-select: none;
                                            position: relative;
                                            z-index: 1;"
                                     title="Click para seleccionar estado - Empleado: {{ $empleado->IdEmpleados }}, Día: {{ $dia }}, Asistencia: {{ $asistencia ? $asistencia->id : 'Sin crear' }}">
                                    {{ $icono ?: '?' }}
                                </div>
                            </td>
                        @endforeach
                        
                        {{-- Columna de totales --}}
                        <td style="padding: 8px; border: 1px solid #ddd; font-size: 10px;">
                            @if($asistencia && !empty($totales))
                                @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)
                                    @if(($totales[$key] ?? 0) > 0)
                                        <div style="color: {{ $estado_info['color'] }}; font-weight: bold;">
                                            {{ $estado_info['icono'] }}: {{ $totales[$key] }}
                                        </div>
                                    @endif
                                @endforeach
                                @if(($totales['sin_marcar'] ?? 0) > 0)
                                    <div style="color: #6c757d;">Sin marcar: {{ $totales['sin_marcar'] }}</div>
                                @endif
                            @endif
                        </td>
                        
                        {{-- Datos financieros --}}
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financiero-input" 
                                   data-campo="bono"
                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"
                                   value="{{ $asistencia ? $asistencia->bono : '' }}" 
                                   style="width: 70px; font-size: 11px;" 
                                   step="0.01" min="0">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financiero-input" 
                                   data-campo="prestamo"
                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"
                                   value="{{ $asistencia ? $asistencia->prestamo : '' }}" 
                                   style="width: 70px; font-size: 11px;" 
                                   step="0.01" min="0">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financiero-input" 
                                   data-campo="fonacot"
                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"
                                   value="{{ $asistencia ? $asistencia->fonacot : '' }}" 
                                   style="width: 70px; font-size: 11px;" 
                                   step="0.01" min="0">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financiero-input" 
                                   data-campo="estatus_finiquito"
                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"
                                   value="{{ $asistencia ? $asistencia->estatus_finiquito : '' }}" 
                                   style="width: 70px; font-size: 11px;" 
                                   step="0.01" min="0">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="text" 
                                   class="form-control financiero-input" 
                                   data-campo="observaciones"
                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"
                                   value="{{ $asistencia ? $asistencia->observaciones : '' }}" 
                                   style="width: 180px; font-size: 11px;" 
                                   maxlength="180"
                                   placeholder="Observaciones...">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($diasDelPeriodo) + 8 }}" style="padding: 20px; text-align: center; color: #666;">
                            No se encontraron empleados activos{{ $estado !== 'todos' ? ' para el estado: ' . $estado : '' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Variables JavaScript seguras --}}
<script>
    // Definir variables globales desde PHP de forma segura
    window.CONFIG_ASISTENCIA = {
        año: {{ json_encode($año) }},
        mes: {{ json_encode($mes) }},
        periodo: {{ json_encode($periodo) }},
        estado: {{ json_encode($estado) }},
        csrf_token: {{ json_encode(csrf_token()) }}
    };
    
    // Definir estados de asistencia para JavaScript
    window.ESTADOS_ASISTENCIA = {!! json_encode(\App\Models\Asistencia::ESTADOS) !!};
</script>

{{-- JavaScript corregido --}}
<script src="{{ asset('js/asistencia-corregido.js') }}"></script>

{{-- Modal para seleccionar estado de asistencia --}}
<div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h3 style="margin: 0;">Seleccionar Estado de Asistencia</h3>
            <button id="cerrar-modal" onclick="cerrarModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>
        </div>
        <div id="estado-buttons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
            @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)
                <button type="button" 
                        class="estado-btn" 
                        data-estado="{{ $key }}"
                        onclick="seleccionarEstadoDirecto('{{ $key }}')"
                        style="background: {{ $estado_info['color'] }}; 
                               color: {{ in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }}; 
                               border: none; 
                               padding: 10px; 
                               font-weight: bold;
                               border-radius: 5px;
                               cursor: pointer;
                               transition: all 0.2s;">
                    {{ $estado_info['icono'] }} {{ $estado_info['nombre'] }}
                </button>
            @endforeach
            <button type="button" 
                    class="estado-btn" 
                    data-estado=""
                    onclick="seleccionarEstadoDirecto('')"
                    style="background: #6c757d; 
                           color: #fff; 
                           border: none; 
                           padding: 10px; 
                           font-weight: bold;
                           border-radius: 5px;
                           cursor: pointer;
                           transition: all 0.2s;">
                🚫 Limpiar
            </button>
        </div>
    </div>
</div>

{{-- Modal para generar reporte --}}
<div id="reporteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h3 style="margin: 0;">📊 Generar Reporte de Asistencia</h3>
            <button id="cerrar-reporte-modal" onclick="cerrarReporteModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>
        </div>
        <form id="reporte-form">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Año:</label>
                    <select name="año" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mes:</label>
                    <select name="mes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        @php
                            $mesesEspanol = [
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ];
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                                {{ $mesesEspanol[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Período:</label>
                    <select name="periodo" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="primera_quincena">Primera Quincena (1-15)</option>
                        <option value="segunda_quincena">Segunda Quincena (16-30/31)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Estado:</label>
                    <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="todos">Todos los Estados</option>
                        @foreach($estados as $est)
                            <option value="{{ $est }}">{{ $est }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">Formato:</label>
                <div>
                    <label style="margin-right: 20px; cursor: pointer;">
                        <input type="radio" name="formato" value="excel" checked style="margin-right: 5px;"> 📊 Excel
                    </label>
                    <label style="cursor: pointer;">
                        <input type="radio" name="formato" value="pdf" style="margin-right: 5px;"> 📄 PDF
                    </label>
                </div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" id="cancelar-reporte" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancelar</button>
                <button type="button" id="descargar-reporte" onclick="descargarReporteConfirmado()" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">📥 Descargar Reporte</button>
            </div>
        </form>
    </div>
</div>

<style>
.dia-asistencia:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.financiero-input {
    border: 1px solid #ddd;
    padding: 2px 4px;
    border-radius: 3px;
}

.financiero-input:focus {
    border-color: #007bff;
    outline: none;
}

.estado-btn:hover {
    opacity: 0.8;
    transform: scale(1.02);
}

/* Estilos para los modales */
#estadoModal, #reporteModal {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

#estadoModal > div, #reporteModal > div {
    animation: slideIn 0.3s ease-in-out;
}

@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Animación para el día actual */
@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.dia-actual {
    animation: blink 2s infinite;
    border: 2px solid #007bff !important;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}
</style>
    console.log('🎯 Cerrando modal de reporte directamente...');
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal reporte cerrado');
    }
}

function descargarReporteConfirmado() {
    console.log('📊 Iniciando descarga de reporte...');
    
    const form = document.getElementById('reporte-form');
    if (!form) {
        console.error('❌ Formulario de reporte no encontrado');
        alert('Error: Formulario no encontrado');
        return;
    }
    
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    console.log('📄 Parámetros de reporte:', Object.fromEntries(formData));
    
    const downloadUrl = "{{ route('asistencia.exportar') }}?" + params.toString();
    console.log('🔗 URL de descarga:', downloadUrl);
    
    // Abrir en nueva ventana para descarga
    window.open(downloadUrl, '_blank');
    
    // Cerrar modal
    cerrarReporteModalDirecto();
}

function cerrarModalDirecto() {
    console.log('🎯 Cerrando modal directamente...');
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal cerrado');
    }
}

// Hacer función global
window.seleccionarEstadoDirecto = function(estado) {
    console.log('🎯 Estado seleccionado:', estado);
    
    if (!window.empleadoActual || !window.diaActual) {
        console.error('❌ Datos de empleado/día no disponibles');
        alert('Error: Datos no disponibles. Cierra el modal e intenta de nuevo.');
        return;
    }
    
    // Mostrar indicador de carga
    const botones = document.querySelectorAll('.estado-btn');
    botones.forEach(btn => btn.disabled = true);
    
    // Enviar actualización con datos completos
    fetch("{{ route('asistencia.update') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
        },
        body: JSON.stringify({
            empleado_id: window.empleadoActual,
            año: window.CONFIG_ASISTENCIA.año,
            mes: window.CONFIG_ASISTENCIA.mes,
            periodo: window.CONFIG_ASISTENCIA.periodo,
            campo: 'dia_' + window.diaActual,
            valor: estado,
            crear_si_no_existe: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Estado actualizado exitosamente');
            
            // Actualizar visualmente la casilla sin recargar
            const casilla = document.querySelector(`[data-empleado="${window.empleadoActual}"][data-dia="${window.diaActual}"]`);
            if (casilla) {
                // Obtener info del estado
                const estados = {
                    'presente': { color: '#28a745', icono: '✓' },
                    'falta': { color: '#dc3545', icono: '✗' },
                    'prima_dominical': { color: '#fd7e14', icono: 'PD' },
                    'bajas': { color: '#e83e8c', icono: 'B' },
                    'alta': { color: '#ffc107', icono: 'A' },
                    'incapacidad': { color: '#20c997', icono: 'I' },
                    'prestamo': { color: '#007bff', icono: 'P' },
                    'vacante': { color: '#343a40', icono: 'V' },
                    'vacaciones': { color: '#6f42c1', icono: 'VAC' }
                };
                
                const estadoInfo = estados[estado];
                if (estadoInfo) {
                    casilla.style.backgroundColor = estadoInfo.color;
                    casilla.style.color = 'white';
                    casilla.textContent = estadoInfo.icono;
                    
                    // Reconfigurar evento de la casilla actualizada
                    casilla.onclick = function() {
                        const empleadoId = this.getAttribute('data-empleado');
                        const dia = this.getAttribute('data-dia');
                        const asistenciaId = this.getAttribute('data-asistencia');
                        abrirModalEstadosDirecto(empleadoId, dia, asistenciaId);
                    };
                    
                    console.log('📱 Casilla actualizada visualmente y evento reconfigurado');
                }
            }
            
            cerrarModalDirecto();
            
            // Reactivar botones del modal para próximo uso
            setTimeout(() => {
                const botones = document.querySelectorAll('.estado-btn');
                botones.forEach(btn => btn.disabled = false);
                console.log('🔄 Botones del modal reactivados');
            }, 500);
        } else {
            console.error('❌ Error en actualización:', data.message);
            alert('Error: ' + data.message);
            // Reactivar botones
            botones.forEach(btn => btn.disabled = false);
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alert('Error de conexión. Intenta de nuevo.');
        // Reactivar botones
        botones.forEach(btn => btn.disabled = false);
    });
}

function aplicarFiltrosDirecto() {
    console.log('🎯 Aplicando filtros vía AJAX...');
    
    const estado = document.getElementById('estado-select')?.value || 'todos';
    const año = document.getElementById('año-select')?.value || window.CONFIG_ASISTENCIA.año;
    const mes = document.getElementById('mes-select')?.value || window.CONFIG_ASISTENCIA.mes;
    const periodo = document.getElementById('periodo-select')?.value || window.CONFIG_ASISTENCIA.periodo;
    
    console.log('📊 Filtros:', {estado, año, mes, periodo});
    
    // Mostrar indicador de carga
    const btnFiltros = document.getElementById('aplicar-filtros');
    if (btnFiltros) {
        btnFiltros.innerHTML = '⏳ Aplicando...';
        btnFiltros.disabled = true;
    }
    
    // Mostrar indicador en la tabla también
    const tablaContainer = document.querySelector('table').parentElement;
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<div style="text-align: center; padding: 20px; font-size: 16px;">⏳ Cargando datos filtrados...</div>';
    loadingIndicator.id = 'loading-indicator';
    tablaContainer.appendChild(loadingIndicator);
    
    // Aplicar filtros usando AJAX
    fetch("{{ route('asistencia.aplicar-filtros') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            estado: estado,
            año: año,
            mes: mes,
            periodo: periodo
        })
    })
    .then(response => {
        console.log('🌐 Respuesta recibida. Status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📥 Respuesta AJAX recibida:', data);
        
        if (data.success) {
            // Actualizar la tabla con el nuevo HTML
            const tablaContainer = document.querySelector('div[style*="overflow-x: auto; border: 1px solid #ddd"]');
            console.log('📋 Contenedor de tabla encontrado:', !!tablaContainer);
            
            if (tablaContainer && data.tableHtml) {
                console.log('🔄 Reemplazando contenido HTML...');
                tablaContainer.innerHTML = data.tableHtml;
                console.log('✅ Contenido HTML reemplazado');
            } else {
                console.error('❌ No se pudo actualizar: container=' + !!tablaContainer + ', html=' + !!data.tableHtml);
            }
            
            // Actualizar el encabezado con el período
            const periodoTexto = periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena';
            const encabezado = document.querySelector('h2');
            if (encabezado) {
                const ahora = new Date();
                const tiempo = ahora.getHours().toString().padStart(2, '0') + ':' + ahora.getMinutes().toString().padStart(2, '0') + ':' + ahora.getSeconds().toString().padStart(2, '0');
                encabezado.innerHTML = `📋 Control de Asistencia - ${periodoTexto} <small style="font-size: 12px; color: #666;">(v2.1 - ${tiempo})</small>`;
            }
            
            console.log('✅ Tabla actualizada con', data.empleados_count, 'empleados');
            
            // Reconfigurar eventos de las nuevas casillas
            configurarEventosCasillas();
        } else {
            console.error('❌ Error en filtros:', data.message);
            alert('Error al aplicar filtros: ' + data.message);
        }
    })
    .catch(error => {
        console.error('❌ Error AJAX:', error);
        alert('Error de conexión al aplicar filtros');
    })
    .finally(() => {
        // Restaurar botón
        if (btnFiltros) {
            btnFiltros.innerHTML = '🔍 Aplicar Filtros';
            btnFiltros.disabled = false;
        }
        
        // Remover indicador de carga
        const loading = document.getElementById('loading-indicator');
        if (loading) {
            loading.remove();
        }
    });
}
            
            console.log('🎯 Eventos reconfigurados después de filtros');
        } else {
            console.warn('⚠️ No se pudo encontrar la tabla');
            console.log('Nueva tabla encontrada:', !!nuevaTabla);
            console.log('Tabla actual encontrada:', !!tablaActual);
            
            // Fallback: recargar página solo si es necesario
            console.log('🚨 Fallback: redirigiendo...');
            window.location.href = "{{ route('asistencia.index') }}?" + params.toString();
        }
        
        // Restaurar botón (que se conserva porque no se reemplaza)
        if (btnFiltros) {
            btnFiltros.innerHTML = '🔍 Aplicar Filtros';
            btnFiltros.disabled = false;
        }
    })
    .catch(error => {
        console.error('❌ Error en AJAX:', error);
        // Fallback: recargar página
        location.reload();
    });
}

// Hacer función global
window.abrirModalEstadosDirecto = function(empleadoId, dia, asistenciaId) {
    console.log('🎯 Abriendo modal directo:', {empleadoId, dia, asistenciaId});
    
    // Configurar variables globales
    window.empleadoActual = empleadoId;
    window.diaActual = dia;
    window.asistenciaIdActual = asistenciaId;
    
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'block';
        modal.style.zIndex = '9999';
        console.log('✅ Modal abierto directamente');
    } else {
        console.error('❌ Modal no encontrado');
        alert('Error: No se puede abrir el modal. Refresca la página.');
    }
}

function descargarReporteDirecto() {
    console.log('📊 Descargando reporte directamente...');
    const reporteModal = document.getElementById('reporteModal');
    if (reporteModal) {
        reporteModal.style.display = 'block';
        reporteModal.style.zIndex = '9999';
        console.log('✅ Modal reporte abierto');
    } else {
        console.error('❌ Modal reporte no encontrado');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Asistencia JS iniciado'); // Debug inicial
    
    let empleadoActual, diaActual, asistenciaIdActual;

    // Elementos del DOM
    const estadoModal = document.getElementById('estadoModal');
    const reporteModal = document.getElementById('reporteModal');
    const cerrarModal = document.getElementById('cerrar-modal');
    const cerrarReporteModal = document.getElementById('cerrar-reporte-modal');
    const cancelarReporte = document.getElementById('cancelar-reporte');

    console.log('📋 Elementos encontrados:', {
        estadoModal: estadoModal ? 'Sí' : 'No',
        reporteModal: reporteModal ? 'Sí' : 'No',
        cerrarModal: cerrarModal ? 'Sí' : 'No'
    });

    // ==================== FUNCIONES MODAL ====================
    function mostrarModal(modal) {
        if (!modal) {
            console.error('❌ Modal no proporcionado');
            return;
        }
        
        console.log('🎯 Mostrando modal:', modal.id);
        modal.style.display = 'block';
        modal.style.zIndex = '9999';
        document.body.style.overflow = 'hidden'; // Prevenir scroll
        
        // Verificar que se mostró
        setTimeout(() => {
            if (modal.style.display === 'block') {
                console.log('✅ Modal confirmado visible');
            } else {
                console.error('❌ Modal no se mostró correctamente');
            }
        }, 100);
    }

    function ocultarModal(modal) {
        if (!modal) {
            console.error('❌ Modal no proporcionado para ocultar');
            return;
        }
        
        console.log('🎯 Ocultando modal:', modal.id);
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaurar scroll
    }

    // Event listeners para cerrar modales
    cerrarModal?.addEventListener('click', () => ocultarModal(estadoModal));
    cerrarReporteModal?.addEventListener('click', () => ocultarModal(reporteModal));
    cancelarReporte?.addEventListener('click', () => ocultarModal(reporteModal));

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        if (event.target === estadoModal) {
            ocultarModal(estadoModal);
        }
        if (event.target === reporteModal) {
            ocultarModal(reporteModal);
        }
    });

    // ==================== FILTROS ====================
    // Aplicar filtros - Versión simplificada y robusta
    function aplicarFiltros() {
        console.log('🎯 APLICANDO FILTROS - FUNCIÓN LLAMADA');
        
        const estado = document.getElementById('estado-select')?.value || 'todos';
        const año = document.getElementById('año-select')?.value || window.CONFIG_ASISTENCIA.año;
        const mes = document.getElementById('mes-select')?.value || window.CONFIG_ASISTENCIA.mes;
        const periodo = document.getElementById('periodo-select')?.value || window.CONFIG_ASISTENCIA.periodo;
        
        console.log('� Valores obtenidos:', {estado, año, mes, periodo});
        
        const url = "{{ route('asistencia.index') }}?estado=" + encodeURIComponent(estado) + 
                   "&año=" + encodeURIComponent(año) + 
                   "&mes=" + encodeURIComponent(mes) + 
                   "&periodo=" + encodeURIComponent(periodo) +
                   "&_t=" + Date.now(); // Cache buster
        
        console.log('🔗 URL final:', url);
        
        // Mostrar indicador de carga
        const btn = document.getElementById('aplicar-filtros');
        if (btn) {
            btn.innerHTML = '⏳ Aplicando...';
            btn.disabled = true;
        }
        
        window.location.href = url;
    }
    
    // Event listeners para el botón
    const btnFiltros = document.getElementById('aplicar-filtros');
    console.log('🔍 Botón filtros encontrado:', btnFiltros ? 'SÍ' : 'NO');
    
    if (btnFiltros) {
        // Múltiples event listeners para asegurar que funcione
        btnFiltros.onclick = function(e) {
            e.preventDefault();
            console.log('🎯 CLICK via onclick');
            aplicarFiltros();
        };
        
        btnFiltros.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🎯 CLICK via addEventListener');
            aplicarFiltros();
        });
        
        btnFiltros.addEventListener('mousedown', function(e) {
            console.log('🎯 MOUSEDOWN detectado');
        });
    } else {
        console.error('❌ BOTÓN NO ENCONTRADO');
        // Intentar con querySelector como respaldo
        const btnRespaldo = document.querySelector('[id="aplicar-filtros"]');
        console.log('🔄 Botón respaldo:', btnRespaldo ? 'ENCONTRADO' : 'NO ENCONTRADO');
    }

    // ==================== CASILLAS DE DÍAS ====================
    // Función para mostrar modal de estados
    function abrirModalEstados(empleadoId, dia, asistenciaId) {
        console.log('🎯 Abriendo modal para:', {empleadoId, dia, asistenciaId});
        
        empleadoActual = empleadoId;
        diaActual = dia;
        asistenciaIdActual = asistenciaId;
        
        const modal = document.getElementById('estadoModal');
        if (modal) {
            modal.style.display = 'block';
            console.log('✅ Modal mostrado');
        } else {
            console.error('❌ Modal no encontrado');
            alert('Error: No se puede abrir el selector de estados');
        }
    }
    
    // Configurar clicks en casillas - Método simplificado
    function configurarCasillas() {
        const casillas = document.querySelectorAll('.dia-asistencia');
        console.log('🔍 Total casillas encontradas:', casillas.length);
        
        if (casillas.length === 0) {
            console.error('❌ NO SE ENCONTRARON CASILLAS');
            return;
        }
        
        casillas.forEach((casilla, index) => {
            const empleadoId = casilla.getAttribute('data-empleado');
            const dia = casilla.getAttribute('data-dia');
            const asistenciaId = casilla.getAttribute('data-asistencia-id');
            
            console.log(`Configurando casilla ${index + 1}:`, {empleadoId, dia, asistenciaId});
            
            // Múltiples métodos de evento para garantizar funcionamiento
            casilla.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('🎯 CLICK via onclick en día:', dia);
                abrirModalEstados(empleadoId, dia, asistenciaId);
            };
            
            casilla.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('🎯 CLICK via addEventListener en día:', dia);
                abrirModalEstados(empleadoId, dia, asistenciaId);
            });
            
            // Visual feedback
            casilla.style.transition = 'all 0.2s ease';
            casilla.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.2)';
                console.log('🎯 Hover en día:', dia);
            });
            
            casilla.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });
    }
    
    // Event delegation como respaldo absoluto
    document.addEventListener('click', function(e) {
        const elemento = e.target;
        if (elemento.classList.contains('dia-asistencia') || elemento.closest('.dia-asistencia')) {
            const casilla = elemento.classList.contains('dia-asistencia') ? elemento : elemento.closest('.dia-asistencia');
            
            e.preventDefault();
            e.stopPropagation();
            
            const empleadoId = casilla.getAttribute('data-empleado');
            const dia = casilla.getAttribute('data-dia');
            const asistenciaId = casilla.getAttribute('data-asistencia-id');
            
            console.log('🎯 CLICK DELEGADO en día:', dia);
            abrirModalEstados(empleadoId, dia, asistenciaId);
        }
    });
    
    // Ejecutar configuración
    configurarCasillas();

    // Manejar selección de estado
    document.querySelectorAll('.estado-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const estado = this.dataset.estado;
            
            console.log('Estado seleccionado:', estado); // Debug
            console.log('Datos actuales:', {empleadoActual, diaActual, asistenciaIdActual}); // Debug
            
            // Preparar datos para envío
            const año = document.getElementById('año-select').value;
            const mes = document.getElementById('mes-select').value;
            const periodo = document.getElementById('periodo-select').value;
            
            // Llamar función de actualización
            actualizarAsistenciaCompleta(empleadoActual, diaActual, estado, año, mes, periodo, asistenciaIdActual);
            
            ocultarModal(estadoModal);
        });
    });

    // Función unificada para actualizar asistencia
    function actualizarAsistenciaCompleta(empleadoId, dia, estado, año, mes, periodo, asistenciaId) {
        console.log('Actualizando con datos:', {empleadoId, dia, estado, año, mes, periodo, asistenciaId}); // Debug
        
        const payload = {
            empleado_id: empleadoId,
            año: año,
            mes: mes,
            periodo: periodo,
            campo: 'dia_' + dia,
            valor: estado,
            crear_si_no_existe: true
        };
        
        // Solo agregar asistencia_id si existe y no es 'NO_ASISTENCIA'
        if (asistenciaId && asistenciaId !== 'NO_ASISTENCIA') {
            payload.asistencia_id = asistenciaId;
        }
        
        fetch("{{ route('asistencia.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del servidor:', data); // Debug
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar asistencia');
        });
    }

    // Actualizar asistencia via AJAX
    function actualizarAsistencia(asistenciaId, campo, valor) {
        console.log('Actualizando:', {asistenciaId, campo, valor}); // Debug
        
        fetch("{{ route('asistencia.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            },
            body: JSON.stringify({
                asistencia_id: asistenciaId,
                campo: campo,
                valor: valor
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recargar página para mostrar cambios
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar asistencia');
        });
    }

    // Manejar campos financieros
    document.querySelectorAll('.financiero-input').forEach(input => {
        input.addEventListener('blur', function() {
            const asistenciaId = this.dataset.asistenciaId;
            const campo = this.dataset.campo;
            const valor = this.value;
            
            if (asistenciaId) {
                fetch("{{ route('asistencia.financiero') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
                    },
                    body: JSON.stringify({
                        asistencia_id: asistenciaId,
                        [campo]: valor
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });

    // Guardar asistencia
    document.getElementById('guardar-asistencia').addEventListener('click', function() {
        fetch("{{ route('asistencia.guardar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CONFIG_ASISTENCIA.csrf_token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Asistencia guardada correctamente');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar asistencia');
        });
    });

    // Generar reporte
    document.getElementById('generar-reporte')?.addEventListener('click', function() {
        mostrarModal(reporteModal);
    });

    // Descargar reporte
    document.getElementById('descargar-reporte')?.addEventListener('click', function() {
        const formData = new FormData(document.getElementById('reporte-form'));
        const params = new URLSearchParams(formData);
        
        window.open("{{ route('asistencia.exportar') }}?" + params.toString(), '_blank');
        ocultarModal(reporteModal);
    });
    
    // ==================== FUNCIONES PARA RECONFIGURAR EVENTOS ====================
    function configurarEventosCasillas() {
        console.log('🔄 Reconfigurando eventos de casillas después de AJAX...');
        
        // Reconfigurar eventos de casillas
        document.querySelectorAll('.dia-asistencia').forEach(casilla => {
            casilla.onclick = function() {
                const empleadoId = this.getAttribute('data-empleado');
                const dia = this.getAttribute('data-dia');
                const asistenciaId = this.getAttribute('data-asistencia');
                abrirModalEstadosDirecto(empleadoId, dia, asistenciaId);
            };
        });
        
        console.log('✅ Eventos reconfigurados para', document.querySelectorAll('.dia-asistencia').length, 'casillas');
    }
    
    function configurarEventosFiltros() {
        console.log('🔄 === CONFIGURANDO EVENTOS DE FILTROS ===');
        
        // Reconfigurar botón de filtros
        const botonFiltros = document.getElementById('aplicar-filtros');
        console.log('🔍 Botón filtros encontrado:', !!botonFiltros);
        
        if (botonFiltros) {
            // Remover eventos anteriores
            botonFiltros.onclick = null;
            
            // Agregar nuevo evento
            botonFiltros.onclick = function(e) {
                e.preventDefault();
                console.log('🎯 CLICK EN FILTROS DETECTADO');
                aplicarFiltrosDirecto();
                return false;
            };
            
            console.log('✅ Evento onclick asignado al botón filtros');
        } else {
            console.error('❌ No se encontró el botón aplicar-filtros');
        }
        
        // Reconfigurar botón de reporte
        const botonReporte = document.getElementById('generar-reporte');
        if (botonReporte) {
            botonReporte.onclick = function() {
                mostrarModal(reporteModal);
            };
        }
        
        console.log('✅ Eventos de filtros reconfigurados');
    }

    // ==================== DELEGACIÓN DE EVENTOS PARA CASILLAS ====================
    // Usar delegación de eventos para que funcione incluso después de AJAX
    document.addEventListener('click', function(e) {
        // Eventos para casillas de asistencia
        if (e.target.classList.contains('dia-asistencia') || e.target.closest('.dia-asistencia')) {
            const casilla = e.target.classList.contains('dia-asistencia') ? e.target : e.target.closest('.dia-asistencia');
            const empleadoId = casilla.getAttribute('data-empleado');
            const dia = casilla.getAttribute('data-dia');
            const asistenciaId = casilla.getAttribute('data-asistencia');
            
            console.log('🎯 Click en casilla detectado:', {empleadoId, dia, asistenciaId});
            abrirModalEstadosDirecto(empleadoId, dia, asistenciaId);
            return;
        }
        
        // Eventos para botón de filtros
        if (e.target.id === 'aplicar-filtros') {
            e.preventDefault();
            console.log('🎯 Click en filtros detectado');
            aplicarFiltrosDirecto();
            return;
        }
    });

    // ==================== INICIALIZACIÓN FINAL ====================
    
    // Configurar eventos de filtros al cargar la página
    configurarEventosFiltros();
    
    // Test de función disponible globalmente
    window.testFiltros = function() {
        console.log('🧪 TEST DE FILTROS:');
        console.log('  - aplicarFiltrosDirecto existe:', typeof aplicarFiltrosDirecto);
        console.log('  - Botón filtros existe:', !!document.getElementById('aplicar-filtros'));
        aplicarFiltrosDirecto();
    };
    
    // Test de función para casillas
    window.testCasillas = function() {
        console.log('🧪 TEST DE CASILLAS:');
        const casillas = document.querySelectorAll('.dia-asistencia');
        console.log('  - Casillas encontradas:', casillas.length);
        if (casillas.length > 0) {
            console.log('  - Primera casilla:', casillas[0]);
            console.log('  - Datos de primera casilla:', {
                empleado: casillas[0].getAttribute('data-empleado'),
                dia: casillas[0].getAttribute('data-dia'),
                asistencia: casillas[0].getAttribute('data-asistencia')
            });
            
            // Simular clic en primera casilla
            console.log('🎯 Simulando clic en primera casilla...');
            casillas[0].click();
        }
    };
    
    // Test completo del sistema
    window.testCompleto = function() {
        console.log('🧪 === TEST COMPLETO DEL SISTEMA ===');
        
        // Test 1: Verificar elementos DOM
        console.log('1️⃣ Verificando elementos DOM...');
        console.log('   - Botón filtros:', !!document.getElementById('aplicar-filtros'));
        console.log('   - Modal estados:', !!document.getElementById('estadoModal'));
        console.log('   - Casillas:', document.querySelectorAll('.dia-asistencia').length);
        
        // Test 2: Verificar funciones globales
        console.log('2️⃣ Verificando funciones...');
        console.log('   - abrirModalEstadosDirecto:', typeof window.abrirModalEstadosDirecto !== 'undefined' ? '✅' : '❌');
        console.log('   - aplicarFiltrosDirecto:', typeof aplicarFiltrosDirecto !== 'undefined' ? '✅' : '❌');
        console.log('   - seleccionarEstadoDirecto:', typeof seleccionarEstadoDirecto !== 'undefined' ? '✅' : '❌');
        
        // Test 3: Probar filtros
        console.log('3️⃣ Probando filtros...');
        setTimeout(() => {
            const estado = document.getElementById('estado-select');
            if (estado) {
                estado.value = 'Chiapas';
                aplicarFiltrosDirecto();
                console.log('   - Filtro aplicado a Chiapas');
            }
        }, 1000);
    };
    
    console.log('🏁 ESTADO FINAL:');
    console.log('   - Casillas encontradas:', document.querySelectorAll('.dia-asistencia').length);
    console.log('   - Modal presente:', !!document.getElementById('estadoModal'));
    console.log('   - Botón filtros configurado:', !!document.getElementById('aplicar-filtros'));
    console.log('   - Tests disponibles: window.testFiltros(), window.testCasillas()');
    console.log('✅ INICIALIZACIÓN COMPLETA - Delegación de eventos activa');
});
FIN DEL JAVASCRIPT INLINE DESACTIVADO */
</script>