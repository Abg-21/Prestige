<!DOCTYPE html>
<html>
<head>
    <title>Test Asistencia</title>
</head>
<body>
    <h1>Test del Módulo de Asistencia</h1>
    
    <p><strong>Año:</strong> {{ $año }}</p>
    <p><strong>Mes:</strong> {{ $mes }}</p>
    <p><strong>Período:</strong> {{ $periodo }}</p>
    <p><strong>Estado:</strong> {{ $estado }}</p>
    
    <h2>Empleados ({{ $empleados->count() }})</h2>
    
    @if($empleados->count() > 0)
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
            </tr>
            @foreach($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->IdEmpleados }}</td>
                    <td>{{ $empleado->Nombre }} {{ $empleado->Apellido_Paterno }} {{ $empleado->Apellido_Materno }}</td>
                    <td>{{ $empleado->Estado ?? 'Sin estado' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No hay empleados para mostrar.</p>
    @endif
    
    <h2>Días del Período</h2>
    <p>{{ implode(', ', $diasDelPeriodo) }}</p>
    
    <h2>Estados Disponibles</h2>
    <p>{{ implode(', ', $estados) }}</p>
</body>
</html>