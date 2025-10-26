<?php
namespace App\Http\Controllers;

use App\Models\Giro;
use App\Models\Cliente;
use App\Models\Puesto;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('=== INDEX PUESTOS ===');
        
        // Limpiar cache para asegurar datos frescos
        \Cache::forget('puestos_lista');
        
        $puestos = Puesto::with(['giro', 'cliente'])
                         ->whereNotNull('idPuestos')
                         ->orderBy('idPuestos', 'asc')  // Cambiado de 'desc' a 'asc' para mostrar más viejos arriba
                         ->get();
        
        \Log::info('Puestos en index:', [
            'total' => $puestos->count(),
            'ids' => $puestos->pluck('idPuestos')->toArray()
        ]);

        if ($request->ajax()) {
            // Solo devuelve la tabla para AJAX
            return response()->view('puestos.puesto', compact('puestos', 'request'));
        }

        // Vista completa para carga normal
        return view('puestos.puesto', compact('puestos'));
    }


    public function create(Request $request)
    {
        $giros = Giro::all();
        $clientes = Cliente::all();

        // Si es petición AJAX ESPECÍFICAMENTE para modal, devolver formulario compacto
        if ($request->has('modal') || $request->input('modal') === 'true') {
            \Log::info('Cargando formulario modal de puesto');
            return view('puestos.form_puesto_ajax', compact('giros', 'clientes'));
        }

        // Para navegación normal (incluso si es AJAX), carga la vista completa
        return view('puestos.create_puesto', [
            'giros' => $giros,
            'clientes' => $clientes,
            'desdePuesto' => true
        ]);
    }
    public function store(Request $request)
    {
        \Log::info('Store method de puesto iniciado', [
            'timestamp' => now(),
            'request_data' => $request->all(),
            'is_ajax' => $request->ajax(),
            'from_modal' => $request->has('from_modal'),
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->ip()
        ]);

        // Determinar si viene del modal para ajustar validaciones
        $fromModal = $request->has('from_modal') || str_contains($request->header('referer', ''), 'candidatos');

        $messages = [
            'Categoría.required' => 'La categoría es obligatoria',
            'Puesto.required' => 'El nombre del puesto es obligatorio',
            'Puesto.max' => 'El nombre del puesto no puede tener más de :max caracteres',
            'id_GiroPuestoFK.required' => 'El giro es obligatorio',
            'id_ClientePuestoFK.required' => 'El cliente es obligatorio',
            'Zona.required' => 'La ruta es obligatoria',
            'Estado.required' => 'El estado es obligatorio',
            'Edad.required' => 'Debe seleccionar al menos un rango de edad',
            'Edad.min' => 'Debe seleccionar al menos un rango de edad',
            'Escolaridad.required' => 'La escolaridad es obligatoria',
            'Experiencia.required' => 'La experiencia es obligatoria',
            'Conocimientos.required' => 'Debe agregar al menos un conocimiento',
            'Conocimientos.min' => 'Debe agregar al menos un conocimiento',
            'Funciones.required' => 'Debe agregar al menos una función',
            'Funciones.min' => 'Debe agregar al menos una función',
            'Habilidades.required' => 'Debe agregar al menos una habilidad',
            'Habilidades.min' => 'Debe agregar al menos una habilidad'
        ];

        // Validaciones base
        $validationRules = [
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required',
            'Edad' => 'required|array|min:1',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required',
            'Experiencia' => 'required|max:40'
        ];

        // Si NO viene del modal (viene de create_puesto), los campos dinámicos son obligatorios
        if (!$fromModal) {
            $validationRules['Conocimientos'] = 'required|array|min:1|max:8';
            $validationRules['Conocimientos.*'] = 'required|string|max:100';
            $validationRules['Funciones'] = 'required|array|min:1|max:8';
            $validationRules['Funciones.*'] = 'required|string|max:100';
            $validationRules['Habilidades'] = 'required|array|min:1|max:8';
            $validationRules['Habilidades.*'] = 'required|string|max:100';
        } else {
            // Para el modal, son opcionales
            $validationRules['Conocimientos'] = 'nullable|array|max:8';
            $validationRules['Funciones'] = 'nullable|array|max:8';
            $validationRules['Habilidades'] = 'nullable|array|max:8';
        }

        $validatedData = $request->validate($validationRules, $messages);

        // Convertir arrays a JSON antes de guardar
        $validatedData['Edad'] = implode(', ', $request->Edad);
        $validatedData['Conocimientos'] = json_encode($request->Conocimientos ?? []);
        $validatedData['Funciones'] = json_encode($request->Funciones ?? []);
        $validatedData['Habilidades'] = json_encode($request->Habilidades ?? []);

        $puesto = Puesto::create($validatedData);

        \Log::info('Puesto creado exitosamente', [
            'puesto_id' => $puesto->idPuestos,
            'puesto_data' => $puesto->toArray(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'x_requested_with' => $request->header('X-Requested-With'),
            'from_modal' => $request->has('from_modal')
        ]);

        // SIMPLIFICAR: Si NO es modal, SIEMPRE responder con HTML
        if (!$request->has('from_modal')) {
            \Log::info('Puesto creado desde create_puesto, devolviendo HTML de lista actualizada');
            $puestos = Puesto::with(['giro', 'cliente'])
                             ->whereNotNull('idPuestos')
                             ->orderBy('idPuestos', 'asc')  // Mostrar más viejos arriba, nuevos abajo
                             ->get();
            $html = view('puestos.puesto', compact('puestos'))->with('success', 'Puesto creado correctamente')->render();
            
            // FORZAR respuesta HTML sin importar si es AJAX o no
            return response($html, 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }
        
        // Si llegamos aquí, es del modal - devolver JSON
        \Log::info('Puesto creado desde modal, devolviendo JSON');
        return response()->json([
            'success' => true,
            'id' => $puesto->idPuestos,
            'nombre' => $puesto->Puesto,
            'message' => 'Puesto creado correctamente'
        ]);
    }


    public function edit($id)
    {
        \Log::info('=== EDIT PUESTO INICIADO ===', ['puesto_id' => $id]);
        
        $puesto = Puesto::findOrFail($id);
        $giros = Giro::all();
        $clientes = Cliente::all();

        \Log::info('Puesto encontrado:', ['puesto' => $puesto->toArray()]);

        // Verifica si el campo es JSON, si no, conviértelo a array
        foreach (['Conocimientos', 'Funciones', 'Habilidades'] as $campo) {
            $valor = $puesto->$campo;
            \Log::info("Procesando campo $campo:", ['valor_original' => $valor, 'tipo' => gettype($valor)]);
            
            if (is_null($valor) || $valor === '' || $valor === 'null') {
                $puesto->$campo = [];
            } elseif (is_array($valor)) {
                // Ya es array
            } elseif (is_string($valor) && (substr($valor, 0, 1) === '[' || substr($valor, 0, 1) === '{')) {
                $decoded = json_decode($valor, true);
                $puesto->$campo = $decoded ?? [];
            } else {
                // Separado por comas o texto plano
                $puesto->$campo = $valor ? array_map('trim', explode(',', $valor)) : [];
            }
            
            \Log::info("Campo $campo procesado:", ['valor_final' => $puesto->$campo]);
        }

        return view('puestos.edit_puesto', compact('puesto', 'giros', 'clientes'));
    }



    public function update(Request $request, $id)
    {
        \Log::info('=== UPDATE PUESTO INICIADO ===', ['puesto_id' => $id, 'datos' => $request->all()]);
        
        $puesto = Puesto::findOrFail($id);

        $messages = [
            'Categoría.required' => 'La categoría es obligatoria',
            'Puesto.required' => 'El nombre del puesto es obligatorio',
            'Puesto.max' => 'El nombre del puesto no puede tener más de :max caracteres',
            'id_GiroPuestoFK.required' => 'El giro es obligatorio',
            'id_ClientePuestoFK.required' => 'El cliente es obligatorio',
            'Zona.required' => 'La ruta es obligatoria',
            'Estado.required' => 'El estado es obligatorio',
            'Edad.required' => 'Debe seleccionar al menos un rango de edad',
            'Edad.min' => 'Debe seleccionar al menos un rango de edad',
            'Escolaridad.required' => 'La escolaridad es obligatoria',
            'Experiencia.required' => 'La experiencia es obligatoria',
            'Conocimientos.required' => 'Debe agregar al menos un conocimiento',
            'Conocimientos.min' => 'Debe agregar al menos un conocimiento',
            'Funciones.required' => 'Debe agregar al menos una función',
            'Funciones.min' => 'Debe agregar al menos una función',
            'Habilidades.required' => 'Debe agregar al menos una habilidad',
            'Habilidades.min' => 'Debe agregar al menos una habilidad'
        ];

        $validatedData = $request->validate([
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required',
            'Edad' => 'required|array|min:1',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required',
            'Experiencia' => 'required|max:40',
            'Conocimientos' => 'required|array|min:1|max:8',
            'Conocimientos.*' => 'required|string|max:100',
            'Funciones' => 'required|array|min:1|max:8',
            'Funciones.*' => 'required|string|max:100',
            'Habilidades' => 'required|array|min:1|max:8',
            'Habilidades.*' => 'required|string|max:100'
        ], $messages);

        // Convertir arrays a formato adecuado
        $validatedData['Edad'] = $request->Edad ? implode(', ', $request->Edad) : null;
        $validatedData['Conocimientos'] = json_encode($request->Conocimientos ?? []);
        $validatedData['Funciones'] = json_encode($request->Funciones ?? []);
        $validatedData['Habilidades'] = json_encode($request->Habilidades ?? []);

        \Log::info('Datos validados para update:', $validatedData);

        $puesto->update($validatedData);

        \Log::info('Puesto actualizado exitosamente');

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            \Log::info('=== RESPUESTA AJAX PUESTO ===');
            $puestos = Puesto::with(['giro', 'cliente'])->get();
            $html = view('puestos.puesto', compact('puestos'))->with('success', 'Puesto actualizado correctamente')->render();
            return response($html)->header('Content-Type', 'text/html');
        }

        return redirect()->route('puestos.index')
            ->with('success', 'Puesto actualizado correctamente');
    }



    public function lista()
    {
        \Log::info('=== PETICIÓN LISTA DE PUESTOS ===', [
            'timestamp' => now(),
            'user_agent' => request()->header('User-Agent'),
            'is_ajax' => request()->ajax()
        ]);
        
        // Limpiar cache
        \Cache::forget('puestos_lista');
        
        // Obtener puestos frescos de la BD
        $puestos = Puesto::select('idPuestos', 'Puesto')
                         ->whereNotNull('idPuestos')
                         ->orderBy('idPuestos', 'desc')
                         ->get();
        
        \Log::info('Puestos obtenidos de BD:', [
            'total' => $puestos->count(),
            'puestos' => $puestos->map(function($p) {
                return ['id' => $p->idPuestos, 'nombre' => $p->Puesto];
            })->toArray()
        ]);
        
        // Renderizar vista
        $html = view('puestos.lista', compact('puestos'))->render();
        
        \Log::info('Vista renderizada:', [
            'html_length' => strlen($html),
            'html_preview' => substr($html, 0, 200)
        ]);
        
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    public function getOptionsForSelect()
    {
        \Log::info('=== PETICIÓN OPCIONES SELECT PUESTOS ===');
        
        try {
            // Verificar autenticación
            if (!auth()->check()) {
                \Log::warning('Usuario no autenticado para opciones de puestos');
                return response('<option value="">Error: No autenticado</option>', 401);
            }
            
            // Obtener puestos básicos
            $puestos = Puesto::select('idPuestos', 'Puesto')
                             ->whereNotNull('idPuestos')
                             ->orderBy('Puesto', 'asc')
                             ->get();
            
            \Log::info('Opciones de puestos obtenidas:', [
                'total' => $puestos->count(),
                'user' => auth()->user()->email ?? 'unknown'
            ]);
            
            // Generar HTML directamente
            $html = '<option value="">Seleccione un puesto</option>';
            foreach ($puestos as $puesto) {
                $html .= '<option value="' . $puesto->idPuestos . '">' . htmlspecialchars($puesto->Puesto) . '</option>';
            }
            
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Cache-Control' => 'no-cache'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en getOptionsForSelect: ' . $e->getMessage());
            return response('<option value="">Error al cargar puestos</option>', 500);
        }
    }

    public function destroy(Request $request, Puesto $puesto)
    {
        \Log::info('=== ELIMINAR PUESTO ===', [
            'puesto_id' => $puesto->idPuestos,
            'puesto_nombre' => $puesto->Puesto,
            'force' => $request->input('force', false)
        ]);

        $relaciones = [];

        // Verificar relaciones
        $empleados_count = $puesto->empleados()->count();
        if ($empleados_count > 0) {
            $relaciones[] = "empleados ($empleados_count)";
        }
        
        // Verificar candidatos si existe la relación
        if (method_exists($puesto, 'candidatos')) {
            $candidatos_count = $puesto->candidatos()->count();
            if ($candidatos_count > 0) {
                $relaciones[] = "candidatos ($candidatos_count)";
            }
        }

        \Log::info('Relaciones encontradas:', $relaciones);

        // Si hay relaciones y no se forzó el borrado, pregunta primero
        if (count($relaciones) > 0 && !$request->input('force')) {
            \Log::info('Solicitando confirmación por relaciones');
            return response()->json([
                'confirm' => true,
                'message' => 'El puesto está relacionado con: ' . implode(', ', $relaciones) . '. ¿Aún así deseas eliminar el puesto?'
            ], 409);
        }

        try {
            $puesto_id = $puesto->idPuestos;
            $puesto_nombre = $puesto->Puesto;
            
            // Eliminar el puesto
            $deleted = $puesto->delete();
            
            \Log::info('Puesto eliminado:', [
                'deleted' => $deleted,
                'puesto_id' => $puesto_id,
                'puesto_nombre' => $puesto_nombre
            ]);

            // Limpiar cache después de eliminar
            \Cache::forget('puestos_lista');
            
            // Verificar que realmente se eliminó
            $exists = Puesto::where('idPuestos', $puesto_id)->exists();
            \Log::info('Verificación de eliminación:', [
                'puesto_id' => $puesto_id,
                'still_exists' => $exists
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Puesto eliminado exitosamente',
                'puesto_id' => $puesto_id
            ]);
            
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Error QueryException al eliminar puesto:', [
                'error' => $e->getMessage(),
                'puesto_id' => $puesto->idPuestos
            ]);
            
            return response()->json([
                'error' => 'No se puede eliminar el puesto porque está relacionado con: ' . implode(', ', $relaciones) . '.'
            ], 409);
        } catch (\Exception $e) {
            \Log::error('Error general al eliminar puesto:', [
                'error' => $e->getMessage(),
                'puesto_id' => $puesto->idPuestos
            ]);
            
            return response()->json(['error' => 'Error al eliminar el puesto: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        \Log::info('=== SHOW PUESTO INICIADO ===', ['puesto_id' => $id]);
        
        $puesto = Puesto::with(['giro', 'cliente'])->findOrFail($id);
        
        \Log::info('Puesto encontrado para mostrar:', ['puesto' => $puesto->toArray()]);
        
        if (request()->ajax()) {
            return view('puestos.show_puesto', compact('puesto'));
        }
        return view('puestos.show_puesto', compact('puesto'));
    }
}