<div style="padding: 20px;">
    {{-- Encabezado --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #333;">
            📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }}
        </h2>
        
        {{-- Filtros --}}
        <div style="display: flex; gap: 10px; align-items: center;">
            <select id="mes-select" style="padding: 5px;">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
            
            <select id="año-select" style="padding: 5px;">
                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            
            <select id="periodo-select" style="padding: 5px;">
                <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>
                    Primera Quincena (1-15)
                </option>
                <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>
                    Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})
                </option>
            </select>
            
            <button id="aplicar-filtros" class="btn btn-primary" style="padding: 5px 15px;">
                Aplicar
            </button>
        </div>
    </div>

    {{-- Información del período actual --}}
    <div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
        <strong>📅 Período actual:</strong> 
        {{ $periodo === 'primera_quincena' ? 'Días 1 al 15' : 'Días 16 al ' . cal_days_in_month(CAL_GREGORIAN, $mes, $año) }} 
        de {{ DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $año }}
        <br>
        <strong>👥 Total empleados:</strong> {{ $empleados->count() }}
    </div>

    {{-- Tabla de Asistencia --}}
    <div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead style="background: #447D9B; color: white; position: sticky; top: 0;">
                <tr>
                    <th rowspan="2" style="padding: 8px; border: 1px solid #ddd; min-width: 120px;">Empleado</th>
                    <th rowspan="2" style="padding: 8px; border: 1px solid #ddd; min-width: 80px;">Zona/Estado</th>
                    <th rowspan="2" style="padding: 8px; border: 1px solid #ddd; min-width: 100px;">Cliente</th>
                    <th colspan="{{ count($diasDelPeriodo) }}" style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        Días del Mes
                    </th>
                    <th colspan="5" style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                        Datos Financieros
                    </th>
                </tr>
                <tr>
                    {{-- Días --}}
                    @foreach($diasDelPeriodo as $dia)
                        <th style="padding: 4px; border: 1px solid #ddd; min-width: 35px; text-align: center; 
                                   {{ $dia == $diaActual ? 'background: #ff9800; color: white;' : '' }}">
                            {{ $dia }}
                        </th>
                    @endforeach
                    
                    {{-- Columnas financieras --}}
                    <th style="padding: 8px; border: 1px solid #ddd; min-width: 80px;">Bono</th>
                    <th style="padding: 8px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>
                    <th style="padding: 8px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>
                    <th style="padding: 8px; border: 1px solid #ddd; min-width: 90px;">Est. Finiquito</th>
                    <th style="padding: 8px; border: 1px solid #ddd; min-width: 150px;">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                    @php
                        $asistencia = $asistencias[$empleado->IdEmpleados];
                        $nombreCompleto = $empleado->Nombre . ' ' . $empleado->Apellido_Paterno . ' ' . $empleado->Apellido_Materno;
                        $cliente = $empleado->puesto->cliente->Nombre ?? 'Sin cliente';
                    @endphp
                    <tr style="border-bottom: 1px solid #eee;">
                        {{-- Información del empleado --}}
                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">
                            {{ $nombreCompleto }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            {{ $empleado->Estado }}
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            {{ $cliente }}
                        </td>
                        
                        {{-- Días de asistencia --}}
                        @foreach($diasDelPeriodo as $dia)
                            @php
                                $campo = 'dia_' . ($periodo === 'primera_quincena' ? $dia : ($dia - 15));
                                $asistenciaValor = $asistencia->$campo;
                                $claseColor = '';
                                $icono = '';
                                
                                if ($asistenciaValor === 1) {
                                    $claseColor = 'background: #4CAF50; color: white;'; // Verde para presente
                                    $icono = '✓';
                                } elseif ($asistenciaValor === 0) {
                                    $claseColor = 'background: #f44336; color: white;'; // Rojo para ausente
                                    $icono = '✗';
                                }
                            @endphp
                            <td style="padding: 2px; border: 1px solid #ddd; text-align: center; {{ $claseColor }}">
                                <button type="button" 
                                        class="asistencia-btn" 
                                        data-asistencia-id="{{ $asistencia->id }}"
                                        data-campo="{{ $campo }}"
                                        data-valor="{{ $asistenciaValor }}"
                                        style="border: none; background: transparent; width: 100%; height: 30px; cursor: pointer; font-size: 14px; font-weight: bold;">
                                    {{ $icono }}
                                </button>
                            </td>
                        @endforeach
                        
                        {{-- Campos financieros --}}
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financial-input" 
                                   data-asistencia-id="{{ $asistencia->id }}"
                                   data-campo="bono"
                                   value="{{ $asistencia->bono }}" 
                                   step="0.01" 
                                   style="width: 100%; border: none; padding: 2px; text-align: center;">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financial-input" 
                                   data-asistencia-id="{{ $asistencia->id }}"
                                   data-campo="prestamo"
                                   value="{{ $asistencia->prestamo }}" 
                                   step="0.01" 
                                   style="width: 100%; border: none; padding: 2px; text-align: center;">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financial-input" 
                                   data-asistencia-id="{{ $asistencia->id }}"
                                   data-campo="fonacot"
                                   value="{{ $asistencia->fonacot }}" 
                                   step="0.01" 
                                   style="width: 100%; border: none; padding: 2px; text-align: center;">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="number" 
                                   class="form-control financial-input" 
                                   data-asistencia-id="{{ $asistencia->id }}"
                                   data-campo="estatus_finiquito"
                                   value="{{ $asistencia->estatus_finiquito }}" 
                                   step="0.01" 
                                   style="width: 100%; border: none; padding: 2px; text-align: center;">
                        </td>
                        <td style="padding: 4px; border: 1px solid #ddd;">
                            <input type="text" 
                                   class="form-control financial-input" 
                                   data-asistencia-id="{{ $asistencia->id }}"
                                   data-campo="observaciones"
                                   value="{{ $asistencia->observaciones }}" 
                                   maxlength="180"
                                   style="width: 100%; border: none; padding: 2px; font-size: 11px;">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Leyenda --}}
    <div style="margin-top: 20px; display: flex; gap: 20px; font-size: 14px;">
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="background: #4CAF50; color: white; padding: 4px 8px; border-radius: 3px;">✓</span>
            <span>Presente</span>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="background: #f44336; color: white; padding: 4px 8px; border-radius: 3px;">✗</span>
            <span>Ausente</span>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="background: #ccc; color: #666; padding: 4px 8px; border-radius: 3px;">&nbsp;</span>
            <span>Sin marcar</span>
        </div>
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="background: #ff9800; color: white; padding: 4px 8px; border-radius: 3px;">{{ $diaActual }}</span>
            <span>Día actual</span>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
$(document).ready(function() {
    // Filtros
    $('#aplicar-filtros').click(function() {
        const año = $('#año-select').val();
        const mes = $('#mes-select').val();
        const periodo = $('#periodo-select').val();
        
        const url = "{{ route('asistencia.index') }}?" + 
                   "año=" + año + "&mes=" + mes + "&periodo=" + periodo;
        
        window.location.href = url;
    });

    // Asistencia - Ciclar entre estados (null -> presente -> ausente -> null)
    $('.asistencia-btn').click(function() {
        const btn = $(this);
        const asistenciaId = btn.data('asistencia-id');
        const campo = btn.data('campo');
        let valorActual = btn.data('valor');
        
        // Ciclar valores: null -> 1 (presente) -> 0 (ausente) -> null
        let nuevoValor;
        if (valorActual === null || valorActual === undefined || valorActual === '') {
            nuevoValor = 1; // Presente
        } else if (valorActual === 1) {
            nuevoValor = 0; // Ausente
        } else {
            nuevoValor = null; // Sin marcar
        }
        
        // Enviar petición AJAX
        $.ajax({
            url: "{{ route('asistencia.update') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                asistencia_id: asistenciaId,
                campo: campo,
                valor: nuevoValor
            },
            success: function(response) {
                if (response.success) {
                    // Actualizar la interfaz
                    btn.data('valor', nuevoValor);
                    const cell = btn.parent();
                    
                    // Limpiar estilos anteriores
                    cell.removeClass().addClass('asistencia-cell');
                    
                    if (nuevoValor === 1) {
                        cell.css({'background': '#4CAF50', 'color': 'white'});
                        btn.text('✓');
                    } else if (nuevoValor === 0) {
                        cell.css({'background': '#f44336', 'color': 'white'});
                        btn.text('✗');
                    } else {
                        cell.css({'background': '', 'color': ''});
                        btn.text('');
                    }
                }
            },
            error: function(xhr) {
                alert('Error al actualizar la asistencia: ' + (xhr.responseJSON?.message || 'Error desconocido'));
            }
        });
    });

    // Campos financieros - Actualizar al perder el foco
    $('.financial-input').on('blur', function() {
        const input = $(this);
        const asistenciaId = input.data('asistencia-id');
        const campo = input.data('campo');
        const valor = input.val();
        
        $.ajax({
            url: "{{ route('asistencia.update') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                asistencia_id: asistenciaId,
                campo: campo,
                valor: valor
            },
            success: function(response) {
                if (response.success) {
                    // Efecto visual de confirmación
                    input.css('background-color', '#d4edda').delay(500).queue(function() {
                        $(this).css('background-color', '').dequeue();
                    });
                }
            },
            error: function(xhr) {
                alert('Error al actualizar: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                // Revertir el valor si hay error
                input.focus();
            }
        });
    });

    // Auto-guardar cada 30 segundos para campos modificados
    setInterval(function() {
        $('.financial-input').each(function() {
            if ($(this).is(':focus')) {
                $(this).blur(); // Forzar guardar si el usuario está escribiendo
            }
        });
    }, 30000);
});
</script>