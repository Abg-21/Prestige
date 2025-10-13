<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Iniciando carga de empleados', ['is_ajax' => $request->ajax()]);
        
        try {
            $empleados = Empleado::with('puesto')->get();
            
            Log::info('Empleados obtenidos exitosamente', ['count' => $empleados->count()]);
            
            if ($request->ajax()) {
                return view('empleados.partials.index', compact('empleados'));
            }
            return view('empleados.empleado', compact('empleados'));
            
        } catch (\Exception $e) {
            Log::error('Error en index de empleados: ' . $e->getMessage());
            
            $empleados = collect([]);
            return view('empleados.empleado', compact('empleados'))
                ->with('error', 'No se pudieron cargar los empleados en este momento.');
        }
    }

    public function create()
    {
        $puestos = Puesto::all();
        return view('empleados.create_empleado', compact('puestos'));
    }

    public function store(Request $request)
    {
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
            'No_Cuenta' => 'nullable|string|max:10',
            'Tipo_Cuenta' => 'nullable|string|max:15',
            'Sueldo' => 'nullable|numeric',
            'IdPuestoEmpleadoFK' => 'required|exists:puestos,idPuestos',
        ]);

        $empleado = new Empleado($request->all());
        $empleado->save();

        if ($request->ajax()) {
            $empleados = Empleado::with('puesto')->get();
            return view('empleados.partials.index', compact('empleados'));
        }

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado creado exitosamente');
    }

    public function edit($id)
    {
        $empleado = \App\Models\Empleado::findOrFail($id);
        $puestos = Puesto::all();
        return view('empleados.edit_empleado', compact('empleado', 'puestos'));
    }

    public function update(Request $request, $id)
    {
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
            'No_Cuenta' => 'nullable|string|max:10',
            'Tipo_Cuenta' => 'nullable|string|max:15',
            'Sueldo' => 'nullable|numeric',
            'IdPuestoEmpleadoFK' => 'required|exists:puestos,idPuestos',
        ]);

        $empleado->update($request->all());

        if ($request->ajax()) {
            $empleados = Empleado::with('puesto')->get();
            return view('empleados.partials.index', compact('empleados'));
        }

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente');
    }

    public function destroy(Empleado $empleado)
    {
        // Set fecha_egreso before soft delete
        $empleado->fecha_egreso = now();
        $empleado->save();
        
        // Mark as eliminated
        $empleado->darDeBaja();

        if (request()->ajax()) {
            $empleados = Empleado::with('puesto')->get();
            return view('empleados.partials.index', compact('empleados'));
        }

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado dado de baja exitosamente');
    }

    public function show(Empleado $empleado)
    {
        return view('empleados.show_empleado', compact('empleado'));
    }
}

