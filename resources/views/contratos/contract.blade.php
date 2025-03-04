<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Trabajo</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .contrato { margin: 0 auto; width: 90%; }
        h2 { text-align: center; }
        p { text-align: justify; }
    </style>
</head>
<body>
    <div class="contrato">
        <h2>Contrato Individual de Trabajo</h2>
        <p>
            QUE CELEBRAN Y FIRMAN POR DUPLICADO EN LA CIUDAD DE CUERNAVACA, MORELOS, EL {{ date('d/m/Y') }},
            POR UNA PARTE LA PERSONA MORAL GRUPO PROMOCIONAL PRESTIGE S.A. DE C.V., REPRESENTADA POR EL C. ENRIQUE ESTRADA GAYTAN, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ “EL PATRÓN”, Y POR LA OTRA, EL (LA) C. <strong>{{ $empleado->Nombre }} {{ $empleado->Apellido_P }} {{ $empleado->Apellido_M }}</strong>,
            A QUIEN EN LO SUCESIVO SE DESIGNARÁ COMO “EL TRABAJADOR(A)”.
        </p>
        <!-- Resto del contrato con sustitución de datos del empleado -->
        <p>
            EL TRABAJADOR(A) declara tener la capacidad y experiencia necesaria para desempeñar el puesto de <strong>{{ $empleado->puesto->Puesto ?? 'Sin asignar' }}</strong>...
        </p>
    </div>
</body>
</html>
