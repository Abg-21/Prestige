<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Candidato;
use App\Helpers\CacheHelper;

class CandidatoController extends Controller
{
    /**
     * Aprobar candidato y crear empleado
     */
    public function aprobar($id)
    {
        try {
            $candidato = \App\Models\Candidato::findOrFail($id);
            
            // Crear empleado con los datos del candidato
            $empleado = \App\Models\Empleado::create([
                'Nombre' => $candidato->Nombre,
                'Apellido_Paterno' => $candidato->Apellido_Paterno,
                'Apellido_Materno' => $candidato->Apellido_Materno,
                'Edad' => $candidato->Edad,
                'Telefono' => $candidato->Telefono,
                'Estado' => $candidato->Estado,
                'Ruta' => $candidato->Ruta,
                'Escolaridad' => $candidato->Escolaridad,
                'Correo' => $candidato->Correo,
                'Experiencia' => $candidato->Experiencia,
                'Fecha_Ingreso' => now(),
                'IdPuestoEmpleadoFK' => $candidato->IdPuestoCandidatoFK,
                // Los campos extra se dejan nulos para llenar después
                'Curp' => null,
                'NSS' => null,
                'RFC' => null,
                'Codigo_Postal' => null,
                'Folio' => null,
                'No_Cuenta' => null,
                'Tipo_Cuenta' => null,
                'Sueldo' => null,
                'Fecha_Egreso' => null,
                'Eliminado_en' => null
            ]);

            // Eliminar candidato completamente de la base de datos
            $candidato->delete(); // Eliminación física en lugar de soft delete
            
            // Limpiar cache completo para que aparezca en empleados
            \Cache::forget('candidatos_ultra_fast');
            \Cache::forget('empleados_ultra_fast'); // IMPORTANTE: limpiar cache de empleados
            \Cache::forget('puestos_ultra_fast');

            // Respuesta AJAX
            if (request()->ajax() || request()->wantsJson()) {
                $candidatos = \App\Models\Candidato::with('puesto')
                    ->whereNull('Eliminado_en')
                    ->orderBy('IdCandidatos', 'desc')
                    ->get();
                    
                $html = view('candidatos.candidatos', compact('candidatos'))
                    ->with('success', "Candidato {$candidato->Nombre} aprobado y convertido en empleado")
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('candidatos.index')
                ->with('success', "Candidato {$candidato->Nombre} aprobado y convertido en empleado correctamente");
                
        } catch (\Exception $e) {
            \Log::error('Error al aprobar candidato: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al aprobar candidato: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Error al aprobar el candidato: ' . $e->getMessage()]);
        }
    }

    // ...existing code...
    public function index()
    {
        try {
            $candidatos = CacheHelper::getCandidatos();
            return view('candidatos.candidatos', compact('candidatos'));
            
        } catch (\Exception $e) {
            \Log::error('Error en index de candidatos: ' . $e->getMessage());
            $candidatos = collect([]);
            return view('candidatos.candidatos', compact('candidatos'));
        }
    }
    
    public function create()
    {
        // CACHE ULTRARRÁPIDO
        $puestos = CacheHelper::getPuestos();
        return view('candidatos.create_candidatos', compact('puestos'));
    }
    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_Paterno' => 'required|string|max:20',
                'Apellido_Materno' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:100',
                'Telefono' => 'required|string|max:15',
                'Estado' => 'required|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|string',
                'Correo' => 'required|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Postulacion' => 'required|date',
                'IdPuestoCandidatoFK' => 'nullable|exists:puestos,idPuestos'
            ]);

            $candidato = Candidato::create($validatedData);

            // Limpiar cache después de crear
            \Cache::forget('candidatos_ultra_fast');
            \Cache::forget('puestos_ultra_fast');

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                // Obtener candidatos frescos sin caché para asegurar que aparezca el nuevo
                $candidatos = \App\Models\Candidato::with('puesto')
                    ->whereNull('Eliminado_en')
                    ->orderBy('IdCandidatos', 'desc')
                    ->get();
                    
                $html = view('candidatos.candidatos', compact('candidatos'))->with('success', 'Candidato creado correctamente')->render();
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('candidatos.index')
                ->with('success', 'Candidato creado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al crear candidato: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el candidato'])
                        ->withInput();
        }
    }
    
    public function show($id)
    {
        $candidato = Candidato::with('puesto')->findOrFail($id);
        return view('candidatos.show_candidato', compact('candidato'));
    }
    
    public function edit($id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            $puestos = CacheHelper::getPuestos();
            return view('candidatos.edit_candidatos', compact('candidato', 'puestos'));
        } catch (\Exception $e) {
            Log::error('Error al cargar candidato para editar: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al cargar el candidato']);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_Paterno' => 'required|string|max:20',
                'Apellido_Materno' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:100',
                'Telefono' => 'required|string|max:15',
                'Estado' => 'required|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|string',
                'Correo' => 'required|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Postulacion' => 'required|date',
                'IdPuestoCandidatoFK' => 'nullable|exists:puestos,idPuestos'
            ]);

            $candidato->update($validatedData);
            
            // Limpiar cache
            \Cache::forget('candidatos_ultra_fast');
            \Cache::forget('puestos_ultra_fast');

            // Respuesta AJAX
            if ($request->ajax() || $request->wantsJson()) {
                // Obtener candidatos frescos para mostrar lista actualizada
                $candidatos = \App\Models\Candidato::with('puesto')
                    ->whereNull('Eliminado_en')
                    ->orderBy('IdCandidatos', 'desc')
                    ->get();
                    
                $html = view('candidatos.candidatos', compact('candidatos'))
                    ->with('success', "Candidato {$candidato->Nombre} actualizado correctamente")
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('candidatos.index')
                ->with('success', 'Candidato actualizado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar candidato: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar candidato: ' . $e->getMessage(),
                    'errors' => $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Error al actualizar el candidato'])
                        ->withInput();
        }
    }
    
    public function destroy($id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            $candidato->darDeBaja('Eliminado desde el sistema');

            return redirect()->route('candidatos.index')
                ->with('success', 'Candidato eliminado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar candidato: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al eliminar el candidato']);
        }
    }

    public function reject($id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            
            // Crear registro en tabla eliminados
            \App\Models\Eliminado::create([
                'eliminable_type' => Candidato::class,
                'eliminable_id' => $candidato->IdCandidatos,
                'tipo' => 'candidato',
                'motivo' => 'Candidato rechazado por el usuario',
                'eliminado_en' => now(),
                'idUsuarioEliminadoFK' => auth()->id() ?? 1
            ]);

            // Marcar candidato como eliminado para quitarlo de la vista principal
            $candidato->update(['Eliminado_en' => now()]);
            
            // Limpiar cache
            \Cache::forget('candidatos_ultra_fast');
            \Cache::forget('puestos_ultra_fast');

            // Respuesta AJAX
            if (request()->ajax() || request()->wantsJson()) {
                $candidatos = \App\Models\Candidato::with('puesto')
                    ->whereNull('Eliminado_en')
                    ->orderBy('IdCandidatos', 'desc')
                    ->get();
                    
                $html = view('candidatos.candidatos', compact('candidatos'))
                    ->with('success', "Candidato {$candidato->Nombre} rechazado correctamente")
                    ->render();
                    
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('candidatos.index')
                ->with('success', "Candidato {$candidato->Nombre} rechazado correctamente");
                
        } catch (\Exception $e) {
            Log::error('Error al rechazar candidato: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al rechazar candidato: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Error al rechazar el candidato: ' . $e->getMessage()]);
        }
    }
}