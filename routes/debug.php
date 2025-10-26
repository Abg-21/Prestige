<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Puesto;

// Ruta temporal de debug para interceptar todas las peticiones
Route::any('/debug-ajax/{action?}', function (Request $request, $action = 'test') {
    \Log::info('=== DEBUG AJAX INTERCEPTED ===', [
        'action' => $action,
        'method' => $request->method(),
        'url' => $request->url(),
        'headers' => $request->headers->all(),
        'data' => $request->all(),
        'auth' => auth()->check(),
        'user' => auth()->check() ? auth()->user()->nombre : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
    
    switch ($action) {
        case 'modal':
            // Test modal puesto
            try {
                $controller = new \App\Http\Controllers\PuestoController();
                $response = $controller->create($request);
                
                \Log::info('Modal create response', [
                    'type' => get_class($response),
                    'content_length' => method_exists($response, 'getContent') ? strlen($response->getContent()) : 'N/A'
                ]);
                
                return $response;
            } catch (\Exception $e) {
                \Log::error('Error en modal create', ['error' => $e->getMessage()]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        case 'eliminate':
            // Test eliminación
            $puestoId = $request->input('puesto_id', 2); // ID del puesto a eliminar
            
            try {
                $puesto = Puesto::findOrFail($puestoId);
                \Log::info('Puesto encontrado para eliminar', [
                    'id' => $puesto->idPuestos,
                    'nombre' => $puesto->Puesto
                ]);
                
                $antes = Puesto::count();
                \Log::info('Puestos antes de eliminar: ' . $antes);
                
                $eliminado = $puesto->delete();
                \Log::info('Resultado delete(): ' . ($eliminado ? 'TRUE' : 'FALSE'));
                
                $despues = Puesto::count();
                \Log::info('Puestos después de eliminar: ' . $despues);
                
                $existe = Puesto::where('idPuestos', $puestoId)->exists();
                \Log::info('¿Puesto aún existe?: ' . ($existe ? 'SÍ' : 'NO'));
                
                return response()->json([
                    'success' => true,
                    'eliminado' => $eliminado,
                    'antes' => $antes,
                    'despues' => $despues,
                    'aun_existe' => $existe
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Error en eliminación test', ['error' => $e->getMessage()]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        case 'store':
            // Test guardar puesto
            try {
                \Log::info('Debug store puesto', $request->all());
                
                $controller = new \App\Http\Controllers\PuestoController();
                $response = $controller->store($request);
                
                \Log::info('Store response', [
                    'type' => get_class($response),
                    'status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 'N/A'
                ]);
                
                return $response;
                
            } catch (\Exception $e) {
                \Log::error('Error en store test', ['error' => $e->getMessage()]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        case 'lista':
            // Test lista puestos
            try {
                $controller = new \App\Http\Controllers\PuestoController();
                $response = $controller->lista($request);
                
                \Log::info('Lista response', [
                    'type' => get_class($response),
                    'content_length' => method_exists($response, 'getContent') ? strlen($response->getContent()) : 'N/A'
                ]);
                
                return $response;
                
            } catch (\Exception $e) {
                \Log::error('Error en lista test', ['error' => $e->getMessage()]);
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        default:
            return response()->json([
                'message' => 'Debug route working',
                'authenticated' => auth()->check(),
                'user' => auth()->check() ? auth()->user()->nombre : null,
                'timestamp' => now()->toDateTimeString()
            ]);
    }
})->middleware('auth');

// Rutas temporales SIN autenticación para debug de modal
Route::any('/debug-ajax-noauth/{action?}', function (Request $request, $action = 'test') {
    \Log::info('=== DEBUG AJAX NO AUTH ===', [
        'action' => $action,
        'method' => $request->method(),
        'url' => $request->url(),
        'data' => $request->all()
    ]);
    
    switch ($action) {
        case 'modal':
            try {
                // Crear formulario completo del modal de puesto
                return response('<div class="p-4">
                    <h4>🔧 Crear Nuevo Puesto</h4>
                    <form id="form-nuevo-puesto-modal">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Nombre del Puesto *</label>
                                <input type="text" name="Puesto" class="form-control" required maxlength="50" placeholder="Ej: Desarrollador Web">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Descripción del Puesto</label>
                                <textarea name="Descripcion" class="form-control" rows="4" maxlength="200" placeholder="Descripción detallada del puesto..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Campos dinámicos para Conocimientos -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Conocimientos Requeridos</label>
                            <div id="conocimientos-list-modal">
                                <div class="input-group mb-2">
                                    <input type="text" name="Conocimientos[]" class="form-control" placeholder="Conocimiento" maxlength="100">
                                    <button type="button" class="btn btn-danger remove-conocimiento-modal">-</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="add-conocimiento-modal">+ Agregar Conocimiento</button>
                        </div>
                        
                        <!-- Campos dinámicos para Funciones -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Funciones del Puesto</label>
                            <div id="funciones-list-modal">
                                <div class="input-group mb-2">
                                    <input type="text" name="Funciones[]" class="form-control" placeholder="Función" maxlength="100">
                                    <button type="button" class="btn btn-danger remove-funcion-modal">-</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="add-funcion-modal">+ Agregar Función</button>
                        </div>
                        
                        <!-- Campos dinámicos para Habilidades -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Habilidades Necesarias</label>
                            <div id="habilidades-list-modal">
                                <div class="input-group mb-2">
                                    <input type="text" name="Habilidades[]" class="form-control" placeholder="Habilidad" maxlength="100">
                                    <button type="button" class="btn btn-danger remove-habilidad-modal">-</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="add-habilidad-modal">+ Agregar Habilidad</button>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear Puesto</button>
                        </div>
                    </form>
                    
                    <script>
                    // Configurar campos dinámicos del modal
                    $(document).ready(function() {
                        // Conocimientos
                        $("#add-conocimiento-modal").off("click").on("click", function() {
                            const container = $("#conocimientos-list-modal");
                            const newField = $("<div class=\"input-group mb-2\"><input type=\"text\" name=\"Conocimientos[]\" class=\"form-control\" placeholder=\"Conocimiento\" maxlength=\"100\"><button type=\"button\" class=\"btn btn-danger remove-conocimiento-modal\">-</button></div>");
                            container.append(newField);
                        });
                        
                        // Funciones
                        $("#add-funcion-modal").off("click").on("click", function() {
                            const container = $("#funciones-list-modal");
                            const newField = $("<div class=\"input-group mb-2\"><input type=\"text\" name=\"Funciones[]\" class=\"form-control\" placeholder=\"Función\" maxlength=\"100\"><button type=\"button\" class=\"btn btn-danger remove-funcion-modal\">-</button></div>");
                            container.append(newField);
                        });
                        
                        // Habilidades
                        $("#add-habilidad-modal").off("click").on("click", function() {
                            const container = $("#habilidades-list-modal");
                            const newField = $("<div class=\"input-group mb-2\"><input type=\"text\" name=\"Habilidades[]\" class=\"form-control\" placeholder=\"Habilidad\" maxlength=\"100\"><button type=\"button\" class=\"btn btn-danger remove-habilidad-modal\">-</button></div>");
                            container.append(newField);
                        });
                        
                        // Remover campos
                        $(document).off("click", ".remove-conocimiento-modal, .remove-funcion-modal, .remove-habilidad-modal")
                                  .on("click", ".remove-conocimiento-modal, .remove-funcion-modal, .remove-habilidad-modal", function() {
                            const container = $(this).closest("div[id$=\"-list-modal\"]");
                            if (container.find(".input-group").length > 1) {
                                $(this).closest(".input-group").remove();
                            } else {
                                alert("Debe mantener al menos un campo");
                            }
                        });
                    });
                    </script>
                </div>');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        case 'store':
            try {
                // Simular guardado exitoso
                return response()->json([
                    'success' => true,
                    'message' => 'Puesto creado exitosamente (DEBUG)',
                    'puesto_id' => rand(1, 100),
                    'debug' => true
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        case 'lista':
            try {
                // Retornar lista de opciones simulada
                return response('<option value="">Seleccionar puesto</option>
                    <option value="1">Desarrollador Web</option>
                    <option value="2">Diseñador Gráfico</option>
                    <option value="3">Analista de Sistemas</option>
                    <option value="4">Administrador de Base de Datos</option>
                    <option value="5">Gerente de Proyecto (DEBUG)</option>');
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        default:
            return response()->json([
                'message' => 'Debug route working - NO AUTH',
                'timestamp' => now()->toDateTimeString()
            ]);
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Ruta para probar la página de candidatos sin autenticación
Route::get('/debug-candidatos-create', function () {
    try {
        // Simular autenticación temporal
        auth()->loginUsingId(4); // ID del usuario admin
        
        return view('candidatos.create_candidatos');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});