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
        $puestos = Puesto::with(['giro', 'cliente'])->get();

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

        // Si es acceso normal, carga la vista completa
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
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->ip()
        ]);

        $messages = [
            'Categoría.required' => 'La categoría es obligatoria',
            'Puesto.required' => 'El nombre del puesto es obligatorio',
            'Puesto.max' => 'El nombre del puesto no puede tener más de :max caracteres',
            'id_GiroPuestoFK.required' => 'El giro es obligatorio',
            'id_ClientePuestoFK.required' => 'El cliente es obligatorio',
            'Zona.required' => 'La zona es obligatoria',
            'Estado.required' => 'El estado es obligatorio',
            'Edad.required' => 'Debe seleccionar al menos un rango de edad',
            'Escolaridad.required' => 'La escolaridad es obligatoria',
            'Experiencia.required' => 'La experiencia es obligatoria'
        ];

        $validatedData = $request->validate([
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required',
            'Edad' => 'required|array',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required',
            'Experiencia' => 'required|max:40',
            'Conocimientos' => 'nullable|array|max:8',
            'Funciones' => 'nullable|array|max:8',
            'Habilidades' => 'nullable|array|max:8'
        ], $messages);

        // Convertir arrays a JSON antes de guardar
        $validatedData['Edad'] = implode(', ', $request->Edad);
        $validatedData['Conocimientos'] = json_encode($request->Conocimientos ?? []);
        $validatedData['Funciones'] = json_encode($request->Funciones ?? []);
        $validatedData['Habilidades'] = json_encode($request->Habilidades ?? []);

        $puesto = Puesto::create($validatedData);

        \Log::info('Puesto creado exitosamente', [
            'puesto_id' => $puesto->idPuestos,
            'puesto_data' => $puesto->toArray()
        ]);

        if ($request->ajax()) {
            \Log::info('Retornando respuesta AJAX exitosa');
            return response()->json([
                'success' => true,
                'message' => 'Se guardaron los datos correctamente'
            ]);
        }

        return redirect()->route('puestos.index')
            ->with('success', 'Puesto creado correctamente');
    }


    public function edit(Puesto $puesto)
    {
        $giros = Giro::all();
        $clientes = Cliente::all();

        // Verifica si el campo es JSON, si no, conviértelo a array
        foreach (['Conocimientos', 'Funciones', 'Habilidades'] as $campo) {
            $valor = $puesto->$campo;
            if (is_null($valor) || $valor === '') {
                $puesto->$campo = [];
            } elseif (is_array($valor)) {
                // Ya es array
            } elseif (is_string($valor) && (substr($valor, 0, 1) === '[' || substr($valor, 0, 1) === '{')) {
                $puesto->$campo = json_decode($valor, true) ?? [];
            } else {
                // Separado por comas
                $puesto->$campo = array_map('trim', explode(',', $valor));
            }
        }

        return view('puestos.edit_puesto', compact('puesto', 'giros', 'clientes'));
    }



    public function update(Request $request, Puesto $puesto)
    {
        $validatedData = $request->validate([
            'Categoría' => 'required|in:Promovendedor,Promotor,Supervisor,Otro',
            'Puesto' => 'required|max:45',
            'id_GiroPuestoFK' => 'required|exists:giros,idGiros',
            'id_ClientePuestoFK' => 'required|exists:clientes,idClientes',
            'Zona' => 'required|max:30',
            'Estado' => 'required',
            'Edad' => 'required|array',
            'Edad.*' => 'in:18-23,24-30,31-35,36-42,43-51,52-60',
            'Escolaridad' => 'required',
            'Experiencia' => 'required|max:40',
            'Conocimientos' => 'nullable|array|max:8',
            'Funciones' => 'nullable|array|max:8',
            'Habilidades' => 'nullable|array|max:8'
        ]);

        // Convertir arrays a formato adecuado
        $validatedData['Edad'] = $request->Edad ? implode(', ', $request->Edad) : null;
        $validatedData['Conocimientos'] = json_encode($request->Conocimientos ?? []);
        $validatedData['Funciones'] = json_encode($request->Funciones ?? []);
        $validatedData['Habilidades'] = json_encode($request->Habilidades ?? []);

        $puesto->update($validatedData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Se guardaron los datos correctamente'
            ]);
        }

        return redirect()->route('puestos.index')
            ->with('success', 'Puesto actualizado correctamente');
    }



    public function lista()
    {
        $puestos = Puesto::all();
        return view('puestos.select_options', compact('puestos'));
    }

    public function destroy(Request $request, Puesto $puesto)
    {
        $relaciones = [];

        if ($puesto->empleados()->count() > 0) {
            $relaciones[] = 'empleados';
        }
        if (method_exists($puesto, 'candidatos') && $puesto->candidatos()->count() > 0) {
            $relaciones[] = 'candidatos';
        }

        // Si hay relaciones y no se forzó el borrado, pregunta primero
        if (count($relaciones) > 0 && !$request->input('force')) {
            return response()->json([
                'confirm' => true,
                'message' => 'El puesto está relacionado con: ' . implode(', ', $relaciones) . '. ¿Aún así deseas eliminar el puesto?'
            ], 409);
        }

        try {
            $puesto->delete();
            return response()->json(['success' => true, 'message' => 'Eliminado exitosamente']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Si falla el borrado forzado, muestra el mensaje claro
            return response()->json([
                'error' => 'No se puede eliminar el puesto porque está relacionado con: ' . implode(', ', $relaciones) . '.'
            ], 409);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el puesto.'], 500);
        }
    }

    public function show(Puesto $puesto)
    {
        if (request()->ajax()) {
            return view('puestos.show_puesto', compact('puesto'));
        }
        return view('puestos.show_puesto', compact('puesto'));
    }
}