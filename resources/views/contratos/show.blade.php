@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Contrato de Trabajo</h2>
    <div class="contrato">
        <p><strong>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO DETERMINADO</strong></p>
        <p>
            QUE CELEBRAN Y FIRMAN POR DUPLICADO EN LA CIUDAD DE CUERNAVACA, MORELOS, EL {{ date('d/m/Y') }}, POR UNA PARTE LA PERSONA MORAL GRUPO PROMOCIONAL PRESTIGE S.A. DE C.V., REPRESENTADA POR EL C. ENRIQUE ESTRADA GAYTAN, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ “EL PATRÓN”, Y POR LA OTRA, EL (LA) C. <strong>{{ $empleado->Nombre }} {{ $empleado->Apellido_P }} {{ $empleado->Apellido_M }}</strong>, A QUIEN EN LO SUCESIVO SE DESIGNARÁ COMO “EL TRABAJADOR(A)”.
        </p>
        <!-- Aquí continúa el contrato... -->
        <h3>Declaraciones</h3>
        <p>
            I.- Declara “EL PATRÓN” que se encuentra legalmente constituida...<br>
            II.- “EL TRABAJADOR(A)” declara ser de nacionalidad mexicana, originario(a) de [{{ $empleado->Ciudad }}], con estado de residencia [{{ $empleado->Estado }}] y que posee [{{ $empleado->Experiencia }}] años de experiencia, etc.
        </p>
        <!-- Continúa el contrato... -->
    </div>
    <a href="{{ route('contratos.download', $empleado->idEmpleado) }}" class="btn btn-success">Descargar PDF</a>
</div>
@endsection
