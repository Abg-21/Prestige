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

        @php
            // Función helper para decodificar campos JSON de manera segura
            function decodificarCampo($valor) {
                if (empty($valor) || $valor === 'null' || is_null($valor)) {
                    return [];
                }
                
                if (is_array($valor)) {
                    return $valor;
                }
                
                if (is_string($valor)) {
                    // Intentar decodificar JSON
                    if (substr($valor, 0, 1) === '[' || substr($valor, 0, 1) === '{') {
                        $decoded = json_decode($valor, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            return array_filter($decoded); // Remover elementos vacíos
                        }
                    }
                    
                    // Si no es JSON, tratar como texto separado por comas
                    return array_filter(array_map('trim', explode(',', $valor)));
                }
                
                return [];
            }
            
            $conocimientos = decodificarCampo($puesto->Conocimientos);
            $funciones = decodificarCampo($puesto->Funciones);
            $habilidades = decodificarCampo($puesto->Habilidades);
        @endphp

        @if(!empty($conocimientos))
        <div class="mb-4">
            <label class="fw-bold">Conocimientos:</label>
            <ul>
                @foreach($conocimientos as $conocimiento)
                    @if(!empty($conocimiento))
                        <li>{{ $conocimiento }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($funciones))
        <div class="mb-4">
            <label class="fw-bold">Funciones:</label>
            <ul>
                @foreach($funciones as $funcion)
                    @if(!empty($funcion))
                        <li>{{ $funcion }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        @if(!empty($habilidades))
        <div class="mb-4">
            <label class="fw-bold">Habilidades:</label>
            <ul>
                @foreach($habilidades as $habilidad)
                    @if(!empty($habilidad))
                        <li>{{ $habilidad }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('puestos.index') }}" class="btn btn-secondary ajax-link">Volver</a>
        </div>
    </div>
</div>