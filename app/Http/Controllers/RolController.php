<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class RolController extends Controller
{
    public function index()
    {
        Log::info('=== INICIANDO INDEX DE ROLES ===');
        
        try {
            $roles = Rol::with('usuarios')->get();
            Log::info('Roles obtenidos: ' . $roles->count());
            
            // Usar el nombre correcto de tu vista
            return view('roles.roles', compact('roles'));
            
        } catch (\Exception $e) {
            Log::error('ERROR EN INDEX DE ROLES: ' . $e->getMessage());
            Log::error('ARCHIVO: ' . $e->getFile());
            Log::error('LÍNEA: ' . $e->getLine());
            
            // En caso de error, devolver vista con datos vacíos
            $roles = collect([]);
            return view('roles.roles', compact('roles'))
                ->with('error', 'No se pudieron cargar los roles en este momento.');
        }
    }

    public function create()
    {
        $modulos = $this->getModulosDisponibles();
        $acciones = $this->getAccionesDisponibles();
        
        // Cambiar de 'roles.create' a 'roles.create_rol'
        return view('roles.create_rol', compact('modulos', 'acciones'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:50|unique:roles,nombre',
                'descripcion' => 'nullable|string|max:255',
                'permisos' => 'required|array'
            ]);

            Rol::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'permisos' => $validated['permisos']
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rol creado correctamente',
                    'redirect' => route('roles.index')
                ]);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al crear rol: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el rol'
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Error al crear el rol'])
                        ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $modulos = $this->getModulosDisponibles();
            $acciones = $this->getAccionesDisponibles();
            
            // Cambiar de 'roles.edit' a 'roles.edit_rol'
            return view('roles.edit_rol', compact('rol', 'modulos', 'acciones'));
            
        } catch (\Exception $e) {
            Log::error('Error al cargar rol para editar: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rol no encontrado'
                ], 404);
            }
            
            return redirect()->route('roles.index')
                ->with('error', 'Rol no encontrado');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rol = Rol::findOrFail($id);
            
            $validated = $request->validate([
                'nombre' => 'required|string|max:50|unique:roles,nombre,' . $id,
                'descripcion' => 'nullable|string|max:255',
                'permisos' => 'required|array'
            ]);

            $rol->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'permisos' => $validated['permisos']
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rol actualizado correctamente',
                    'redirect' => route('roles.index')
                ]);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar rol: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el rol'
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Error al actualizar el rol'])
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            
            // Verificar si el rol está siendo usado por usuarios
            if ($rol->usuarios()->count() > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el rol porque está asignado a usuarios'
                    ], 422);
                }
                
                return back()->withErrors(['error' => 'No se puede eliminar el rol porque está asignado a usuarios']);
            }

            $rol->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rol eliminado correctamente'
                ]);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado correctamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar rol: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el rol'
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Error al eliminar el rol']);
        }
    }

    private function getModulosDisponibles()
    {
        return [
            'candidatos' => 'Candidatos',
            'empleados' => 'Empleados',
            'puestos' => 'Puestos',
            'giros' => 'Giros',
            'clientes' => 'Clientes',
            'documentos' => 'Documentos',
            'asistencia' => 'Asistencia',
            'nomina' => 'Nómina',
            'eliminados' => 'Eliminados',
            'usuarios' => 'Usuarios',
            'roles' => 'Roles'
        ];
    }

    private function getAccionesDisponibles()
    {
        return [
            'ver' => 'Ver',
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar'
        ];
    }
}