<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\CacheHelper;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        try {
            // CACHE ULTRARRÁPIDO usando helper optimizado
            $empleados = CacheHelper::getEmpleados();
            
            return view('empleados.empleado', compact('empleados'));
            
        } catch (\Exception $e) {
            // Fallback sin cache
            $empleados = collect([]);
            return view('empleados.empleado', compact('empleados'));
        }
    }

    public function create()
    {
        // CACHE ULTRARRÁPIDO
        $puestos = CacheHelper::getPuestos();
        return view('empleados.create_empleado', compact('puestos'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_Paterno' => 'required|string|max:20',
                'Apellido_Materno' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:99',
                'Telefono' => 'required|string|max:15',
                'Estado' => 'required|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
                'Correo' => 'required|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Ingreso' => 'required|date',
                'Fecha_Egreso' => 'nullable|date',
                'Curp' => 'nullable|string|max:18',
                'NSS' => 'nullable|string|max:11',
                'RFC' => 'nullable|string|max:13',
                'Codigo_Postal' => 'nullable|string|max:5',
                'Folio' => 'nullable|string|max:10',
                'Codigo' => 'nullable|string|max:20',
                'No_Cuenta' => 'nullable|string|max:10',
                'Tipo_Cuenta' => 'nullable|string|max:15',
                'Sueldo' => 'nullable|numeric',
                'IdPuestoEmpleadoFK' => 'required|exists:puestos,idPuestos',
            ]);

            $empleado = Empleado::create($request->all());

            // Limpiar cache después de crear
            \Cache::forget('empleados_ultra_fast');
            \Cache::forget('puestos_ultra_fast');

            if ($request->ajax() || $request->wantsJson()) {
                $empleados = \App\Models\Empleado::with('puesto')
                    ->whereNull('Eliminado_en')
                    ->orderBy('IdEmpleados', 'desc')
                    ->get();
                    
                $html = view('empleados.empleado', compact('empleados'))
                    ->with('success', 'Empleado creado correctamente')
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado creado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al crear empleado: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el empleado'])
                        ->withInput();
        }
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        // CACHE ULTRARRÁPIDO
        $puestos = CacheHelper::getPuestos();
        return view('empleados.edit_empleado', compact('empleado', 'puestos'));
    }

    public function update(Request $request, $id)
    {
        try {
            $empleado = Empleado::findOrFail($id);
            
            $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_Paterno' => 'required|string|max:20',
                'Apellido_Materno' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:99',
                'Telefono' => 'required|string|max:15',
                'Estado' => 'required|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
                'Correo' => 'required|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Ingreso' => 'required|date',
                'Fecha_Egreso' => 'nullable|date',
                'Curp' => 'nullable|string|max:18',
                'NSS' => 'nullable|string|max:11',
                'RFC' => 'nullable|string|max:13',
                'Codigo_Postal' => 'nullable|string|max:5',
                'Folio' => 'nullable|string|max:10',
                'Codigo' => 'nullable|string|max:20',
                'No_Cuenta' => 'nullable|string|max:10',
                'Tipo_Cuenta' => 'nullable|string|max:15',
                'Sueldo' => 'nullable|numeric',
                'IdPuestoEmpleadoFK' => 'required|exists:puestos,idPuestos',
            ]);

            $empleado->update($request->all());

            // Limpiar cache usando helper
            CacheHelper::clearAll();

            if ($request->ajax() || $request->wantsJson()) {
                // Usar CacheHelper para obtener empleados actualizados
                $empleados = CacheHelper::getEmpleados();
                    
                $html = view('empleados.empleado', compact('empleados'))
                    ->with('success', "Empleado {$empleado->Nombre} {$empleado->Apellido_Paterno} actualizado correctamente")
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado actualizado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar empleado: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al actualizar el empleado'])
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $empleado = Empleado::findOrFail($id);
            $nombreCompleto = $empleado->Nombre . ' ' . $empleado->Apellido_Paterno;
            
            // Usar transacción para asegurar consistencia
            \DB::transaction(function () use ($empleado) {
                // Actualizar empleado con fecha de egreso y eliminación PRIMERO
                $empleado->Fecha_Egreso = now();
                $empleado->Eliminado_en = now();
                $empleado->save();
                
                // Obtener un usuario válido para la FK
                $usuarioId = \App\Models\Usuario::first()->id ?? null;
                
                // Solo registrar en eliminados si tenemos un usuario válido
                if ($usuarioId) {
                    \App\Models\Eliminado::create([
                        'eliminable_type' => 'App\Models\Empleado',
                        'eliminable_id' => $empleado->IdEmpleados,
                        'tipo' => 'desactivacion',
                        'motivo' => 'Empleado desactivado desde el sistema',
                        'eliminado_en' => now(),
                        'idUsuarioEliminadoFK' => $usuarioId
                    ]);
                }
            });

            // Limpiar TODA la cache para forzar actualización
            CacheHelper::clearAll();

            if (request()->ajax() || request()->wantsJson()) {
                // Obtener empleados actualizados usando CacheHelper
                $empleados = CacheHelper::getEmpleados();
                    
                $html = view('empleados.empleado', compact('empleados'))
                    ->with('success', "Empleado {$nombreCompleto} desactivado correctamente")
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado desactivado exitosamente');
                
        } catch (\Exception $e) {
            Log::error('Error al desactivar empleado: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al desactivar el empleado: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Error al desactivar el empleado: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $empleado = Empleado::with('puesto')->findOrFail($id);
            return view('empleados.show_empleado', compact('empleado'));
        } catch (\Exception $e) {
            Log::error('Error al mostrar empleado: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al cargar el empleado']);
        }
    }
}

