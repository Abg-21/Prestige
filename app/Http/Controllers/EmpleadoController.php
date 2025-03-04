<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Candidato;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('puesto')->get();
        return view('empleados.empleado', compact('empleados'));
    }

    public function store(Request $request)
    {
        // Obtener el ID del candidato desde la solicitud
        $idCandidatos = $request->input('idCandidatos');
        $candidato = Candidato::findOrFail($idCandidatos);

        // Verificar si el candidato tiene un puesto asignado
        if (!$candidato->id_PuestoCandidatoFK) {
            return redirect()->route('candidatos.index')->with('error', 'El candidato no tiene un puesto asignado.');
        }

        // Crear empleado con los datos del candidato
        Empleado::create([
            'Nombre' => $candidato->Nombre,
            'Apellido_P' => $candidato->Apellido_P,
            'Apellido_M' => $candidato->Apellido_M,
            'Telefono_M' => $candidato->Telefono_M,
            'Telefono_F' => $candidato->Telefono_F,
            'Ciudad' => $candidato->Ciudad,
            'Estado' => $candidato->Estado,
            'Escolaridad' => $candidato->Escolaridad,
            'Correo' => $candidato->Correo,
            'Experiencia' => $candidato->Experiencia,
            'Comentarios' => $candidato->Comentarios,
            'id_PuestoEmpleadoFK' => $candidato->id_PuestoCandidatoFK,
        ]);

        // Eliminar candidato
        $candidato->delete();

        return redirect()->route('candidatos.index')->with('success', 'Candidato aprobado y transferido a empleados.');
    }

    public function show($idEmpleado)
    {
        $empleado = Empleado::with('puesto')->findOrFail($idEmpleado);
        return view('empleados.show_empleado', compact('empleado'));
    }

    public function edit($idEmpleado)
    {
        $empleado = Empleado::findOrFail($idEmpleado);
        return view('empleados.edit_empleado', compact('empleado'));
    }

    public function update(Request $request, $idEmpleado)
    {
        $empleado = Empleado::findOrFail($idEmpleado);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($idEmpleado)
    {
        $empleado = Empleado::findOrFail($idEmpleado);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }

}

