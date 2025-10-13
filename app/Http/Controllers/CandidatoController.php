<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Candidato;

class CandidatoController extends Controller
{
    /**
     * Aprobar candidato y crear empleado
     */
    public function aprobar($id)
    {
        $candidato = \App\Models\Candidato::findOrFail($id);

        // Crear empleado con los datos del candidato
        $empleado = new \App\Models\Empleado();
        $empleado->Nombre = $candidato->Nombre;
        $empleado->Apellido_Paterno = $candidato->Apellido_Paterno;
        $empleado->Apellido_Materno = $candidato->Apellido_Materno;
        $empleado->Edad = $candidato->Edad;
        $empleado->Telefono = $candidato->Telefono;
        $empleado->Estado = $candidato->Estado;
        $empleado->Ruta = $candidato->Ruta;
        $empleado->Escolaridad = $candidato->Escolaridad;
        $empleado->Correo = $candidato->Correo;
        $empleado->Experiencia = $candidato->Experiencia;
        $empleado->Fecha_Ingreso = now();
        $empleado->IdPuestoEmpleadoFK = $candidato->IdPuestoCandidatoFK;
        // Los campos extra se dejan nulos para editar después
        $empleado->Curp = null;
        $empleado->NSS = null;
        $empleado->RFC = null;
        $empleado->Codigo_Postal = null;
        $empleado->Folio = null;
        $empleado->No_Cuenta = null;
        $empleado->Tipo_Cuenta = null;
        $empleado->Sueldo = null;
        $empleado->Fecha_Egreso = null;
        $empleado->Eliminado_en = null;
        $empleado->save();

        // Marcar candidato como aprobado/eliminado
        $candidato->Eliminado_en = now();
        $candidato->save();

        return redirect()->route('empleados.edit', $empleado->IdEmpleados)
            ->with('success', 'Candidato aprobado y convertido en empleado. Ahora puedes editar los datos extra.');
    }

    // ...existing code...
    public function index()
    {
        Log::info('Iniciando carga de candidatos', ['is_ajax' => request()->ajax()]);
        
        try {
            // Usar el modelo real
            $candidatos = Candidato::activos()->with('puesto')->get();
            
            Log::info('Candidatos obtenidos exitosamente', ['count' => $candidatos->count()]);
            
            return view('candidatos.candidatos', compact('candidatos'));
            
        } catch (\Exception $e) {
            Log::error('Error en index de candidatos: ' . $e->getMessage());
            
            // Devolver vista con datos vacíos en caso de error
            $candidatos = collect([]);
            return view('candidatos.candidatos', compact('candidatos'))
                ->with('error', 'No se pudieron cargar los candidatos en este momento.');
        }
    }
    
    public function create()
    {
        return view('candidatos.create_candidatos');
    }
    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_P' => 'required|string|max:20',
                'Apellido_M' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:100',
                'Telefono_M' => 'nullable|string|max:15',
                'Estado' => 'nullable|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|string',
                'Correo' => 'nullable|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Postulacion' => 'nullable|date',
                'id_PuestoCandidatoFK' => 'nullable|exists:puestos,idPuestos'
            ]);

            Candidato::create($validatedData);

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
        $candidato = Candidato::findOrFail($id);
        return view('candidatos.edit_candidatos', compact('candidato'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:25',
                'Apellido_P' => 'required|string|max:20',
                'Apellido_M' => 'required|string|max:20',
                'Edad' => 'required|integer|min:18|max:100',
                'Telefono_M' => 'nullable|string|max:15',
                'Estado' => 'nullable|string',
                'Ruta' => 'nullable|string|max:30',
                'Escolaridad' => 'required|string',
                'Correo' => 'nullable|email|max:30',
                'Experiencia' => 'required|string|max:10',
                'Fecha_Postulacion' => 'nullable|date',
                'id_PuestoCandidatoFK' => 'nullable|exists:puestos,idPuestos'
            ]);

            $candidato->update($validatedData);

            return redirect()->route('candidatos.index')
                ->with('success', 'Candidato actualizado correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar candidato: ' . $e->getMessage());
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
            $candidato->darDeBaja('Rechazado');

            return response()->json([
                'success' => true,
                'message' => 'Candidato rechazado correctamente'
            ]);
                
        } catch (\Exception $e) {
            Log::error('Error al rechazar candidato: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar el candidato'
            ], 500);
        }
    }
}