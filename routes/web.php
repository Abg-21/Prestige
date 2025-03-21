<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\GiroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\ContratoController;

//Pagina principal
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');
Route::get('/menu', function () {
    return view('auth.menu');
})->name('menu');

// Registro y login
// Rutas públicas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// Rutas protegidas por autenticación
Route::middleware(['auth', \App\Http\Middleware\SessionTimeout::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


//Candidatos
Route::resource('candidatos', CandidatoController::class);

//Puestos
Route::resource('puestos', PuestoController::class);
Route::post('/puestos', [PuestoController::class, 'store'])->name('puestos.store');

// Ruta personalizada para el almacenamiento temporal (si es necesaria)
Route::post('puestos/store_temp', [PuestoController::class, 'storeTemp'])->name('puestos.store_temp');

//Giro
Route::resource('giros', GiroController::class);
Route::get('/giros/create', [GiroController::class, 'create'])->name('giros.create');
Route::get('/giros/{id}', [GiroController::class, 'show'])->name('giros.detalle');
Route::post('/giros/store', [GiroController::class, 'store'])->name('giros.store');
//Cliente
Route::resource('clientes', ClienteController::class);
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.detalle');
Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');

//Notificaciones
Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
Route::get('/notificaciones/marcar-como-leido/{id}', [NotificacionController::class, 'marcarComoLeido']);
Route::get('/notificaciones/crear', [NotificacionController::class, 'crearNotificacion']);

//Menu


//Documentos
Route::resource('documentos', DocumentoController::class);

//mail
Route::get('/mail', [MailController::class, 'sendMail']);

//Empleado
Route::resource('empleados', EmpleadoController::class);

//Documentos
Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
Route::get('/documentos/{documento}/download', [DocumentoController::class, 'download'])->name('documentos.download');
Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');

// Rutas protegidas para el Administrador
Route::middleware(['auth', 'role:Administrador'])->group(function () {
    // Gestión de Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create'); // Agregar esta línea
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');

    // Gestión de Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');

    Route::middleware(['auth', 'role:Administrador'])->group(function () {
    // Gestión de Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create'); // Agregar esta línea
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
});
});
//Contratos
Route::get('contratos', [ContratoController::class, 'index'])->name('contratos.index');
Route::get('contratos/{id}', [ContratoController::class, 'show'])->name('contratos.show');
Route::get('contratos/{id}/download', [ContratoController::class, 'download'])->name('contratos.download');
Route::resource('contratos', ContratoController::class);
