<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsuarioController extends Controller
{
    public function index()
    {
        try {
            Log::info('=== INICIANDO INDEX DE USUARIOS ===');
            
            // Paso 1: Verificar que podemos acceder a los modelos
            Log::info('Paso 1: Verificando modelos');
            
            // Verificar que las clases existen
            if (!class_exists(\App\Models\Usuario::class)) {
                throw new \Exception('Modelo Usuario no encontrado');
            }
            
            if (!class_exists(\App\Models\Rol::class)) {
                throw new \Exception('Modelo Rol no encontrado');
            }
            
            Log::info('Paso 2: Modelos verificados');
            
            // Intentar consultar usuarios con roles
            $usuarios = \App\Models\Usuario::with('roles')->get();
            Log::info('Paso 3: Usuarios obtenidos: ' . $usuarios->count());
            
            // Intentar consultar roles
            $roles = \App\Models\Rol::all();
            Log::info('Paso 4: Roles obtenidos: ' . $roles->count());
            
            // Intentar cargar vista sin datos complejos
            Log::info('Paso 5: Intentando renderizar vista');
            
            $data = [
                'usuarios' => $usuarios,
                'roles' => $roles
            ];
            
            if (request()->ajax()) {
                Log::info('Petición AJAX detectada');
                return view('usuarios.user', $data);
            }
            
            // Si no es AJAX, devolver con layout completo
            return view('usuarios.user', $data);
            
        } catch (\Exception $e) {
            Log::error('ERROR EN INDEX: ' . $e->getMessage());
            Log::error('ARCHIVO: ' . $e->getFile());
            Log::error('LÍNEA: ' . $e->getLine());
            Log::error('TRACE: ' . $e->getTraceAsString());
            
            if (request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ], 500);
            }
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function create()
    {
        return response()->json(['message' => 'Create method works']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Store method works']);
    }

    public function show($id)
    {
        return response()->json(['message' => 'Show method works']);
    }

    public function edit($id)
    {
        return response()->json(['message' => 'Edit method works']);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Update method works']);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Destroy method works']);
    }
}