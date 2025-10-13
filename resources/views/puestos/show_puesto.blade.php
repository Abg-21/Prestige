<div class="container" style="max-width: 800px; margin-top: 40px;">
    <div style="background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px 28px;">
        <h2 style="color: #447D9B; margin-bottom: 24px;">Detalles del Puesto</h2>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Categoría:</label>
                <p>{{ $puesto->Categoría }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Puesto:</label>
                <p>{{ $puesto->Puesto }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Giro:</label>
                <p>{{ $puesto->giro ? $puesto->giro->Nombre : 'Sin giro' }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Cliente:</label>
                <p>{{ $puesto->cliente ? $puesto->cliente->Nombre : 'Sin cliente' }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Zona:</label>
                <p>{{ $puesto->Zona }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Estado:</label>
                <p>{{ $puesto->Estado }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="fw-bold">Edad:</label>
                <p>{{ $puesto->Edad }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Escolaridad:</label>
                <p>{{ $puesto->Escolaridad }}</p>
            </div>
        </div>

        <div class="mb-4">
            <label class="fw-bold">Experiencia:</label>
            <p>{{ $puesto->Experiencia }}</p>
        </div>

        @if($puesto->Conocimientos)
        <div class="mb-4">
            <label class="fw-bold">Conocimientos:</label>
            <ul>
                @foreach(json_decode($puesto->Conocimientos) as $conocimiento)
                    <li>{{ $conocimiento }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($puesto->Funciones)
        <div class="mb-4">
            <label class="fw-bold">Funciones:</label>
            <ul>
                @foreach(json_decode($puesto->Funciones) as $funcion)
                    <li>{{ $funcion }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($puesto->Habilidades)
        <div class="mb-4">
            <label class="fw-bold">Habilidades:</label>
            <ul>
                @foreach(json_decode($puesto->Habilidades) as $habilidad)
                    <li>{{ $habilidad }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('puestos.index') }}" class="btn btn-secondary ajax-link">Volver</a>
        </div>
    </div>
</div>