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
    SessionController
};

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

// === RUTAS PROTEGIDAS ===
Route::middleware(['auth'])->group(function () {
    
    // === MENÚ PRINCIPAL ===
    Route::get('/menu', [AuthController::class, 'menu'])->name('menu');
    Route::get('/home', [AuthController::class, 'menu'])->name('home');
    
    // === CONTROL DE SESIÓN ===
    Route::post('/check-session', [SessionController::class, 'checkSession'])->name('check.session');
    Route::post('/update-activity', [SessionController::class, 'updateActivity'])->name('update.activity');
    
    // === RUTAS DE MÓDULOS PRINCIPALES ===
    Route::resource('candidatos', CandidatoController::class)->parameters(['candidatos' => 'id']);
    Route::post('candidatos/{id}/reject', [CandidatoController::class, 'reject'])->name('candidatos.reject');
    Route::post('candidatos/{id}/aprobar', [CandidatoController::class, 'aprobar'])->name('candidatos.aprobar');
    
    Route::resource('clientes', ClienteController::class)->parameters(['clientes' => 'id']);
    Route::resource('giros', GiroController::class)->parameters(['giros' => 'id']);
    Route::resource('empleados', EmpleadoController::class)->parameters(['empleados' => 'id']);
    Route::resource('puestos', PuestoController::class)->parameters(['puestos' => 'id']);
    Route::get('/puestos/lista', [PuestoController::class, 'lista'])->name('puestos.lista');
    Route::resource('documentos', DocumentoController::class)->parameters(['documentos' => 'id']);
    
    // === USUARIOS Y ROLES (Solo para administradores) ===
    Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'id']);
    Route::resource('roles', RolController::class)->parameters(['roles' => 'id']);

    // === MÓDULOS TEMPORALES ===
    Route::get('/asistencia', function () {
        return view('temp.modulo-no-disponible', ['modulo' => 'Asistencia']);
    })->name('asistencia.index');
    
    Route::get('/nomina', function () {
        return view('temp.modulo-no-disponible', ['modulo' => 'Nómina']);
    })->name('nomina.index');
});
