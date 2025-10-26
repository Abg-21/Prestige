<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CandidatoController,
    EmpleadoController,
    PuestoController,
    GiroController,
    ClienteController,
    DocumentoController,
    UsuarioController,
    RolController,
    SessionController,
    EliminadoController,
    AsistenciaController
};

// === RUTAS DE PRUEBA ===
Route::get('/test-eliminados', function () {
    return view('test-eliminados');
})->middleware('auth')->name('test.eliminados');

Route::get('/test-eliminados-simple', function () {
    try {
        $eliminados = \App\Models\Eliminado::with(['eliminable', 'usuario'])->whereNull('restaurado_en')->get();
        return "<h1>Test Eliminados</h1><p>Registros encontrados: " . $eliminados->count() . "</p><pre>" . json_encode($eliminados->toArray(), JSON_PRETTY_PRINT) . "</pre>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><p>" . $e->getMessage() . "</p><p>Trace: " . $e->getTraceAsString() . "</p>";
    }
})->name('test.eliminados.simple');

Route::get('/test-eliminados-controller', function() {
    try {
        return app('App\Http\Controllers\EliminadoController')->index(request());
    } catch (\Exception $e) {
        return "<h1>Error en Controlador</h1><p>" . $e->getMessage() . "</p><p>File: " . $e->getFile() . ":" . $e->getLine() . "</p>";
    }
})->name('test.eliminados.controller');

Route::get('/test-eliminados-sin-auth', function() {
    try {
        $eliminados = \App\Models\Eliminado::with(['eliminable', 'usuario'])->whereNull('restaurado_en')->get();
        return view('Eliminados.simple', compact('eliminados'));
    } catch (\Exception $e) {
        return "<h1>Error</h1><p>" . $e->getMessage() . "</p><p>Line: " . $e->getLine() . "</p><p>File: " . $e->getFile() . "</p>";
    }
})->name('test.eliminados.sin.auth');

// === RUTAS DE AUTENTICACIÓN ===
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/emergency-logout', [AuthController::class, 'logout'])->name('emergency.logout');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('menu');
    }
    return redirect()->route('login');
});

// Ruta de prueba para asistencia SIN middleware de autenticación
Route::get('/asistencia-debug', [AsistenciaController::class, 'index']);

// Ruta de prueba ultra simple
Route::get('/test-simple', function() {
    return response()->json([
        'status' => 'OK',
        'message' => 'Servidor funcionando',
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);
});

// Ruta para verificar datos de empleados
Route::get('/test-empleados', function() {
    try {
        $empleados = \App\Models\Empleado::count();
        $estados = \App\Models\Empleado::select('Estado')->distinct()->pluck('Estado')->toArray();
        $empleado_sample = \App\Models\Empleado::first();
        
        return response()->json([
            'status' => 'OK',
            'empleados_count' => $empleados,
            'estados_disponibles' => $estados,
            'primer_empleado' => $empleado_sample ? [
                'id' => $empleado_sample->IdEmpleados,
                'nombre' => $empleado_sample->Nombre . ' ' . $empleado_sample->Apellido_Paterno,
                'estado' => $empleado_sample->Estado
            ] : null,
            'asistencias_count' => \App\Models\Asistencia::count()
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// === RUTAS PROTEGIDAS ===
Route::middleware(['auth'])->group(function () {
    
    // === MENÚ PRINCIPAL ===
    Route::get('/menu', [AuthController::class, 'menu'])->name('menu');
    Route::get('/home', [AuthController::class, 'menu'])->name('home');
    
    // === CONTROL DE SESIÓN - DESHABILITADO PARA RENDIMIENTO ===
    // Route::post('/check-session', [SessionController::class, 'checkSession'])->name('check.session');
    // Route::post('/update-activity', [SessionController::class, 'updateActivity'])->name('update.activity');
    
    // === RUTAS DE MÓDULOS PRINCIPALES ===
    Route::resource('candidatos', CandidatoController::class)->parameters(['candidatos' => 'id']);
    Route::post('candidatos/{id}/reject', [CandidatoController::class, 'reject'])->name('candidatos.reject');
    Route::post('candidatos/{id}/aprobar', [CandidatoController::class, 'aprobar'])->name('candidatos.aprobar');
    
    Route::resource('clientes', ClienteController::class)->parameters(['clientes' => 'id']);
    Route::resource('giros', GiroController::class)->parameters(['giros' => 'id']);
    Route::resource('empleados', EmpleadoController::class)->parameters(['empleados' => 'id']);
    Route::resource('puestos', PuestoController::class)->parameters(['puestos' => 'id']);
    Route::get('/puestos/lista', [PuestoController::class, 'lista'])->name('puestos.lista');
    Route::get('/api/puestos/options', [PuestoController::class, 'getOptionsForSelect'])->name('puestos.options');
    Route::resource('documentos', DocumentoController::class)->parameters(['documentos' => 'id']);
    
    // === MÓDULO DE ASISTENCIA ===
    Route::get('/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::get('/asistencia-test', function() {
        return response()->json([
            'status' => 'OK',
            'message' => 'Ruta de prueba funcionando',
            'timestamp' => now(),
            'empleados_count' => \App\Models\Empleado::count()
        ]);
    });
    Route::post('/asistencia/update', [AsistenciaController::class, 'updateAsistencia'])->name('asistencia.update');
    Route::post('/asistencia/financiero', [AsistenciaController::class, 'updateFinanciero'])->name('asistencia.financiero');
    Route::post('/asistencia/guardar', [AsistenciaController::class, 'guardarAsistencia'])->name('asistencia.guardar');
    Route::post('/asistencia/aplicar-filtros', [AsistenciaController::class, 'aplicarFiltros'])->name('asistencia.aplicar-filtros');
    Route::get('/asistencia/exportar', [AsistenciaController::class, 'exportar'])->name('asistencia.exportar');
    
    // === DEBUG ELIMINADOS ===
    Route::get('/debug-eliminados', function() {
        return view('debug-eliminados');
    })->name('debug.eliminados');
    
    // === MÓDULO DE ELIMINADOS ===
    Route::get('/eliminados', [EliminadoController::class, 'index'])->name('eliminados.index');
    Route::get('/eliminados/{tipo}/{id}', [EliminadoController::class, 'show'])->name('eliminados.show');
    Route::post('/eliminados/{tipo}/{id}/restaurar', [EliminadoController::class, 'restaurar'])->name('eliminados.restaurar');
    
    // === USUARIOS Y ROLES (Solo para administradores) ===
    Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'id']);
    Route::resource('roles', RolController::class)->parameters(['roles' => 'id']);

    // === MÓDULOS TEMPORALES ===
    Route::get('/nomina', function () {
        return view('temp.modulo-no-disponible', ['modulo' => 'Nómina']);
    })->name('nomina.index');
});

// === DEBUG TEMPORAL ===
Route::get('/asistencia-debug-simple', function() {
    try {
        $empleados = \App\Models\Empleado::limit(5)->get();
        return response()->json([
            'status' => 'success',
            'empleados_count' => $empleados->count(),
            'empleados' => $empleados->toArray()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Debug específico para AsistenciaController
Route::get('/asistencia-debug-controller', function() {
    try {
        $controller = new \App\Http\Controllers\AsistenciaController();
        $request = new \Illuminate\Http\Request();
        
        // Simular los parámetros básicos
        $request->merge([
            'año' => 2025,
            'mes' => 10,
            'estado' => 'todos',
            'periodo' => 'primera_quincena'
        ]);
        
        return $controller->index($request);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Debug para aplicar filtros sin autenticación
Route::post('/asistencia-debug-filtros', function(\Illuminate\Http\Request $request) {
    try {
        $controller = new \App\Http\Controllers\AsistenciaController();
        return $controller->aplicarFiltros($request);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Debug para guardar asistencia sin autenticación  
Route::post('/asistencia-debug-guardar', function(\Illuminate\Http\Request $request) {
    try {
        $controller = new \App\Http\Controllers\AsistenciaController();
        return $controller->guardarAsistencia($request);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Debug para exportar reportes sin autenticación
Route::get('/asistencia-debug-exportar', function(\Illuminate\Http\Request $request) {
    try {
        $controller = new \App\Http\Controllers\AsistenciaController();
        return $controller->exportar($request);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Ruta de debug para verificar token CSRF
Route::get('/csrf-debug', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'app_key' => config('app.key') ? 'SET' : 'NOT SET'
    ]);
});

// Debug específico para login
Route::get('/debug-login-web', function() {
    $email = 'admin@grupoprestige.com.mx';
    $password = '123456';
    
    $usuario = \App\Models\Usuario::where('correo', $email)->whereNull('eliminado_en')->first();
    
    return response()->json([
        'usuario_existe' => $usuario ? true : false,
        'usuario_nombre' => $usuario ? $usuario->nombre : null,
        'password_correcta' => $usuario ? \Illuminate\Support\Facades\Hash::check($password, $usuario->contraseña) : false,
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'auth_guard' => config('auth.defaults.guard'),
        'auth_provider' => config('auth.providers.users.model')
    ]);
});

// Incluir rutas de debug
require __DIR__.'/debug.php';

// Ruta de prueba para filtros
Route::get('/test-filtros', function() {
    $año = 2025;
    $mes = 10;  // Octubre que tiene datos
    $estado = 'todos';
    $periodo = 'primera_quincena';
    
    // Obtener empleados igual que en el controlador
    $query = \App\Models\Empleado::with(['puesto.cliente']);
    
    if ($estado !== 'todos') {
        $query->where('Estado', $estado);
    }
    
    $empleados = $query->orderBy('Apellido_Paterno')
                      ->orderBy('Apellido_Materno')
                      ->orderBy('Nombre')
                      ->get();
    
    // Obtener asistencias
    $asistencias = [];
    foreach ($empleados as $empleado) {
        $asistencia = \App\Models\Asistencia::where('empleado_id', $empleado->IdEmpleados)
                                           ->where('año', $año)
                                           ->where('mes', $mes)
                                           ->first();
        
        if ($asistencia) {
            $asistencias[$empleado->IdEmpleados] = $asistencia;
        }
    }
    
    return response()->json([
        'empleados_count' => $empleados->count(),
        'asistencias_count' => count($asistencias),
        'first_empleado' => $empleados->first(),
        'estados' => \App\Models\Empleado::select('Estado')->distinct()->pluck('Estado')->toArray()
    ]);
});
