<?php

namespace App\Http\Controllers;

use App\Models\Giro;
use Illuminate\Http\Request;

class GiroController extends Controller
{
    public function index()
    {
        $giros = Giro::paginate(10);
        return view('giros.giro', compact('giros'));
    }

    public function create()
    {
        return view('giros.create_giro');
    }

    public function show($id)
    {
        $giro = Giro::with('puestos')->findOrFail($id);
        return view('giros.show', compact('giro'));
    }

    public function store(Request $request)
    {
        \Log::info('=== GIRO STORE INICIADO ===');
        \Log::info('Datos del formulario giros:', $request->all());
        \Log::info('Es AJAX?', [
            'ajax()' => $request->ajax(),
            'wantsJson()' => $request->wantsJson(), 
            'X-Requested-With' => $request->header('X-Requested-With'),
            'headers' => $request->headers->all()
        ]);
        
        try {
            $messages = [
                'Nombre.required' => 'El nombre del giro es obligatorio',
                'Nombre.unique' => 'El nombre ya está ocupado por otro giro',
                'Nombre.max' => 'El nombre no puede tener más de :max caracteres',
                'Descripcion.max' => 'La descripción no puede tener más de :max caracteres'
            ];

            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:30|unique:giros,Nombre',
                'Descripcion' => 'nullable|string|max:45'
            ], $messages);

            \Log::info('Validación exitosa', ['validated_data' => $validatedData]);

            $giro = Giro::create($validatedData);

            \Log::info('Giro creado exitosamente', ['giro' => $giro]);

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                \Log::info('=== RESPUESTA AJAX GIRO ===');
                // Para peticiones AJAX, devolver la vista completa de giros
                $giros = Giro::paginate(10);
                \Log::info('Giros obtenidos:', ['count' => $giros->count()]);
                
                $html = view('giros.giro', compact('giros'))->with('success', 'Giro creado correctamente')->render();
                \Log::info('HTML giro generado:', ['length' => strlen($html), 'preview' => substr($html, 0, 200)]);
                
                return response($html)->header('Content-Type', 'text/html');
            }

            return redirect()->route('giros.index')
                ->with('success', 'Giro creado correctamente');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Error de validación', ['errors' => $e->errors()]);
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                \Log::info('Error de validación en AJAX', []);
                // Para AJAX, devolver la vista de crear giro con errores
                return view('giros.create_giro')->withErrors($e->errors())->withInput($request->all());
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Error inesperado en store', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                \Log::info('Error inesperado en AJAX', []);
                $giros = Giro::paginate(10);
                return view('giros.giro', compact('giros'))->with('error', 'Error interno del servidor');
            }
            
            return redirect()->back()->with('error', 'Error interno del servidor');
        }
    }


    public function edit($id)
    {
        $giro = Giro::findOrFail($id);
        return view('giros.edit_giro', compact('giro'));
    }

    public function update(Request $request, $id)
    {
        $giro = Giro::findOrFail($id);
        
        $messages = [
            'Nombre.required' => 'El nombre del giro es obligatorio',
            'Nombre.unique' => 'El nombre ya está ocupado por otro giro',
            'Nombre.max' => 'El nombre no puede tener más de :max caracteres',
            'Descripcion.max' => 'La descripción no puede tener más de :max caracteres'
        ];

        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:30|unique:giros,Nombre,'.$giro->idGiros.',idGiros',
            'Descripcion' => 'nullable|string|max:45'
        ], $messages);

        $giro->update($validatedData);

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            \Log::info('=== UPDATE AJAX GIRO ===');
            // Para peticiones AJAX, devolver la vista completa de giros
            $giros = Giro::paginate(10);
            \Log::info('Giros obtenidos para update:', ['count' => $giros->count()]);
            
            $html = view('giros.giro', compact('giros'))->with('success', 'Giro actualizado correctamente')->render();
            \Log::info('HTML update giro generado:', ['length' => strlen($html), 'preview' => substr($html, 0, 200)]);
            
            return response($html)->header('Content-Type', 'text/html');
        }

        return redirect()->route('giros.index')
            ->with('success', 'Giro actualizado correctamente');
    }

    public function destroy($id, Request $request)
    {
        \Log::info('Iniciando eliminación de giro', ['giro_id' => $id, 'force' => $request->has('force')]);
        
        $giro = Giro::findOrFail($id);

        if ($giro->puestos()->exists() && !$request->has('force')) {
            \Log::info('Giro tiene puestos relacionados, solicitando confirmación');
            return response()->json([
                'confirm' => true,
                'message' => 'El giro está relacionado a uno o más puestos, ¿Aún así deseas eliminarlo? Los registros de Puestos a los que se relacionen se eliminarán automáticamente al aceptar.'
            ], 409);
        }

        try {
            // Iniciar una transacción para asegurar que todo se elimine correctamente
            \DB::beginTransaction();
            
            // Eliminar primero los puestos relacionados
            if ($giro->puestos()->exists()) {
                \Log::info('Eliminando puestos relacionados');
                $giro->puestos()->delete();
            }
            
            // Luego eliminar el giro
            \Log::info('Eliminando giro');
            $giro->delete();

            \DB::commit();
            
            \Log::info('Giro eliminado exitosamente');

            return response()->json([
                'success' => true,
                'message' => 'Giro eliminado correctamente.'
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al eliminar giro', ['error' => $e->getMessage(), 'giro_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el giro y sus puestos.'
            ], 500);
        }
    }

    public function lista()
    {
        $giros = Giro::all();
        return view('giros.select_options', compact('giros'));
    }
}
