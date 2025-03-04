@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gestión de Contratos</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->idEmpleado }}</td>
                    <td>{{ $empleado->Nombre }} {{ $empleado->Apellido_P }} {{ $empleado->Apellido_M }}</td>
                    <td>{{ $empleado->puesto->Puesto ?? 'Sin asignar' }}</td>
                    <td>
                        <a href="{{ route('contratos.show', $empleado->idEmpleado) }}" class="btn btn-info btn-sm">Ver Contrato</a>
                        <a href="{{ route('contratos.download', $empleado->idEmpleado) }}" class="btn btn-success btn-sm">Descargar PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
