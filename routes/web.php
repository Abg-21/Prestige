<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
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


// Rutas para autenticación y gestión de contraseñas
Route::get('reset-password', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
Route::post('email', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Registro y login
// Rutas públicas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// Rutas protegidas por autenticación
Route::middleware(['auth', 'session.timeout'])->group(function () {
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rutas para verificar y actualizar la actividad de la sesión
    Route::post('/check-session', [AuthController::class, 'checkSession'])->name('check.session');
    Route::post('/update-activity', [AuthController::class, 'updateActivity'])->name('update.activity');
    
    // Aquí irían tus otras rutas protegidas, como 'menu'
    Route::get('/menu', function () {
        return view('menu'); // Asegúrate de crear esta vista
    })->name('menu');
});


// Verificación de correo
    Route::middleware(['auth'])->group(function () {
    // Mostrar la notificación de verificación
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');

    // Verificar el correo con el token
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');

    // Reenviar el enlace de verificación
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
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
