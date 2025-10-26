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
                            $campo = "dia_{$dia}";
                            $valor = $asistencia ? $asistencia->$campo : null;
                            $estado_info = \App\Models\Asistencia::ESTADOS[$valor] ?? \App\Models\Asistencia::ESTADOS[''];
                        @endphp
                        <td class="casilla-asistencia" 
                            style="padding: 4px; border: 1px solid #ddd; text-align: center; background-color: #f8f9fa; cursor: pointer; min-width: 35px;"
                            data-empleado-id="{{ $empleado->IdEmpleados }}"
                            data-dia="{{ $dia }}"
                            data-valor="{{ $valor }}"
                            title="Click para cambiar estado - Día {{ $dia }}">
                            {{-- El contenido se actualizará por JavaScript --}}
                        </td>
                    @endforeach
                    
                    {{-- Totales --}}
                    <td style="padding: 8px; border: 1px solid #ddd; font-size: 10px;">
                        @if($totales)
                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                @foreach($totales as $estado => $total)
                                    @if($total > 0)
                                        @php $info = \App\Models\Asistencia::ESTADOS[$estado] ?? ['icono' => $estado, 'color' => '#ccc']; @endphp
                                        <span style="background: {{ $info['color'] }}; color: white; padding: 1px 4px; border-radius: 2px; font-size: 9px;">
                                            {{ $info['icono'] }}: {{ $total }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span style="color: #999;">Sin datos</span>
                        @endif
                    </td>
                    
                    {{-- Campos financieros --}}
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="{{ $asistencia->id ?? '' }}"
                               data-campo="bono"
                               value="{{ $asistencia->bono ?? '' }}" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="{{ $asistencia->id ?? '' }}"
                               data-campo="prestamo"
                               value="{{ $asistencia->prestamo ?? '' }}" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="{{ $asistencia->id ?? '' }}"
                               data-campo="fonacot"
                               value="{{ $asistencia->fonacot ?? '' }}" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <input type="number" 
                               class="financiero-input" 
                               data-asistencia-id="{{ $asistencia->id ?? '' }}"
                               data-campo="finiquito"
                               value="{{ $asistencia->finiquito ?? '' }}" 
                               style="width: 70px; padding: 2px; border: 1px solid #ccc; border-radius: 3px;"
                               step="0.01"
                               placeholder="0.00">
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <textarea class="financiero-input" 
                                  data-asistencia-id="{{ $asistencia->id ?? '' }}"
                                  data-campo="observaciones"
                                  style="width: 180px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; resize: vertical; min-height: 30px;"
                                  placeholder="Observaciones...">{{ $asistencia->observaciones ?? '' }}</textarea>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($diasDelPeriodo) + 8 }}" style="padding: 20px; text-align: center; color: #666;">
                        No se encontraron empleados{{ $estado !== 'todos' ? ' para el estado: ' . $estado : '' }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>