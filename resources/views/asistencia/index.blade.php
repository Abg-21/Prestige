@php
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
@endphp

{{-- Meta CSRF token como respaldo --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div style="padding: 20px;">
    {{-- Encabezado --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0; color: #333;">
                📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }}
            </h2>
            <div style="margin-top: 5px; font-size: 14px; color: #007bff;">
                📅 Hoy: {{ $nombreDiaActual ?? 'N/A' }}, {{ $diaActual ?? 'N/A' }} de {{ date('F Y') }} 
                | Semana {{ $semanaActual ?? 'N/A' }} del mes
                @if(isset($diaActual, $mesActual, $añoActual) && $mes == $mesActual && $año == $añoActual)
                    <span style="color: #28a745; font-weight: bold;">📍 (Mostrando mes actual)</span>
                @endif
            </div>
        </div>
        
        {{-- Botones de acción --}}
        <div style="display: flex; gap: 10px;">
            <button id="guardar-asistencia" class="btn btn-success" onclick="window.guardarAsistenciaCompleta()" style="padding: 8px 16px;">
                💾 Guardar Asistencia
            </button>
            
            <button id="generar-reporte" class="btn btn-info" onclick="window.abrirModalReporte()" style="padding: 8px 16px;">
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
                <select id="estado-select" name="estado" style="padding: 5px; min-width: 150px;">
                    <option value="todos" {{ $estado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                    @foreach($estados as $est)
                        <option value="{{ $est }}" {{ $estado === $est ? 'selected' : '' }}>{{ $est }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filtro por Mes --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Mes:</label>
                <select id="mes-select" name="mes" style="padding: 5px;">
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
            
            {{-- Filtro por Período --}}
            <div>
                <label style="font-weight: bold; margin-right: 5px;">Período:</label>
                <select id="periodo-select" name="periodo" style="padding: 5px;">
                    <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>
                        Primera Quincena (1-15)
                    </option>
                    <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>
                        Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})
                    </option>
                </select>
            </div>
            
            <button id="aplicar-filtros" class="btn btn-primary" onclick="window.aplicarFiltrosAsistencia()" style="padding: 8px 16px;">
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
    <div class="table-responsive" style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">
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
    // Debug: mostrar variables PHP
    console.log('🔍 Variables PHP recibidas:', {
        año: {{ json_encode($año ?? 'undefined') }},
        mes: {{ json_encode($mes ?? 'undefined') }},
        periodo: {{ json_encode($periodo ?? 'undefined') }},
        estado: {{ json_encode($estado ?? 'undefined') }}
    });

    // Definir variables globales desde PHP de forma segura
    window.CONFIG_ASISTENCIA = {
        año: {{ json_encode($año ?? date('Y')) }},
        mes: {{ json_encode($mes ?? date('n')) }},
        periodo: {{ json_encode($periodo ?? 'primera_quincena') }},
        estado: {{ json_encode($estado ?? 'todos') }},
        csrf_token: {{ json_encode(csrf_token()) }},
        fecha_actual: {
            dia: {{ json_encode($diaActual ?? null) }},
            mes: {{ json_encode($mesActual ?? null) }},
            año: {{ json_encode($añoActual ?? null) }},
            nombre_dia: {{ json_encode($nombreDiaActual ?? null) }},
            semana: {{ json_encode($semanaActual ?? null) }}
        }
    };
    
    // Debug: verificar que CONFIG se creó correctamente
    console.log('✅ CONFIG_ASISTENCIA creado:', window.CONFIG_ASISTENCIA);
    
    // Definir estados de asistencia para JavaScript
    window.ESTADOS_ASISTENCIA = {!! json_encode(\App\Models\Asistencia::ESTADOS) !!};
</script>

{{-- JavaScript corregido --}}
<script src="{{ asset('js/asistencia-fixed.js') }}"></script>

{{-- Modal para seleccionar estado de asistencia --}}
<div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h3 style="margin: 0;">Seleccionar Estado de Asistencia</h3>
            <button id="cerrar-modal" onclick="cerrarModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">×</button>
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
            <button id="cerrar-reporte-modal" onclick="cerrarReporteModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">×</button>
        </div>
        <form id="reporte-form">
            {{-- Filtros del modal de reportes (sin año) --}}
            <div style="margin-bottom: 15px;">
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
                <button type="button" id="descargar-reporte" onclick="window.descargarReporteConfirmado()" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">📥 Descargar Reporte</button>
            </div>
        </form>
    </div>
</div>

<style>
.dia-asistencia:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.dia-actual {
    animation: parpadeo 1.5s infinite;
    border: 2px solid #007bff !important;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
}

@keyframes parpadeo {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
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

/* Modal de confirmación de guardado */
.modal-confirmacion {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    animation: fadeIn 0.3s ease-in-out;
}

.modal-confirmacion-contenido {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    max-width: 90%;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.icono-exito {
    font-size: 48px;
    color: #28a745;
    margin-bottom: 15px;
}

.icono-error {
    font-size: 48px;
    color: #dc3545;
    margin-bottom: 15px;
}
</style>

{{-- Modal de confirmación de guardado --}}
<div id="modal-confirmacion" class="modal-confirmacion">
    <div class="modal-confirmacion-contenido">
        <div id="icono-resultado" class="icono-exito">✓</div>
        <h3 id="titulo-resultado">Guardado Exitoso</h3>
        <p id="mensaje-resultado">Los cambios se han guardado correctamente</p>
        <div id="detalle-resultado" style="margin: 15px 0; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 14px;"></div>
        <button id="cerrar-confirmacion" onclick="cerrarModalConfirmacion()" 
                style="background: #007bff; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; margin-top: 10px;">
            Cerrar
        </button>
    </div>
</div>