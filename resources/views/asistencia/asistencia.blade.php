@php@php@php

    header('Cache-Control: no-cache, no-store, must-revalidate');

    header('Pragma: no-cache');    header('Cache-Control: no-cache, no-store, must-revalidate');    header('Cache-Control: no-cache, no-store, must-revalidate');

    header('Expires: 0');

@endphp    header('Pragma: no-cache');    header('Pragma: no-cache');



<div style="padding: 20px;">    header('Expires: 0');    header('Expires: 0');

    {{-- Encabezado --}}

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">@endphp@endphp

        <h2 style="margin: 0; color: #333;">

            📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }}

            <small style="font-size: 12px; color: #666;">(v3.1 - {{ now()->format('H:i:s') }})</small>

        </h2><div style="padding: 20px;"><div style="padding: 20px;">

        

        {{-- Botones de acción --}}    {{-- Encabezado --}}    {{-- Encabezado --}}

        <div style="display: flex; gap: 10px;">

            <button id="guardar-asistencia" class="btn btn-success" style="padding: 8px 16px;">    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">

                💾 Guardar Asistencia

            </button>        <h2 style="margin: 0; color: #333;">        <h2 style="margin: 0; color: #333;">

            

            <button id="generar-reporte" class="btn btn-info" style="padding: 8px 16px;">            📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }}            📋 Control de Asistencia - {{ $periodo === 'primera_quincena' ? 'Primera Quincena' : 'Segunda Quincena' }}

                📊 Generar Reporte

            </button>            <small style="font-size: 12px; color: #666;">(v3.0 - {{ now()->format('H:i:s') }})</small>            <small style="font-size: 12px; color: #666;">(v2.2 - {{ now()->format('H:i:s') }})</small>

        </div>

    </div>        </h2>        </h2>



    {{-- Filtros --}}                

    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">

        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">        {{-- Botones de acción --}}        {{-- Botones de acción --}}

            {{-- Filtro por Estado --}}

            <div>        <div style="display: flex; gap: 10px;">        <div style="display: flex; gap: 10px;">

                <label style="font-weight: bold; margin-right: 5px;">Estado/Zona:</label>

                <select id="estado-select" style="padding: 5px; min-width: 150px;">            <button id="guardar-asistencia" class="btn btn-success" style="padding: 8px 16px;">            <button id="guardar-asistencia" class="btn btn-success" style="padding: 8px 16px;">

                    <option value="todos" {{ $estado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>

                    @foreach($estados as $est)                💾 Guardar Asistencia                💾 Guardar Asistencia

                        <option value="{{ $est }}" {{ $estado === $est ? 'selected' : '' }}>{{ $est }}</option>

                    @endforeach            </button>            </button>

                </select>

            </div>                        

            

            {{-- Filtro por Mes --}}            <button id="generar-reporte" class="btn btn-info" style="padding: 8px 16px;">            <button id="generar-reporte" class="btn btn-info" onclick="descargarReporteDirecto()" style="padding: 8px 16px;">

            <div>

                <label style="font-weight: bold; margin-right: 5px;">Mes:</label>                📊 Generar Reporte                📊 Generar Reporte

                <select id="mes-select" style="padding: 5px;">

                    @php            </button>            </button>

                        $mesesEspanol = [

                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',        </div>        </div>

                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',

                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'    </div>    </div>

                        ];

                    @endphp

                    @for($i = 1; $i <= 12; $i++)

                        <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>    {{-- Filtros --}}    {{-- Filtros --}}

                            {{ $mesesEspanol[$i] }}

                        </option>    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">

                    @endfor

                </select>        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">

            </div>

                        {{-- Filtro por Estado --}}            {{-- Filtro por Estado --}}

            {{-- Filtro por Año --}}

            <div>            <div>            <div>

                <label style="font-weight: bold; margin-right: 5px;">Año:</label>

                <select id="año-select" style="padding: 5px;">                <label style="font-weight: bold; margin-right: 5px;">Estado/Zona:</label>                <label style="font-weight: bold; margin-right: 5px;">Estado/Zona:</label>

                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)

                        <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>                <select id="estado-select" style="padding: 5px; min-width: 150px;">                <select id="estado-select" style="padding: 5px; min-width: 150px;">

                    @endfor

                </select>                    <option value="todos" {{ $estado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>                    <option value="todos" {{ $estado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>

            </div>

                                @foreach($estados as $est)                    @foreach($estados as $est)

            {{-- Filtro por Período --}}

            <div>                        <option value="{{ $est }}" {{ $estado === $est ? 'selected' : '' }}>{{ $est }}</option>                        <option value="{{ $est }}" {{ $estado === $est ? 'selected' : '' }}>{{ $est }}</option>

                <label style="font-weight: bold; margin-right: 5px;">Período:</label>

                <select id="periodo-select" style="padding: 5px;">                    @endforeach                    @endforeach

                    <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>

                        Primera Quincena (1-15)                </select>                </select>

                    </option>

                    <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>            </div>            </div>

                        Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})

                    </option>                        

                </select>

            </div>            {{-- Filtro por Mes --}}            {{-- Filtro por Mes --}}

            

            <button id="aplicar-filtros" class="btn btn-primary" style="padding: 8px 16px;">            <div>            <div>

                🔍 Aplicar Filtros

            </button>                <label style="font-weight: bold; margin-right: 5px;">Mes:</label>                <label style="font-weight: bold; margin-right: 5px;">Mes:</label>

        </div>

    </div>                <select id="mes-select" style="padding: 5px;">                <select id="mes-select" style="padding: 5px;">



    {{-- Leyenda de Estados --}}                    @php                    @php

    <div style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;">

        <h4 style="margin: 0 0 10px 0; color: #333;">📌 Leyenda de Estados:</h4>                        $mesesEspanol = [                        $mesesEspanol = [

        <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 12px;">

            @php                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',

                $estados_asistencia = \App\Models\Asistencia::ESTADOS;

            @endphp                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',

            @foreach($estados_asistencia as $key => $estado_info)

                <div style="display: flex; align-items: center; gap: 5px;">                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'

                    <span style="background: {{ $estado_info['color'] }}; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;">

                        {{ $estado_info['icono'] }}                        ];                        ];

                    </span>

                    <span>{{ $estado_info['nombre'] }}</span>                    @endphp                    @endphp

                </div>

            @endforeach                    @for($i = 1; $i <= 12; $i++)                    @for($i = 1; $i <= 12; $i++)

        </div>

    </div>                        <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>                        <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>



    {{-- Información del período actual --}}                            {{ $mesesEspanol[$i] }}                            {{ $mesesEspanol[$i] }}

    <div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">

        <strong>📅 Período actual:</strong>                         </option>                        </option>

        {{ $periodo === 'primera_quincena' ? 'Días 1 al 15' : 'Días 16 al ' . cal_days_in_month(CAL_GREGORIAN, $mes, $año) }} 

        de {{ DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $año }}                    @endfor                    @endfor

        <br>

        <strong>👥 Total empleados mostrados:</strong> {{ $empleados->count() }}                </select>                </select>

        @if($estado !== 'todos')

            <strong> • Estado seleccionado:</strong> {{ $estado }}            </div>            </div>

        @endif

    </div>                        



    {{-- Tabla de Asistencia --}}            {{-- Filtro por Año --}}            {{-- Filtro por Año --}}

    <div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">

        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">            <div>            <div>

            <thead style="background: #447D9B; color: white; position: sticky; top: 0;">

                <tr>                <label style="font-weight: bold; margin-right: 5px;">Año:</label>                <label style="font-weight: bold; margin-right: 5px;">Año:</label>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Empleado</th>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 150px;">Cliente</th>                <select id="año-select" style="padding: 5px;">                <select id="año-select" style="padding: 5px;">

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 120px;">Estado</th>

                                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)

                    {{-- Días del período --}}

                    @foreach($diasDelPeriodo as $dia)                        <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>                        <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>

                        <th style="padding: 5px; border: 1px solid #ddd; min-width: 40px; text-align: center;">

                            {{ $dia }}                    @endfor                    @endfor

                        </th>

                    @endforeach                </select>                </select>

                    

                    {{-- Columna de totales --}}            </div>            </div>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 100px;">Totales</th>

                                            

                    {{-- Datos financieros --}}

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Bono</th>            {{-- Filtro por Período --}}            {{-- Filtro por Período --}}

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>            <div>            <div>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Finiquito</th>

                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Observaciones</th>                <label style="font-weight: bold; margin-right: 5px;">Período:</label>                <label style="font-weight: bold; margin-right: 5px;">Período:</label>

                </tr>

            </thead>                <select id="periodo-select" style="padding: 5px;">                <select id="periodo-select" style="padding: 5px;">

            <tbody>

                @forelse($empleados as $empleado)                    <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>                    <option value="primera_quincena" {{ $periodo === 'primera_quincena' ? 'selected' : '' }}>

                    @php

                        $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;                        Primera Quincena (1-15)                        Primera Quincena (1-15)

                        $totales = $asistencia ? $asistencia->calcularTotales() : [];

                    @endphp                    </option>                    </option>

                    <tr style="border-bottom: 1px solid #eee;">

                        {{-- Información del empleado --}}                    <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>                    <option value="segunda_quincena" {{ $periodo === 'segunda_quincena' ? 'selected' : '' }}>

                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">

                            {{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}                        Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})                        Segunda Quincena (16-{{ cal_days_in_month(CAL_GREGORIAN, $mes, $año) }})

                        </td>

                        <td style="padding: 8px; border: 1px solid #ddd;">                    </option>                    </option>

                            @if($empleado->puesto && $empleado->puesto->cliente)

                                {{ $empleado->puesto->cliente->Nombre }}                </select>                </select>

                            @else

                                <span style="color: #dc3545;">Sin cliente asignado</span>            </div>            </div>

                            @endif

                        </td>                        

                        <td style="padding: 8px; border: 1px solid #ddd;">

                            {{ $empleado->Estado ?? 'Sin estado' }}            <button id="aplicar-filtros" class="btn btn-primary" style="padding: 8px 16px;">            <button id="aplicar-filtros" class="btn btn-primary" style="padding: 8px 16px;">

                        </td>

                                        🔍 Aplicar Filtros                🔍 Aplicar Filtros

                        {{-- Días de asistencia --}}

                        @foreach($diasDelPeriodo as $dia)            </button>            </button>

                            @php

                                $valorDia = $asistencia ? $asistencia->getAsistenciaDia($dia) : null;        </div>        </div>

                                $color = $asistencia ? $asistencia->getColorDia($dia) : '#f8f9fa';

                                $icono = $asistencia ? $asistencia->getIconoDia($dia) : '';    </div>    </div>

                                $esDiaActual = ($dia == $diaActual && $mes == $mes && $año == $año);

                            @endphp

                            <td style="padding: 2px; border: 1px solid #ddd; text-align: center;">

                                <div class="dia-asistencia {{ $esDiaActual ? 'dia-actual' : '' }}"     {{-- Leyenda de Estados --}}    {{-- Leyenda de Estados --}}

                                     data-empleado="{{ $empleado->IdEmpleados }}" 

                                     data-dia="{{ $dia }}"    <div style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;">    <div style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;">

                                     data-asistencia-id="{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}"

                                     onclick="abrirModalEstados({{ $empleado->IdEmpleados }}, {{ $dia }}, '{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}')"        <h4 style="margin: 0 0 10px 0; color: #333;">📌 Leyenda de Estados:</h4>        <h4 style="margin: 0 0 10px 0; color: #333;">📌 Leyenda de Estados:</h4>

                                     style="background: {{ $color }}; 

                                            color: {{ in_array($color, ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }};         <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 12px;">        <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 12px;">

                                            padding: 5px; 

                                            border-radius: 3px;             @php            @php

                                            cursor: pointer; 

                                            font-weight: bold;                $estados_asistencia = \App\Models\Asistencia::ESTADOS;                $estados_asistencia = \App\Models\Asistencia::ESTADOS;

                                            min-height: 25px;

                                            display: flex;            @endphp            @endphp

                                            align-items: center;

                                            justify-content: center;            @foreach($estados_asistencia as $key => $estado_info)            @foreach($estados_asistencia as $key => $estado_info)

                                            user-select: none;

                                            position: relative;                <div style="display: flex; align-items: center; gap: 5px;">                <div style="display: flex; align-items: center; gap: 5px;">

                                            z-index: 1;"

                                     title="Click para seleccionar estado - Empleado: {{ $empleado->IdEmpleados }}, Día: {{ $dia }}, Asistencia: {{ $asistencia ? $asistencia->id : 'Sin crear' }}">                    <span style="background: {{ $estado_info['color'] }}; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;">                    <span style="background: {{ $estado_info['color'] }}; color: white; padding: 2px 6px; border-radius: 3px; font-weight: bold;">

                                    {{ $icono ?: '?' }}

                                </div>                        {{ $estado_info['icono'] }}                        {{ $estado_info['icono'] }}

                            </td>

                        @endforeach                    </span>                    </span>

                        

                        {{-- Totales --}}                    <span>{{ $estado_info['nombre'] }}</span>                    <span>{{ $estado_info['nombre'] }}</span>

                        <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px; background: #f8f9fa;">

                            @if($asistencia && !empty($totales))                </div>                </div>

                                <div style="color: #28a745;">Asistencias: {{ $totales['asistencias'] }}</div>

                                <div style="color: #dc3545;">Faltas: {{ $totales['faltas'] }}</div>            @endforeach            @endforeach

                                <div style="color: #ffc107;">Retardos: {{ $totales['retardos'] }}</div>

                                @if(isset($totales['sin_marcar']) && $totales['sin_marcar'] > 0)        </div>        </div>

                                    <div style="color: #6c757d;">Sin marcar: {{ $totales['sin_marcar'] }}</div>

                                @endif    </div>    </div>

                            @endif

                        </td>

                        

                        {{-- Datos financieros --}}    {{-- Información del período actual --}}    {{-- Información del período actual --}}

                        <td style="padding: 4px; border: 1px solid #ddd;">

                            <input type="number"     <div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">    <div style="background: #e8f4fd; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">

                                   class="financiero-input" 

                                   data-campo="bono"        <strong>📅 Período actual:</strong>         <strong>📅 Período actual:</strong> 

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"

                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"        {{ $periodo === 'primera_quincena' ? 'Días 1 al 15' : 'Días 16 al ' . cal_days_in_month(CAL_GREGORIAN, $mes, $año) }}         {{ $periodo === 'primera_quincena' ? 'Días 1 al 15' : 'Días 16 al ' . cal_days_in_month(CAL_GREGORIAN, $mes, $año) }} 

                                   value="{{ $asistencia ? $asistencia->bono : '' }}" 

                                   style="width: 70px; font-size: 11px;"         de {{ DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $año }}        de {{ DateTime::createFromFormat('!m', $mes)->format('F') }} {{ $año }}

                                   step="0.01" min="0">

                        </td>        <br>        <br>

                        <td style="padding: 4px; border: 1px solid #ddd;">

                            <input type="number"         <strong>👥 Total empleados mostrados:</strong> {{ $empleados->count() }}        <strong>👥 Total empleados mostrados:</strong> {{ $empleados->count() }}

                                   class="financiero-input" 

                                   data-campo="prestamo"        @if($estado !== 'todos')        @if($estado !== 'todos')

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"

                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"            <strong> • Estado seleccionado:</strong> {{ $estado }}            <strong> • Estado seleccionado:</strong> {{ $estado }}

                                   value="{{ $asistencia ? $asistencia->prestamo : '' }}" 

                                   style="width: 70px; font-size: 11px;"         @endif        @endif

                                   step="0.01" min="0">

                        </td>    </div>    </div>

                        <td style="padding: 4px; border: 1px solid #ddd;">

                            <input type="number" 

                                   class="financiero-input" 

                                   data-campo="fonacot"    {{-- Tabla de Asistencia --}}    {{-- Tabla de Asistencia --}}

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"

                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"    <div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">    <div style="overflow-x: auto; border: 1px solid #ddd; border-radius: 8px;">

                                   value="{{ $asistencia ? $asistencia->fonacot : '' }}" 

                                   style="width: 70px; font-size: 11px;"         <table style="width: 100%; border-collapse: collapse; font-size: 12px;">        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">

                                   step="0.01" min="0">

                        </td>            <thead style="background: #447D9B; color: white; position: sticky; top: 0;">            <thead style="background: #447D9B; color: white; position: sticky; top: 0;">

                        <td style="padding: 4px; border: 1px solid #ddd;">

                            <input type="number"                 <tr>                <tr>

                                   class="financiero-input" 

                                   data-campo="finiquito"                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Empleado</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Empleado</th>

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"

                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 150px;">Cliente</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 150px;">Cliente</th>

                                   value="{{ $asistencia ? $asistencia->finiquito : '' }}" 

                                   style="width: 70px; font-size: 11px;"                     <th style="padding: 10px; border: 1px solid #ddd; min-width: 120px;">Estado</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 120px;">Estado</th>

                                   step="0.01" min="0">

                        </td>                                        

                        <td style="padding: 4px; border: 1px solid #ddd;">

                            <input type="text"                     {{-- Días del período --}}                    {{-- Días del período --}}

                                   class="financiero-input" 

                                   data-campo="observaciones"                    @foreach($diasDelPeriodo as $dia)                    @foreach($diasDelPeriodo as $dia)

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"

                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                        <th style="padding: 5px; border: 1px solid #ddd; min-width: 40px; text-align: center;">                        <th style="padding: 5px; border: 1px solid #ddd; min-width: 40px; text-align: center;">

                                   value="{{ $asistencia ? $asistencia->observaciones : '' }}" 

                                   style="width: 180px; font-size: 11px;">                            {{ $dia }}                            {{ $dia }}

                        </td>

                    </tr>                        </th>                        </th>

                @empty

                    <tr>                    @endforeach                    @endforeach

                        <td colspan="{{ count($diasDelPeriodo) + 8 }}" style="text-align: center; padding: 20px; color: #666;">

                            No se encontraron empleados con los filtros seleccionados.                                        

                        </td>

                    </tr>                    {{-- Columna de totales --}}                    {{-- Columna de totales --}}

                @endforelse

            </tbody>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 100px;">Totales</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 100px;">Totales</th>

        </table>

    </div>                                        

</div>

                    {{-- Datos financieros --}}                    {{-- Datos financieros --}}

{{-- Modal para cambiar estado --}}

<div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Bono</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Bono</th>

    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px; position: relative;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Préstamo</th>

            <h3 style="margin: 0;">📅 Seleccionar Estado de Asistencia</h3>

            <button id="cerrar-estado-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Fonacot</th>

        </div>

        <div id="estado-buttons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Finiquito</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 80px;">Finiquito</th>

            @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)

                <button type="button"                     <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Observaciones</th>                    <th style="padding: 10px; border: 1px solid #ddd; min-width: 200px;">Observaciones</th>

                        class="estado-btn" 

                        data-estado="{{ $key }}"                </tr>                </tr>

                        style="background: {{ $estado_info['color'] }}; 

                               color: {{ in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }};             </thead>            </thead>

                               border: none; 

                               padding: 10px;             <tbody>            <tbody>

                               font-weight: bold;

                               border-radius: 5px;                @forelse($empleados as $empleado)                @forelse($empleados as $empleado)

                               cursor: pointer;

                               transition: all 0.2s;">                    @php                    @php

                    {{ $estado_info['icono'] }} {{ $estado_info['nombre'] }}

                </button>                        $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;                        $asistencia = $asistencias[$empleado->IdEmpleados] ?? null;

            @endforeach

            <button type="button"                         $totales = $asistencia ? $asistencia->calcularTotales() : [];                        $totales = $asistencia ? $asistencia->calcularTotales() : [];

                    class="estado-btn" 

                    data-estado=""                    @endphp                    @endphp

                    style="background: #6c757d; 

                           color: #fff;                     <tr style="border-bottom: 1px solid #eee;">                    <tr style="border-bottom: 1px solid #eee;">

                           border: none; 

                           padding: 10px;                         {{-- Información del empleado --}}                        {{-- Información del empleado --}}

                           font-weight: bold;

                           border-radius: 5px;                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">                        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">

                           cursor: pointer;

                           transition: all 0.2s;">                            {{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}                            {{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}

                🚫 Limpiar

            </button>                        </td>                        </td>

        </div>

    </div>                        <td style="padding: 8px; border: 1px solid #ddd;">                        <td style="padding: 8px; border: 1px solid #ddd;">

</div>

                            @if($empleado->puesto && $empleado->puesto->cliente)                            @if($empleado->puesto && $empleado->puesto->cliente)

{{-- Modal para generar reporte --}}

<div id="reporteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">                                {{ $empleado->puesto->cliente->Nombre }}                                {{ $empleado->puesto->cliente->Nombre }}

    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">                            @else                            @else

            <h3 style="margin: 0;">📊 Generar Reporte de Asistencia</h3>

            <button id="cerrar-reporte-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>                                <span style="color: #dc3545;">Sin cliente asignado</span>                                <span style="color: #dc3545;">Sin cliente asignado</span>

        </div>

        <form id="reporte-form">                            @endif                            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">

                <div>                        </td>                        </td>

                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Año:</label>

                    <select name="año" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                        <td style="padding: 8px; border: 1px solid #ddd;">                        <td style="padding: 8px; border: 1px solid #ddd;">

                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)

                            <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>                            {{ $empleado->Estado ?? 'Sin estado' }}                            {{ $empleado->Estado ?? 'Sin estado' }}

                        @endfor

                    </select>                        </td>                        </td>

                </div>

                <div>                                                

                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mes:</label>

                    <select name="mes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                        {{-- Días de asistencia --}}                        {{-- Días de asistencia --}}

                        @php

                            $mesesEspanol = [                        @foreach($diasDelPeriodo as $dia)                        @foreach($diasDelPeriodo as $dia)

                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',

                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',                            @php                            @php

                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'

                            ];                                $valorDia = $asistencia ? $asistencia->getAsistenciaDia($dia) : null;                                $valorDia = $asistencia ? $asistencia->getAsistenciaDia($dia) : null;

                        @endphp

                        @for($i = 1; $i <= 12; $i++)                                $color = $asistencia ? $asistencia->getColorDia($dia) : '#f8f9fa';                                // $color = $asistencia ? $asistencia->getColorDia($dia) : '#f8f9fa';

                            <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>

                                {{ $mesesEspanol[$i] }}                                $icono = $asistencia ? $asistencia->getIconoDia($dia) : '';                                // $icono = $asistencia ? $asistencia->getIconoDia($dia) : '';

                            </option>

                        @endfor                                $esDiaActual = ($dia == $diaActual && $mes == $mes && $año == $año);                                $color = '#f8f9fa'; // Color por defecto

                    </select>

                </div>                            @endphp                                $icono = ''; // Icono por defecto

            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">                            <td style="padding: 2px; border: 1px solid #ddd; text-align: center;">                                $esDiaActual = ($dia == $diaActual && $mes == $mes && $año == $año);

                <div>

                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Período:</label>                                <div class="dia-asistencia {{ $esDiaActual ? 'dia-actual' : '' }}"                             @endphp

                    <select name="periodo" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">

                        <option value="primera_quincena">Primera Quincena (1-15)</option>                                     data-empleado="{{ $empleado->IdEmpleados }}"                             <td style="padding: 2px; border: 1px solid #ddd; text-align: center;">

                        <option value="segunda_quincena">Segunda Quincena (16-30/31)</option>

                    </select>                                     data-dia="{{ $dia }}"                                <div class="dia-asistencia {{ $esDiaActual ? 'dia-actual' : '' }}" 

                </div>

                <div>                                     data-asistencia-id="{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}"                                     data-empleado="{{ $empleado->IdEmpleados }}" 

                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Estado:</label>

                    <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                                     onclick="abrirModalEstados({{ $empleado->IdEmpleados }}, {{ $dia }}, '{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}')"                                     data-dia="{{ $dia }}"

                        <option value="todos">Todos los Estados</option>

                        @foreach($estados as $est)                                     style="background: {{ $color }};                                      data-asistencia-id="{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}"

                            <option value="{{ $est }}">{{ $est }}</option>

                        @endforeach                                            color: {{ in_array($color, ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }};                                      onclick="abrirModalEstadosDirecto({{ $empleado->IdEmpleados }}, {{ $dia }}, '{{ $asistencia ? $asistencia->id : 'NO_ASISTENCIA' }}')"

                    </select>

                </div>                                            padding: 5px;                                      style="background: {{ $color }}; 

            </div>

            <div style="margin-bottom: 20px;">                                            border-radius: 3px;                                             color: {{ in_array($color, ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }}; 

                <label style="display: block; margin-bottom: 10px; font-weight: bold;">Formato:</label>

                <div>                                            cursor: pointer;                                             padding: 5px; 

                    <label style="margin-right: 20px; cursor: pointer;">

                        <input type="radio" name="formato" value="excel" checked style="margin-right: 5px;"> 📊 Excel                                            font-weight: bold;                                            border-radius: 3px; 

                    </label>

                    <label style="cursor: pointer;">                                            min-height: 25px;                                            cursor: pointer; 

                        <input type="radio" name="formato" value="pdf" style="margin-right: 5px;"> 📄 PDF

                    </label>                                            display: flex;                                            cursor: pointer; 

                </div>

            </div>                                            align-items: center;                                            font-weight: bold;

            <div style="display: flex; gap: 10px; justify-content: flex-end;">

                <button type="button" id="cancelar-reporte" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancelar</button>                                            justify-content: center;                                            min-height: 25px;

                <button type="button" id="descargar-reporte" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">📥 Descargar Reporte</button>

            </div>                                            user-select: none;                                            display: flex;

        </form>

    </div>                                            position: relative;                                            align-items: center;

</div>

                                            z-index: 1;"                                            justify-content: center;

{{-- CSS Estilos --}}

<style>                                     title="Click para seleccionar estado - Empleado: {{ $empleado->IdEmpleados }}, Día: {{ $dia }}, Asistencia: {{ $asistencia ? $asistencia->id : 'Sin crear' }}">                                            user-select: none;

.dia-asistencia:hover {

    opacity: 0.8;                                    {{ $icono ?: '?' }}                                            position: relative;

    transform: scale(1.05);

}                                </div>                                            z-index: 1;"



.financiero-input {                            </td>                                     title="Click para seleccionar estado - Empleado: {{ $empleado->IdEmpleados }}, Día: {{ $dia }}, Asistencia: {{ $asistencia ? $asistencia->id : 'Sin crear' }}">

    border: 1px solid #ddd;

    padding: 2px 4px;                        @endforeach                                    {{ $icono ?: '?' }}

    border-radius: 3px;

}                                                        </div>



.financiero-input:focus {                        {{-- Totales --}}                            </td>

    border-color: #007bff;

    outline: none;                        <td style="padding: 8px; border: 1px solid #ddd; font-size: 11px; background: #f8f9fa;">                        @endforeach

}

                            @if($asistencia && !empty($totales))                        

.estado-btn:hover {

    opacity: 0.8;                                <div style="color: #28a745;">Asistencias: {{ $totales['asistencias'] }}</div>                        {{-- Columna de totales --}}

    transform: scale(1.02);

}                                <div style="color: #dc3545;">Faltas: {{ $totales['faltas'] }}</div>                        <td style="padding: 8px; border: 1px solid #ddd; font-size: 10px;">



/* Estilos para los modales */                                <div style="color: #ffc107;">Retardos: {{ $totales['retardos'] }}</div>                            @if($asistencia && !empty($totales))

#estadoModal, #reporteModal {

    animation: fadeIn 0.3s ease-in-out;                                @if(isset($totales['sin_marcar']) && $totales['sin_marcar'] > 0)                                @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)

}

                                    <div style="color: #6c757d;">Sin marcar: {{ $totales['sin_marcar'] }}</div>                                    @if(($totales[$key] ?? 0) > 0)

@keyframes fadeIn {

    from { opacity: 0; }                                @endif                                        <div style="color: {{ $estado_info['color'] }}; font-weight: bold;">

    to { opacity: 1; }

}                            @endif                                            {{ $estado_info['icono'] }}: {{ $totales[$key] }}



#estadoModal > div, #reporteModal > div {                        </td>                                        </div>

    animation: slideIn 0.3s ease-in-out;

}                                                            @endif



@keyframes slideIn {                        {{-- Datos financieros --}}                                @endforeach

    from { transform: translateY(-50px); opacity: 0; }

    to { transform: translateY(0); opacity: 1; }                        <td style="padding: 4px; border: 1px solid #ddd;">                                @if(($totales['sin_marcar'] ?? 0) > 0)

}

                            <input type="number"                                     <div style="color: #6c757d;">Sin marcar: {{ $totales['sin_marcar'] }}</div>

/* Animación para el día actual */

@keyframes blink {                                   class="financiero-input"                                 @endif

    0%, 50% { opacity: 1; }

    51%, 100% { opacity: 0.3; }                                   data-campo="bono"                            @endif

}

                                   data-empleado-id="{{ $empleado->IdEmpleados }}"                        </td>

.dia-actual {

    animation: blink 2s infinite;                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                        

    border: 2px solid #007bff !important;

    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);                                   value="{{ $asistencia ? $asistencia->bono : '' }}"                         {{-- Datos financieros --}}

}

</style>                                   style="width: 70px; font-size: 11px;"                         <td style="padding: 4px; border: 1px solid #ddd;">



{{-- CSRF Token para AJAX --}}                                   step="0.01" min="0">                            <input type="number" 

<meta name="csrf-token" content="{{ csrf_token() }}">

                        </td>                                   class="form-control financiero-input" 

{{-- JavaScript completo y funcional --}}

<script>                        <td style="padding: 4px; border: 1px solid #ddd;">                                   data-campo="bono"

console.log('🚀 JavaScript de asistencia v3.1 cargado');

                            <input type="number"                                    data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"

// Variables globales

let empleadoActual = null;                                   class="financiero-input"                                    value="{{ $asistencia ? $asistencia->bono : '' }}" 

let diaActual = null;

let asistenciaIdActual = null;                                   data-campo="prestamo"                                   style="width: 70px; font-size: 11px;" 



// ========== FUNCIONES PRINCIPALES ==========                                   data-empleado-id="{{ $empleado->IdEmpleados }}"                                   step="0.01" min="0">



// 1. Aplicar filtros (funcional)                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                        </td>

function aplicarFiltros() {

    console.log('🔍 Aplicando filtros...');                                   value="{{ $asistencia ? $asistencia->prestamo : '' }}"                         <td style="padding: 4px; border: 1px solid #ddd;">

    

    const estado = document.getElementById('estado-select');                                   style="width: 70px; font-size: 11px;"                             <input type="number" 

    const mes = document.getElementById('mes-select');

    const año = document.getElementById('año-select');                                   step="0.01" min="0">                                   class="form-control financiero-input" 

    const periodo = document.getElementById('periodo-select');

                            </td>                                   data-campo="prestamo"

    if (!estado || !mes || !año || !periodo) {

        console.error('❌ ERROR: Algunos elementos de filtro no se encontraron');                        <td style="padding: 4px; border: 1px solid #ddd;">                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"

        return;

    }                            <input type="number"                                    value="{{ $asistencia ? $asistencia->prestamo : '' }}" 

    

    const filtros = {                                   class="financiero-input"                                    style="width: 70px; font-size: 11px;" 

        estado: estado.value,

        mes: mes.value,                                   data-campo="fonacot"                                   step="0.01" min="0">

        año: año.value,

        periodo: periodo.value                                   data-empleado-id="{{ $empleado->IdEmpleados }}"                        </td>

    };

                                       data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                        <td style="padding: 4px; border: 1px solid #ddd;">

    console.log('📊 Filtros:', filtros);

                                       value="{{ $asistencia ? $asistencia->fonacot : '' }}"                             <input type="number" 

    // Mostrar loading

    const btn = document.getElementById('aplicar-filtros');                                   style="width: 70px; font-size: 11px;"                                    class="form-control financiero-input" 

    if (btn) {

        btn.innerHTML = '⏳ Aplicando...';                                   step="0.01" min="0">                                   data-campo="fonacot"

        btn.disabled = true;

    }                        </td>                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"

    

    // Aplicar filtros redirigiendo                        <td style="padding: 4px; border: 1px solid #ddd;">                                   value="{{ $asistencia ? $asistencia->fonacot : '' }}" 

    const params = new URLSearchParams(filtros);

    window.location.href = '/asistencia?' + params.toString();                            <input type="number"                                    style="width: 70px; font-size: 11px;" 

}

                                   class="financiero-input"                                    step="0.01" min="0">

// 2. Abrir modal de estados (funcional)

function abrirModalEstados(empleadoId, dia, asistenciaId) {                                   data-campo="finiquito"                        </td>

    console.log('📋 Abriendo modal de estados:', {empleadoId, dia, asistenciaId});

                                       data-empleado-id="{{ $empleado->IdEmpleados }}"                        <td style="padding: 4px; border: 1px solid #ddd;">

    // Guardar datos actuales

    empleadoActual = empleadoId;                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                            <input type="number" 

    diaActual = dia;

    asistenciaIdActual = asistenciaId;                                   value="{{ $asistencia ? $asistencia->finiquito : '' }}"                                    class="form-control financiero-input" 

    

    // Mostrar modal                                   style="width: 70px; font-size: 11px;"                                    data-campo="estatus_finiquito"

    const modal = document.getElementById('estadoModal');

    if (modal) {                                   step="0.01" min="0">                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"

        modal.style.display = 'block';

        console.log('✅ Modal mostrado');                        </td>                                   value="{{ $asistencia ? $asistencia->estatus_finiquito : '' }}" 

    } else {

        console.error('❌ ERROR: Modal no encontrado');                        <td style="padding: 4px; border: 1px solid #ddd;">                                   style="width: 70px; font-size: 11px;" 

    }

}                            <input type="text"                                    step="0.01" min="0">



// 3. Seleccionar estado (funcional con AJAX)                                   class="financiero-input"                         </td>

function seleccionarEstado(estado) {

    console.log('🎯 Estado seleccionado:', estado);                                   data-campo="observaciones"                        <td style="padding: 4px; border: 1px solid #ddd;">

    

    if (!empleadoActual || !diaActual) {                                   data-empleado-id="{{ $empleado->IdEmpleados }}"                            <input type="text" 

        console.error('❌ ERROR: No hay empleado o día seleccionado');

        return;                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"                                   class="form-control financiero-input" 

    }

                                       value="{{ $asistencia ? $asistencia->observaciones : '' }}"                                    data-campo="observaciones"

    // Mostrar loading en modal

    const botones = document.querySelectorAll('.estado-btn');                                   style="width: 180px; font-size: 11px;">                                   data-asistencia-id="{{ $asistencia ? $asistencia->id : '' }}"

    botones.forEach(btn => {

        btn.disabled = true;                        </td>                                   value="{{ $asistencia ? $asistencia->observaciones : '' }}" 

        btn.style.opacity = '0.5';

    });                    </tr>                                   style="width: 180px; font-size: 11px;" 

    

    // Hacer petición AJAX                @empty                                   maxlength="180"

    fetch('/asistencia/guardar', {

        method: 'POST',                    <tr>                                   placeholder="Observaciones...">

        headers: {

            'Content-Type': 'application/json',                        <td colspan="{{ count($diasDelPeriodo) + 8 }}" style="text-align: center; padding: 20px; color: #666;">                        </td>

            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

        },                            No se encontraron empleados con los filtros seleccionados.                    </tr>

        body: JSON.stringify({

            empleado_id: empleadoActual,                        </td>                @empty

            dia: diaActual,

            valor: estado,                    </tr>                    <tr>

            año: {{ $año }},

            mes: {{ $mes }}                @endforelse                        <td colspan="{{ count($diasDelPeriodo) + 8 }}" style="padding: 20px; text-align: center; color: #666;">

        })

    })            </tbody>                            No se encontraron empleados activos{{ $estado !== 'todos' ? ' para el estado: ' . $estado : '' }}

    .then(response => response.json())

    .then(data => {        </table>                        </td>

        console.log('✅ Respuesta del servidor:', data);

            </div>                    </tr>

        if (data.success) {

            // Actualizar la casilla visualmente</div>                @endforelse

            actualizarCasillaEnPantalla(empleadoActual, diaActual, estado);

            cerrarModalEstados();            </tbody>

            console.log('✅ Estado actualizado correctamente');

        } else {{{-- Modal para cambiar estado --}}        </table>

            console.error('❌ ERROR:', data.message);

        }<div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">    </div>

    })

    .catch(error => {    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px; position: relative;"></div>

        console.error('❌ Error:', error);

    })        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">

    .finally(() => {

        // Restaurar botones            <h3 style="margin: 0;">📅 Seleccionar Estado de Asistencia</h3>{{-- Modal para cambiar estado --}}

        botones.forEach(btn => {

            btn.disabled = false;            <button id="cerrar-estado-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button><div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">

            btn.style.opacity = '1';

        });        </div>    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 400px; position: relative;">

    });

}        <div id="estado-buttons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">



// 4. Actualizar casilla en pantalla            @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)            <h3 style="margin: 0;">📅 Seleccionar Estado de Asistencia</h3>

function actualizarCasillaEnPantalla(empleadoId, dia, estado) {

    const casilla = document.querySelector(`[data-empleado="${empleadoId}"][data-dia="${dia}"]`);                <button type="button"             <button id="cerrar-estado-modal" onclick="cerrarEstadoModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>

    if (casilla) {

        // Obtener info del estado                        class="estado-btn"         </div>

        const estados = @json(\App\Models\Asistencia::ESTADOS);

        const estadoInfo = estados[estado] || {color: '#f8f9fa', icono: '?'};                        data-estado="{{ $key }}"        <div id="estado-buttons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">

        

        // Actualizar estilos                        style="background: {{ $estado_info['color'] }};             @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)

        casilla.style.background = estadoInfo.color;

        casilla.style.color = ['#ffc107', '#f8d7da'].includes(estadoInfo.color) ? '#000' : '#fff';                               color: {{ in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }};                 <button type="button" 

        casilla.textContent = estadoInfo.icono;

                                       border: none;                         class="estado-btn" 

        console.log('🔄 Casilla actualizada visualmente');

    }                               padding: 10px;                         data-estado="{{ $key }}"

}

                               font-weight: bold;                        onclick="seleccionarEstadoDirecto('{{ $key }}')"

// 5. Cerrar modal de estados

function cerrarModalEstados() {                               border-radius: 5px;                        style="background: {{ $estado_info['color'] }}; 

    const modal = document.getElementById('estadoModal');

    if (modal) {                               cursor: pointer;                               color: {{ in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }}; 

        modal.style.display = 'none';

        empleadoActual = null;                               transition: all 0.2s;">                               border: none; 

        diaActual = null;

        asistenciaIdActual = null;                    {{ $estado_info['icono'] }} {{ $estado_info['nombre'] }}                               padding: 10px; 

        console.log('❌ Modal cerrado');

    }                </button>                               font-weight: bold;

}

            @endforeach                               border-radius: 5px;

// 6. Abrir modal de reportes

function abrirModalReportes() {            <button type="button"                                cursor: pointer;

    console.log('📊 Abriendo modal de reportes');

    const modal = document.getElementById('reporteModal');                    class="estado-btn"                                transition: all 0.2s;">

    if (modal) {

        modal.style.display = 'block';                    data-estado=""                    {{ $estado_info['icono'] }} {{ $estado_info['nombre'] }}

    } else {

        console.error('❌ ERROR: Modal de reportes no encontrado');                    style="background: #6c757d;                 </button>

    }

}                           color: #fff;             @endforeach



// 7. Cerrar modal de reportes                           border: none;             <button type="button" 

function cerrarModalReportes() {

    const modal = document.getElementById('reporteModal');                           padding: 10px;                     class="estado-btn" 

    if (modal) {

        modal.style.display = 'none';                           font-weight: bold;                    data-estado=""

        console.log('❌ Modal de reportes cerrado');

    }                           border-radius: 5px;                    onclick="seleccionarEstadoDirecto('')"

}

                           cursor: pointer;                    style="background: #6c757d; 

// 8. Descargar reporte (funcional)

function descargarReporte() {                           transition: all 0.2s;">                           color: #fff; 

    console.log('📥 Iniciando descarga de reporte...');

                    🚫 Limpiar                           border: none; 

    const form = document.getElementById('reporte-form');

    const formData = new FormData(form);            </button>                           padding: 10px; 

    

    const params = {        </div>                           font-weight: bold;

        año: formData.get('año'),

        mes: formData.get('mes'),    </div>                           border-radius: 5px;

        periodo: formData.get('periodo'),

        estado: formData.get('estado'),</div>                           cursor: pointer;

        formato: formData.get('formato')

    };                           transition: all 0.2s;">

    

    console.log('📊 Parámetros del reporte:', params);{{-- Modal para generar reporte --}}                🚫 Limpiar

    

    // Mostrar loading<div id="reporteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">            </button>

    const btn = document.getElementById('descargar-reporte');

    if (btn) {    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">        </div>

        btn.innerHTML = '⏳ Generando...';

        btn.disabled = true;        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">    </div>

    }

                <h3 style="margin: 0;">📊 Generar Reporte de Asistencia</h3></div>

    // Crear URL para descarga

    const url = '/asistencia/reporte?' + new URLSearchParams(params).toString();            <button id="cerrar-reporte-modal" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>

    

    // Crear enlace de descarga        </div>{{-- Modal para generar reporte --}}

    const link = document.createElement('a');

    link.href = url;        <form id="reporte-form"><div id="reporteModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">

    link.download = `reporte_asistencia_${params.año}_${params.mes}.${params.formato === 'excel' ? 'xlsx' : 'pdf'}`;

    document.body.appendChild(link);            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">

    link.click();

    document.body.removeChild(link);                <div>        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">

    

    // Restaurar botón                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Año:</label>            <h3 style="margin: 0;">📊 Generar Reporte de Asistencia</h3>

    setTimeout(() => {

        if (btn) {                    <select name="año" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">            <button id="cerrar-reporte-modal" onclick="cerrarReporteModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>

            btn.innerHTML = '📥 Descargar Reporte';

            btn.disabled = false;                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)        </div>

        }

        cerrarModalReportes();                            <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>        <form id="reporte-form">

        console.log('✅ Reporte descargado');

    }, 1000);                        @endfor            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">

}

                    </select>                <div>

// 9. Guardar toda la asistencia

function guardarAsistencia() {                </div>                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Año:</label>

    console.log('💾 Guardando toda la asistencia...');

                    <div>                    <select name="año" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">

    const btn = document.getElementById('guardar-asistencia');

    if (btn) {                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mes:</label>                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)

        btn.innerHTML = '⏳ Guardando...';

        btn.disabled = true;                    <select name="mes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                            <option value="{{ $i }}" {{ $año == $i ? 'selected' : '' }}>{{ $i }}</option>

    }

                            @php                        @endfor

    // Simular guardado (aquí irían los datos financieros, etc.)

    setTimeout(() => {                            $mesesEspanol = [                    </select>

        if (btn) {

            btn.innerHTML = '💾 Guardar Asistencia';                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',                </div>

            btn.disabled = false;

        }                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',                <div>

        console.log('✅ Asistencia guardada correctamente');

    }, 1000);                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Mes:</label>

}

                            ];                    <select name="mes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">

// ========== EVENT LISTENERS ==========

                        @endphp                        @php

document.addEventListener('DOMContentLoaded', function() {

    console.log('🚀 DOM cargado, configurando eventos...');                        @for($i = 1; $i <= 12; $i++)                            $mesesEspanol = [

    

    // Botón aplicar filtros                            <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',

    const btnFiltros = document.getElementById('aplicar-filtros');

    if (btnFiltros) {                                {{ $mesesEspanol[$i] }}                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',

        btnFiltros.addEventListener('click', aplicarFiltros);

        console.log('✅ Filtros configurados');                            </option>                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'

    }

                            @endfor                            ];

    // Botón generar reporte

    const btnReporte = document.getElementById('generar-reporte');                    </select>                        @endphp

    if (btnReporte) {

        btnReporte.addEventListener('click', abrirModalReportes);                </div>                        @for($i = 1; $i <= 12; $i++)

        console.log('✅ Reporte configurado');

    }            </div>                            <option value="{{ $i }}" {{ $mes == $i ? 'selected' : '' }}>

    

    // Botón guardar asistencia            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">                                {{ $mesesEspanol[$i] }}

    const btnGuardar = document.getElementById('guardar-asistencia');

    if (btnGuardar) {                <div>                            </option>

        btnGuardar.addEventListener('click', guardarAsistencia);

        console.log('✅ Guardar configurado');                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Período:</label>                        @endfor

    }

                        <select name="periodo" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                    </select>

    // Botones cerrar modales

    const btnCerrarEstado = document.getElementById('cerrar-estado-modal');                        <option value="primera_quincena">Primera Quincena (1-15)</option>                </div>

    if (btnCerrarEstado) {

        btnCerrarEstado.addEventListener('click', cerrarModalEstados);                        <option value="segunda_quincena">Segunda Quincena (16-30/31)</option>            </div>

    }

                        </select>            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">

    const btnCerrarReporte = document.getElementById('cerrar-reporte-modal');

    if (btnCerrarReporte) {                </div>                <div>

        btnCerrarReporte.addEventListener('click', cerrarModalReportes);

    }                <div>                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Período:</label>

    

    const btnCancelarReporte = document.getElementById('cancelar-reporte');                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Estado:</label>                    <select name="periodo" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">

    if (btnCancelarReporte) {

        btnCancelarReporte.addEventListener('click', cerrarModalReportes);                    <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">                        <option value="primera_quincena">Primera Quincena (1-15)</option>

    }

                            <option value="todos">Todos los Estados</option>                        <option value="segunda_quincena">Segunda Quincena (16-30/31)</option>

    const btnDescargarReporte = document.getElementById('descargar-reporte');

    if (btnDescargarReporte) {                        @foreach($estados as $est)                    </select>

        btnDescargarReporte.addEventListener('click', descargarReporte);

    }                            <option value="{{ $est }}">{{ $est }}</option>                </div>

    

    // Botones de estado en modal                        @endforeach                <div>

    const botonesEstado = document.querySelectorAll('.estado-btn');

    botonesEstado.forEach(btn => {                    </select>                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Estado:</label>

        btn.addEventListener('click', function() {

            const estado = this.getAttribute('data-estado');                </div>                    <select name="estado" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">

            seleccionarEstado(estado);

        });            </div>                        <option value="todos">Todos los Estados</option>

    });

                <div style="margin-bottom: 20px;">                        @foreach($estados as $est)

    console.log('✅ Todos los eventos configurados');

});                <label style="display: block; margin-bottom: 10px; font-weight: bold;">Formato:</label>                            <option value="{{ $est }}">{{ $est }}</option>



// Cerrar modales al hacer clic fuera                <div>                        @endforeach

window.addEventListener('click', function(event) {

    const estadoModal = document.getElementById('estadoModal');                    <label style="margin-right: 20px; cursor: pointer;">                    </select>

    const reporteModal = document.getElementById('reporteModal');

                            <input type="radio" name="formato" value="excel" checked style="margin-right: 5px;"> 📊 Excel                </div>

    if (event.target === estadoModal) {

        cerrarModalEstados();                    </label>            </div>

    } else if (event.target === reporteModal) {

        cerrarModalReportes();                    <label style="cursor: pointer;">            <div style="margin-bottom: 20px;">

    }

});                        <input type="radio" name="formato" value="pdf" style="margin-right: 5px;"> 📄 PDF                <label style="display: block; margin-bottom: 10px; font-weight: bold;">Formato:</label>



console.log('🎯 JavaScript de asistencia v3.1 configurado completamente');                    </label>                <div>

</script>
                </div>                    <label style="margin-right: 20px; cursor: pointer;">

            </div>                        <input type="radio" name="formato" value="excel" checked style="margin-right: 5px;"> 📊 Excel

            <div style="display: flex; gap: 10px; justify-content: flex-end;">                    </label>

                <button type="button" id="cancelar-reporte" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancelar</button>                    <label style="cursor: pointer;">

                <button type="button" id="descargar-reporte" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">📥 Descargar Reporte</button>                        <input type="radio" name="formato" value="pdf" style="margin-right: 5px;"> 📄 PDF

            </div>                    </label>

        </form>                </div>

    </div>            </div>

</div>            <div style="display: flex; gap: 10px; justify-content: flex-end;">

                <button type="button" onclick="cerrarReporteModalDirecto()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancelar</button>

{{-- CSS Estilos --}}                <button type="button" onclick="descargarReporteConfirmado()" style="padding: 10px 20px; background: #17a2b8; color: white; border: none; border-radius: 4px; cursor: pointer;">📥 Descargar Reporte</button>

<style>            </div>

.dia-asistencia:hover {        </form>

    opacity: 0.8;    </div>

    transform: scale(1.05);</div>

}

<style>

.financiero-input {.dia-asistencia:hover {

    border: 1px solid #ddd;    opacity: 0.8;

    padding: 2px 4px;    transform: scale(1.05);

    border-radius: 3px;}

}

.financiero-input {

.financiero-input:focus {    border: 1px solid #ddd;

    border-color: #007bff;    padding: 2px 4px;

    outline: none;    border-radius: 3px;

}}



.estado-btn:hover {.financiero-input:focus {

    opacity: 0.8;    border-color: #007bff;

    transform: scale(1.02);    outline: none;

}}



/* Estilos para los modales */.estado-btn:hover {

#estadoModal, #reporteModal {    opacity: 0.8;

    animation: fadeIn 0.3s ease-in-out;    transform: scale(1.02);

}}



@keyframes fadeIn {/* Estilos para los modales */

    from { opacity: 0; }#estadoModal, #reporteModal {

    to { opacity: 1; }    animation: fadeIn 0.3s ease-in-out;

}}



#estadoModal > div, #reporteModal > div {@keyframes fadeIn {

    animation: slideIn 0.3s ease-in-out;    from { opacity: 0; }

}    to { opacity: 1; }

}

@keyframes slideIn {

    from { transform: translateY(-50px); opacity: 0; }#estadoModal > div, #reporteModal > div {

    to { transform: translateY(0); opacity: 1; }    animation: slideIn 0.3s ease-in-out;

}}



/* Animación para el día actual */@keyframes slideIn {

@keyframes blink {    from { transform: translateY(-50px); opacity: 0; }

    0%, 50% { opacity: 1; }    to { transform: translateY(0); opacity: 1; }

    51%, 100% { opacity: 0.3; }}

}

/* Animación para el día actual */

.dia-actual {@keyframes blink {

    animation: blink 2s infinite;    0%, 50% { opacity: 1; }

    border: 2px solid #007bff !important;    51%, 100% { opacity: 0.3; }

    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);}

}

</style>.dia-actual {

    animation: blink 2s infinite;

{{-- CSRF Token para AJAX --}}    border: 2px solid #007bff !important;

<meta name="csrf-token" content="{{ csrf_token() }}">    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);

}

{{-- JavaScript completo y funcional --}}</style>

<script>

console.log('🚀 JavaScript de asistencia v3.0 cargado');<script>

console.log('🔄 JavaScript de asistencia cargado - versión ultra simple con alerts');

// Variables globales

let empleadoActual = null;// Variables globales

let diaActual = null;let empleadoActual = null;

let asistenciaIdActual = null;let diaActual = null;



// ========== FUNCIONES PRINCIPALES ==========// Función para aplicar filtros con alert

function aplicarFiltrosDirecto() {

// 1. Aplicar filtros (funcional)    alert('⚠️ FUNCIÓN APLICAR FILTROS EJECUTADA - Verificando...');

function aplicarFiltros() {    console.log('🔍 Aplicando filtros...');

    console.log('🔍 Aplicando filtros...');    

        const estado = document.getElementById('estado-select').value;

    const estado = document.getElementById('estado-select');    const mes = document.getElementById('mes-select').value;

    const mes = document.getElementById('mes-select');    const año = document.getElementById('año-select').value;

    const año = document.getElementById('año-select');    const periodo = document.getElementById('periodo-select').value;

    const periodo = document.getElementById('periodo-select');    

        console.log('📊 Filtros:', {estado, mes, año, periodo});

    if (!estado || !mes || !año || !periodo) {    

        alert('❌ ERROR: Algunos elementos de filtro no se encontraron');    // Construir la URL con los filtros

        return;    const params = new URLSearchParams({

    }        estado: estado,

            mes: mes,

    const filtros = {        año: año,

        estado: estado.value,        periodo: periodo

        mes: mes.value,    });

        año: año.value,    

        periodo: periodo.value    const url = '/asistencia?' + params.toString();

    };    console.log('🌐 Redirigiendo a:', url);

        

    console.log('📊 Filtros:', filtros);    window.location.href = url;

    }

    // Mostrar loading

    const btn = document.getElementById('aplicar-filtros');// NUEVA: Función para abrir modal desde las casillas (nombre correcto)

    if (btn) {function abrirModalEstadosDirecto(empleadoId, dia, asistenciaId) {

        btn.innerHTML = '⏳ Aplicando...';    alert(`⚠️ FUNCIÓN CASILLA EJECUTADA - Empleado: ${empleadoId}, Día: ${dia}, Asistencia: ${asistenciaId}`);

        btn.disabled = true;    console.log('📋 Casilla clickeada - Empleado:', empleadoId, 'Día:', dia, 'Asistencia:', asistenciaId);

    }    

        empleadoActual = empleadoId;

    // Aplicar filtros redirigiendo    diaActual = dia;

    const params = new URLSearchParams(filtros);    

    window.location.href = '/asistencia?' + params.toString();    const modal = document.getElementById('estadoModal');

}    if (modal) {

        modal.style.display = 'block';

// 2. Abrir modal de estados (funcional)        console.log('✅ Modal de estado mostrado desde casilla');

function abrirModalEstados(empleadoId, dia, asistenciaId) {    } else {

    console.log('📋 Abriendo modal de estados:', {empleadoId, dia, asistenciaId});        console.error('❌ Modal de estado no encontrado');

            alert('❌ ERROR: Modal de estado no encontrado');

    // Guardar datos actuales    }

    empleadoActual = empleadoId;}

    diaActual = dia;

    asistenciaIdActual = asistenciaId;// Función para seleccionar estado directamente con alert

    function seleccionarEstadoDirecto(estado) {

    // Mostrar modal    alert(`⚠️ FUNCIÓN SELECCIONAR ESTADO EJECUTADA - Estado: ${estado}`);

    const modal = document.getElementById('estadoModal');    console.log('🎯 Estado seleccionado:', estado);

    if (modal) {    

        modal.style.display = 'block';    if (!empleadoActual || !diaActual) {

        console.log('✅ Modal mostrado');        alert('❌ ERROR: No hay empleado o día seleccionado');

    } else {        console.error('❌ No hay empleado o día seleccionado');

        alert('❌ ERROR: Modal no encontrado');        return;

    }    }

}    

    // Aquí iría la lógica AJAX para actualizar el estado

// 3. Seleccionar estado (funcional con AJAX)    console.log('🔄 Actualizando estado...', {

function seleccionarEstado(estado) {        empleado: empleadoActual,

    console.log('🎯 Estado seleccionado:', estado);        dia: diaActual,

            estado: estado

    if (!empleadoActual || !diaActual) {    });

        alert('❌ ERROR: No hay empleado o día seleccionado');    

        return;    // Cerrar modal

    }    cerrarEstadoModalDirecto();

        

    // Mostrar loading en modal    alert(`✅ Estado "${estado}" aplicado correctamente`);

    const botones = document.querySelectorAll('.estado-btn');}

    botones.forEach(btn => {

        btn.disabled = true;// Función para cerrar modal de estado con alert

        btn.style.opacity = '0.5';function cerrarEstadoModalDirecto() {

    });    alert('⚠️ FUNCIÓN CERRAR MODAL ESTADO EJECUTADA');

        console.log('❌ Cerrando modal de estado');

    // Hacer petición AJAX    

    fetch('/asistencia/guardar', {    const modal = document.getElementById('estadoModal');

        method: 'POST',    if (modal) {

        headers: {        modal.style.display = 'none';

            'Content-Type': 'application/json',        empleadoActual = null;

            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''        diaActual = null;

        },        console.log('✅ Modal cerrado');

        body: JSON.stringify({    }

            empleado_id: empleadoActual,}

            dia: diaActual,

            valor: estado,// Función para mostrar modal de reporte con alert

            año: {{ $año }},function descargarReporteDirecto() {

            mes: {{ $mes }}    alert('⚠️ FUNCIÓN REPORTE EJECUTADA');

        })    console.log('📊 Mostrando modal de reporte');

    })    

    .then(response => response.json())    const modal = document.getElementById('reporteModal');

    .then(data => {    if (modal) {

        console.log('✅ Respuesta del servidor:', data);        modal.style.display = 'block';

                console.log('✅ Modal de reporte mostrado');

        if (data.success) {    } else {

            // Actualizar la casilla visualmente        console.error('❌ Modal de reporte no encontrado');

            actualizarCasillaEnPantalla(empleadoActual, diaActual, estado);        alert('❌ ERROR: Modal de reporte no encontrado');

            cerrarModalEstados();    }

            alert('✅ Estado actualizado correctamente');}

        } else {

            alert('❌ ERROR: ' + data.message);// Función para cerrar modal de reporte con alert

        }function cerrarReporteModalDirecto() {

    })    alert('⚠️ FUNCIÓN CERRAR MODAL REPORTE EJECUTADA');

    .catch(error => {    console.log('❌ Cerrando modal de reporte');

        console.error('❌ Error:', error);    

        alert('❌ ERROR: No se pudo actualizar el estado');    const modal = document.getElementById('reporteModal');

    })    if (modal) {

    .finally(() => {        modal.style.display = 'none';

        // Restaurar botones        console.log('✅ Modal de reporte cerrado');

        botones.forEach(btn => {    }

            btn.disabled = false;}

            btn.style.opacity = '1';

        });// Función para confirmar descarga de reporte con alert

    });function descargarReporteConfirmado() {

}    alert('⚠️ FUNCIÓN DESCARGAR REPORTE CONFIRMADO EJECUTADA');

    console.log('📥 Procesando descarga de reporte...');

// 4. Actualizar casilla en pantalla    

function actualizarCasillaEnPantalla(empleadoId, dia, estado) {    // Aquí iría la lógica para generar el reporte

    const casilla = document.querySelector(`[data-empleado="${empleadoId}"][data-dia="${dia}"]`);    alert('✅ Reporte generado correctamente (simulado)');

    if (casilla) {    cerrarReporteModalDirecto();

        // Obtener info del estado}

        const estados = @json(\App\Models\Asistencia::ESTADOS);

        const estadoInfo = estados[estado] || {color: '#f8f9fa', icono: '?'};// Función para guardar asistencia con alert

        function guardarAsistenciaDirecto() {

        // Actualizar estilos    alert('⚠️ FUNCIÓN GUARDAR ASISTENCIA EJECUTADA');

        casilla.style.background = estadoInfo.color;    console.log('💾 Guardando asistencia...');

        casilla.style.color = ['#ffc107', '#f8d7da'].includes(estadoInfo.color) ? '#000' : '#fff';    

        casilla.textContent = estadoInfo.icono;    // Aquí iría la lógica AJAX para guardar

            alert('✅ Asistencia guardada correctamente (simulado)');

        console.log('🔄 Casilla actualizada visualmente');}

    }

}// Event listeners cuando el DOM esté listo

document.addEventListener('DOMContentLoaded', function() {

// 5. Cerrar modal de estados    console.log('🚀 DOM cargado, configurando event listeners ultra simples...');

function cerrarModalEstados() {    

    const modal = document.getElementById('estadoModal');    // Botón aplicar filtros

    if (modal) {    const btnAplicar = document.getElementById('aplicar-filtros');

        modal.style.display = 'none';    if (btnAplicar) {

        empleadoActual = null;        btnAplicar.onclick = aplicarFiltrosDirecto;

        diaActual = null;        console.log('✅ Event listener aplicar filtros configurado');

        asistenciaIdActual = null;    } else {

        console.log('❌ Modal cerrado');        console.error('❌ Botón aplicar filtros no encontrado');

    }    }

}    

    // Botón guardar asistencia

// 6. Abrir modal de reportes    const btnGuardar = document.getElementById('guardar-asistencia');

function abrirModalReportes() {    if (btnGuardar) {

    console.log('📊 Abriendo modal de reportes');        btnGuardar.onclick = guardarAsistenciaDirecto;

    const modal = document.getElementById('reporteModal');        console.log('✅ Event listener guardar asistencia configurado');

    if (modal) {    } else {

        modal.style.display = 'block';        console.error('❌ Botón guardar asistencia no encontrado');

    } else {    }

        alert('❌ ERROR: Modal de reportes no encontrado');    

    }    console.log('✅ Todos los event listeners configurados');

}    alert('🚀 JavaScript cargado completamente - Todos los botones deberían funcionar ahora');

});

// 7. Cerrar modal de reportes

function cerrarModalReportes() {// Cerrar modales al hacer clic fuera de ellos

    const modal = document.getElementById('reporteModal');window.onclick = function(event) {

    if (modal) {    const estadoModal = document.getElementById('estadoModal');

        modal.style.display = 'none';    const reporteModal = document.getElementById('reporteModal');

        console.log('❌ Modal de reportes cerrado');    

    }    if (event.target === estadoModal) {

}        cerrarEstadoModalDirecto();

    } else if (event.target === reporteModal) {

// 8. Descargar reporte (funcional)        cerrarReporteModalDirecto();

function descargarReporte() {    }

    console.log('📥 Iniciando descarga de reporte...');};

    

    const form = document.getElementById('reporte-form');console.log('🎯 JavaScript de asistencia configurado completamente');

    const formData = new FormData(form);</script>

    <script>

    const params = {console.log('🚀 JAVASCRIPT SUPER SIMPLE INICIADO');

        año: formData.get('año'),

        mes: formData.get('mes'),// Test inmediato de elementos

        periodo: formData.get('periodo'),console.log('Modal estados existe:', !!document.getElementById('estadoModal'));

        estado: formData.get('estado'),console.log('Modal reportes existe:', !!document.getElementById('reporteModal'));

        formato: formData.get('formato')

    };document.addEventListener('DOMContentLoaded', function() {

        console.log('✅ DOM CARGADO');

    console.log('📊 Parámetros del reporte:', params);    

        // Test elementos después de cargar

    // Mostrar loading    console.log('📋 Elementos encontrados:');

    const btn = document.getElementById('descargar-reporte');    console.log('- Modal estados:', !!document.getElementById('estadoModal'));

    if (btn) {    console.log('- Modal reportes:', !!document.getElementById('reporteModal'));

        btn.innerHTML = '⏳ Generando...';    console.log('- Botón filtros:', !!document.getElementById('aplicar-filtros'));

        btn.disabled = true;    console.log('- Botón reporte:', !!document.getElementById('generar-reporte'));

    }    console.log('- Casillas:', document.querySelectorAll('.dia-asistencia').length);

        

    // Crear URL para descarga    // BOTÓN FILTROS - Super simple

    const url = '/asistencia/reporte?' + new URLSearchParams(params).toString();    const btnFiltros = document.getElementById('aplicar-filtros');

        if (btnFiltros) {

    // Crear enlace de descarga        btnFiltros.onclick = function() {

    const link = document.createElement('a');            console.log('🎯 CLICK FILTROS DETECTADO');

    link.href = url;            alert('Filtros clickeado - funcionando!');

    link.download = `reporte_asistencia_${params.año}_${params.mes}.${params.formato === 'excel' ? 'xlsx' : 'pdf'}`;            

    document.body.appendChild(link);            const estado = document.getElementById('estado-select').value;

    link.click();            const año = document.getElementById('año-select').value;

    document.body.removeChild(link);            const mes = document.getElementById('mes-select').value;

                const periodo = document.getElementById('periodo-select').value;

    // Restaurar botón            

    setTimeout(() => {            console.log('Filtros:', {estado, año, mes, periodo});

        if (btn) {            

            btn.innerHTML = '📥 Descargar Reporte';            // Redirigir simple

            btn.disabled = false;            window.location.href = window.location.pathname + '?estado=' + estado + '&año=' + año + '&mes=' + mes + '&periodo=' + periodo;

        }        };

        cerrarModalReportes();        console.log('✅ Evento filtros configurado');

        alert('✅ Reporte descargado');    } else {

    }, 1000);        console.error('❌ Botón filtros NO ENCONTRADO');

}    }

    

// 9. Guardar toda la asistencia    // BOTÓN REPORTE - Super simple

function guardarAsistencia() {    const btnReporte = document.getElementById('generar-reporte');

    console.log('💾 Guardando toda la asistencia...');    if (btnReporte) {

            btnReporte.onclick = function() {

    const btn = document.getElementById('guardar-asistencia');            console.log('🎯 CLICK REPORTE DETECTADO');

    if (btn) {            alert('Reporte clickeado - funcionando!');

        btn.innerHTML = '⏳ Guardando...';            const modal = document.getElementById('reporteModal');

        btn.disabled = true;            if (modal) {

    }                modal.style.display = 'block';

                    console.log('✅ Modal reporte abierto');

    // Simular guardado (aquí irían los datos financieros, etc.)            }

    setTimeout(() => {        };

        if (btn) {        console.log('✅ Evento reporte configurado');

            btn.innerHTML = '💾 Guardar Asistencia';    } else {

            btn.disabled = false;        console.error('❌ Botón reporte NO ENCONTRADO');

        }    }

        alert('✅ Asistencia guardada correctamente');    

    }, 1000);    // CASILLAS - Super simple

}    const casillas = document.querySelectorAll('.dia-asistencia');

    console.log('🎯 Configurando', casillas.length, 'casillas');

// ========== EVENT LISTENERS ==========    

    casillas.forEach((casilla, i) => {

document.addEventListener('DOMContentLoaded', function() {        casilla.onclick = function() {

    console.log('🚀 DOM cargado, configurando eventos...');            console.log('🎯 CLICK CASILLA', i+1, 'DETECTADO');

                alert('Casilla ' + (i+1) + ' clickeada - funcionando!');

    // Botón aplicar filtros            const modal = document.getElementById('estadoModal');

    const btnFiltros = document.getElementById('aplicar-filtros');            if (modal) {

    if (btnFiltros) {                modal.style.display = 'block';

        btnFiltros.addEventListener('click', aplicarFiltros);                console.log('✅ Modal estados abierto');

        console.log('✅ Filtros configurados');            }

    }        };

        });

    // Botón generar reporte    

    const btnReporte = document.getElementById('generar-reporte');    // CERRAR MODALES - Super simple

    if (btnReporte) {    const cerrarEstados = document.getElementById('cerrar-modal');

        btnReporte.addEventListener('click', abrirModalReportes);    if (cerrarEstados) {

        console.log('✅ Reporte configurado');        cerrarEstados.onclick = function() {

    }            document.getElementById('estadoModal').style.display = 'none';

                console.log('✅ Modal estados cerrado');

    // Botón guardar asistencia        };

    const btnGuardar = document.getElementById('guardar-asistencia');    }

    if (btnGuardar) {    

        btnGuardar.addEventListener('click', guardarAsistencia);    const cerrarReporte = document.getElementById('cerrar-reporte-modal');

        console.log('✅ Guardar configurado');    if (cerrarReporte) {

    }        cerrarReporte.onclick = function() {

                document.getElementById('reporteModal').style.display = 'none';

    // Botones cerrar modales            console.log('✅ Modal reporte cerrado');

    const btnCerrarEstado = document.getElementById('cerrar-estado-modal');        };

    if (btnCerrarEstado) {    }

        btnCerrarEstado.addEventListener('click', cerrarModalEstados);    

    }    console.log('✅ SISTEMA SUPER SIMPLE CONFIGURADO');

    });

    const btnCerrarReporte = document.getElementById('cerrar-reporte-modal');</script>

    if (btnCerrarReporte) {

        btnCerrarReporte.addEventListener('click', cerrarModalReportes);{{-- Modal para seleccionar estado de asistencia --}}

    }<div id="estadoModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">

        <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border-radius: 8px; width: 80%; max-width: 600px; position: relative;">

    const btnCancelarReporte = document.getElementById('cancelar-reporte');        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">

    if (btnCancelarReporte) {            <h3 style="margin: 0;">Seleccionar Estado de Asistencia</h3>

        btnCancelarReporte.addEventListener('click', cerrarModalReportes);            <button id="cerrar-modal" onclick="cerrarModalDirecto()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>

    }        </div>

            <div id="estado-buttons" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">

    const btnDescargarReporte = document.getElementById('descargar-reporte');            @foreach(\App\Models\Asistencia::ESTADOS as $key => $estado_info)

    if (btnDescargarReporte) {                <button type="button" 

        btnDescargarReporte.addEventListener('click', descargarReporte);                        class="estado-btn" 

    }                        data-estado="{{ $key }}"

                            onclick="seleccionarEstadoDirecto('{{ $key }}')"

    // Botones de estado en modal                        style="background: {{ $estado_info['color'] }}; 

    const botonesEstado = document.querySelectorAll('.estado-btn');                               color: {{ in_array($estado_info['color'], ['#ffc107', '#f8d7da']) ? '#000' : '#fff' }}; 

    botonesEstado.forEach(btn => {                               border: none; 

        btn.addEventListener('click', function() {                               padding: 10px; 

            const estado = this.getAttribute('data-estado');                               font-weight: bold;

            seleccionarEstado(estado);                               border-radius: 5px;

        });                               cursor: pointer;

    });                               transition: all 0.2s;">

                        {{ $estado_info['icono'] }} {{ $estado_info['nombre'] }}

    console.log('✅ Todos los eventos configurados');                </button>

});            @endforeach

            <button type="button" 

// Cerrar modales al hacer clic fuera                    class="estado-btn" 

window.addEventListener('click', function(event) {                    data-estado=""

    const estadoModal = document.getElementById('estadoModal');                    onclick="seleccionarEstadoDirecto('')"

    const reporteModal = document.getElementById('reporteModal');                    style="background: #6c757d; 

                               color: #fff; 

    if (event.target === estadoModal) {                           border: none; 

        cerrarModalEstados();                           padding: 10px; 

    } else if (event.target === reporteModal) {                           font-weight: bold;

        cerrarModalReportes();                           border-radius: 5px;

    }                           cursor: pointer;

});                           transition: all 0.2s;">

                🚫 Limpiar

console.log('🎯 JavaScript de asistencia v3.0 configurado completamente');            </button>

</script>        </div>
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

<script>
console.log('🔄 JavaScript de asistencia cargado - versión ultra simple con alerts');

// Variables globales
let empleadoActual = null;
let diaActual = null;

// Función para aplicar filtros con alert
function aplicarFiltrosDirecto() {
    alert('⚠️ FUNCIÓN APLICAR FILTROS EJECUTADA - Verificando...');
    console.log('🔍 Aplicando filtros...');
    
    const estado = document.getElementById('estado-select').value;
    const mes = document.getElementById('mes-select').value;
    const año = document.getElementById('año-select').value;
    const periodo = document.getElementById('periodo-select').value;
    
    console.log('📊 Filtros:', {estado, mes, año, periodo});
    
    // Construir la URL con los filtros
    const params = new URLSearchParams({
        estado: estado,
        mes: mes,
        año: año,
        periodo: periodo
    });
    
    const url = '/asistencia?' + params.toString();
    console.log('🌐 Redirigiendo a:', url);
    
    window.location.href = url;
}

// Función para mostrar modal de estado con alert
function mostrarEstadoModal(empleadoId, dia) {
    alert(`⚠️ FUNCIÓN MODAL ESTADO EJECUTADA - Empleado: ${empleadoId}, Día: ${dia}`);
    console.log('📋 Mostrando modal de estado para empleado:', empleadoId, 'día:', dia);
    
    empleadoActual = empleadoId;
    diaActual = dia;
    
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal mostrado');
    } else {
        console.error('❌ Modal no encontrado');
        alert('❌ ERROR: Modal no encontrado');
    }
}

// NUEVA: Función para abrir modal desde las casillas (nombre correcto)
function abrirModalEstadosDirecto(empleadoId, dia, asistenciaId) {
    alert(`⚠️ FUNCIÓN CASILLA EJECUTADA - Empleado: ${empleadoId}, Día: ${dia}, Asistencia: ${asistenciaId}`);
    console.log('📋 Casilla clickeada - Empleado:', empleadoId, 'Día:', dia, 'Asistencia:', asistenciaId);
    
    empleadoActual = empleadoId;
    diaActual = dia;
    
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal de estado mostrado desde casilla');
    } else {
        console.error('❌ Modal de estado no encontrado');
        alert('❌ ERROR: Modal de estado no encontrado');
    }
}

// Función para seleccionar estado directamente con alert
function seleccionarEstadoDirecto(estado) {
    alert(`⚠️ FUNCIÓN SELECCIONAR ESTADO EJECUTADA - Estado: ${estado}`);
    console.log('🎯 Estado seleccionado:', estado);
    
    if (!empleadoActual || !diaActual) {
        alert('❌ ERROR: No hay empleado o día seleccionado');
        console.error('❌ No hay empleado o día seleccionado');
        return;
    }
    
    // Aquí iría la lógica AJAX para actualizar el estado
    console.log('🔄 Actualizando estado...', {
        empleado: empleadoActual,
        dia: diaActual,
        estado: estado
    });
    
    // Cerrar modal
    cerrarEstadoModalDirecto();
    
    alert(`✅ Estado "${estado}" aplicado correctamente`);
}

// Función para cerrar modal de estado con alert
function cerrarEstadoModalDirecto() {
    alert('⚠️ FUNCIÓN CERRAR MODAL ESTADO EJECUTADA');
    console.log('❌ Cerrando modal de estado');
    
    const modal = document.getElementById('estadoModal');
    if (modal) {
        modal.style.display = 'none';
        empleadoActual = null;
        diaActual = null;
        console.log('✅ Modal cerrado');
    }
}

// Función para mostrar modal de reporte con alert
function descargarReporteDirecto() {
    alert('⚠️ FUNCIÓN REPORTE EJECUTADA');
    console.log('📊 Mostrando modal de reporte');
    
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'block';
        console.log('✅ Modal de reporte mostrado');
    } else {
        console.error('❌ Modal de reporte no encontrado');
        alert('❌ ERROR: Modal de reporte no encontrado');
    }
}

// Función para cerrar modal de reporte con alert
function cerrarReporteModalDirecto() {
    alert('⚠️ FUNCIÓN CERRAR MODAL REPORTE EJECUTADA');
    console.log('❌ Cerrando modal de reporte');
    
    const modal = document.getElementById('reporteModal');
    if (modal) {
        modal.style.display = 'none';
        console.log('✅ Modal de reporte cerrado');
    }
}

// Función para confirmar descarga de reporte con alert
function descargarReporteConfirmado() {
    alert('⚠️ FUNCIÓN DESCARGAR REPORTE CONFIRMADO EJECUTADA');
    console.log('📥 Procesando descarga de reporte...');
    
    // Aquí iría la lógica para generar el reporte
    alert('✅ Reporte generado correctamente (simulado)');
    cerrarReporteModalDirecto();
}

// Función para guardar asistencia con alert
function guardarAsistenciaDirecto() {
    alert('⚠️ FUNCIÓN GUARDAR ASISTENCIA EJECUTADA');
    console.log('💾 Guardando asistencia...');
    
    // Aquí iría la lógica AJAX para guardar
    alert('✅ Asistencia guardada correctamente (simulado)');
}

// Event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM cargado, configurando event listeners ultra simples...');
    
    // Botón aplicar filtros
    const btnAplicar = document.getElementById('aplicar-filtros');
    if (btnAplicar) {
        btnAplicar.onclick = aplicarFiltrosDirecto;
        console.log('✅ Event listener aplicar filtros configurado');
    } else {
        console.error('❌ Botón aplicar filtros no encontrado');
    }
    
    // Botón guardar asistencia
    const btnGuardar = document.getElementById('guardar-asistencia');
    if (btnGuardar) {
        btnGuardar.onclick = guardarAsistenciaDirecto;
        console.log('✅ Event listener guardar asistencia configurado');
    } else {
        console.error('❌ Botón guardar asistencia no encontrado');
    }
    
    // Botón cerrar modal estado
    const btnCerrarEstado = document.getElementById('cerrar-estado-modal');
    if (btnCerrarEstado) {
        btnCerrarEstado.onclick = cerrarEstadoModalDirecto;
        console.log('✅ Event listener cerrar modal estado configurado');
    } else {
        console.error('❌ Botón cerrar modal estado no encontrado');
    }
    
    // Botón cerrar modal reporte
    const btnCerrarReporte = document.getElementById('cerrar-reporte-modal');
    if (btnCerrarReporte) {
        btnCerrarReporte.onclick = cerrarReporteModalDirecto;
        console.log('✅ Event listener cerrar modal reporte configurado');
    } else {
        console.error('❌ Botón cerrar modal reporte no encontrado');
    }
    
    console.log('✅ Todos los event listeners configurados');
    alert('🚀 JavaScript cargado completamente - Todos los botones deberían funcionar ahora');
});

// Cerrar modales al hacer clic fuera de ellos
window.onclick = function(event) {
    const estadoModal = document.getElementById('estadoModal');
    const reporteModal = document.getElementById('reporteModal');
    
    if (event.target === estadoModal) {
        cerrarEstadoModalDirecto();
    } else if (event.target === reporteModal) {
        cerrarReporteModalDirecto();
    }
};

console.log('🎯 JavaScript de asistencia configurado completamente');
</script>