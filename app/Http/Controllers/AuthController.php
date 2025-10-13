<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['logout']);
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('menu');
        }
        
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = [
                'correo' => $request->email,
                'password' => $request->password
            ];
            
            // Buscar usuario que no esté eliminado
            $usuario = Usuario::where('correo', $request->email)
                              ->whereNull('eliminado_en')
                              ->first();
                              
            if (!$usuario) {
                return back()->withErrors([
                    'email' => 'Usuario no encontrado o inactivo.',
                ])->withInput($request->only('email'));
            }
            
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('menu'));
            }

            return back()->withErrors([
                'email' => 'Las credenciales no coinciden.',
            ])->withInput($request->only('email'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Mostrar menú principal CON PERMISOS REALES
     */
    public function menu()
    {
        $usuario = auth()->user();
        
        if (!$usuario) {
            return redirect()->route('login');
        }

        // Obtener permisos REALES del usuario basado en sus roles
        $permisos = $this->obtenerPermisosUsuario($usuario);

        $nombreUsuario = $usuario->nombre ?? 'Usuario';
        $mensajeBienvenida = "Bienvenido, " . $nombreUsuario;

        return view('auth.menu', compact('permisos', 'usuario', 'mensajeBienvenida'));
    }

    /**
     * Obtener permisos reales del usuario basado en sus roles
     */
    private function obtenerPermisosUsuario($usuario)
    {
        $permisos = [];
        $esAdmin = false;
        if ($usuario->roles && $usuario->roles->count() > 0) {
            foreach ($usuario->roles as $rol) {
                if (strtolower($rol->nombre) === 'administrador') {
                    $esAdmin = true;
                    break;
                }
            }
            if ($esAdmin) {
                // Dar todos los permisos posibles
                $modulos = [
                    'candidatos', 'empleados', 'puestos', 'giros', 'clientes', 'documentos', 'asistencia', 'nomina', 'eliminados', 'usuarios', 'roles', 'contratos'
                ];
                $acciones = ['ver', 'crear', 'editar', 'eliminar'];
                foreach ($modulos as $modulo) {
                    $permisos[$modulo] = true;
                    foreach ($acciones as $accion) {
                        $permisos[$modulo . '_' . $accion] = true;
                    }
                }
            } else {
                foreach ($usuario->roles as $rol) {
                    $rolPermisos = is_string($rol->permisos) ? json_decode($rol->permisos, true) : $rol->permisos;
                    if (is_array($rolPermisos)) {
                        foreach ($rolPermisos as $modulo => $acciones) {
                            if (is_array($acciones)) {
                                foreach ($acciones as $accion => $valor) {
                                    if ($valor) {
                                        $permisos[$modulo . '_' . $accion] = true;
                                        $permisos[$modulo] = true; // Para mostrar el módulo
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // Si no tiene roles, NO dar permisos por defecto
        return $permisos;
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('logout', 'Sesión cerrada');
    }
}
