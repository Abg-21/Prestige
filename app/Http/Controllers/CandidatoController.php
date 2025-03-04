<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Candidato;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    public function index()
    {
        // Obtener todos los candidatos con la información del puesto relacionado
        $candidatos = Candidato::with('puesto:idPuestos,Puesto')->get();
        return view('candidatos.candidatos', compact('candidatos'));
    }

    public function create()
    {
        // Obtener todos los puestos para mostrarlos en el formulario
        $puestos = Puesto::all();
        return view('candidatos.create_candidatos', compact('puestos'));
    }

    public function store(Request $request)
    {
        // Validar los datos enviados desde el formulario
        $request->validate([
            'Nombre' => 'required|string|max:50',
            'Apellido_P' => 'required|string|max:30',
            'Apellido_M' => 'required|string|max:30',
            'id_PuestoCandidatoFK' => 'required|exists:puestos,idPuestos', // Validar clave foránea
            'Telefono_M' => 'nullable|string|max:15',
            'Telefono_F' => 'nullable|string|max:15',
            'Ciudad' => 'nullable|string|max:30',
            'Estado' => 'nullable|string|max:30',
            'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
            'Correo' => 'nullable|email|max:30',
            'Experiencia' => 'required|integer|min:0',
            'Comentarios' => 'nullable|string|max:50',
        ]);

        // Crear un nuevo candidato con los datos enviados
        $candidato = new Candidato($request->only([
            'Nombre', 'Apellido_P', 'Apellido_M', 'Telefono_M', 'Telefono_F',
            'Ciudad', 'Estado', 'Escolaridad', 'Correo', 'Experiencia', 'Comentarios'
        ]));
        $candidato->id_PuestoCandidatoFK = $request->id_PuestoCandidatoFK;
        $candidato->save();

        return redirect()->route('candidatos.index')->with('success', 'Candidato creado exitosamente.');
    }

    public function edit(Candidato $candidato)
    {
        // Obtener todos los puestos para mostrarlos en el formulario de edición
        $puestos = Puesto::all();
        return view('candidatos.edit_candidatos', compact('candidato', 'puestos'));
    }

    public function update(Request $request, Candidato $candidato)
    {
        // Validar los datos enviados desde el formulario
        $request->validate([
            'Nombre' => 'required|string|max:50',
            'Apellido_P' => 'required|string|max:30',
            'Apellido_M' => 'required|string|max:30',
            'id_PuestoCandidatoFK' => 'required|exists:puestos,idPuestos', // Validar clave foránea
            'Telefono_M' => 'nullable|string|max:15',
            'Telefono_F' => 'nullable|string|max:15',
            'Ciudad' => 'nullable|string|max:30',
            'Estado' => 'nullable|string|max:30',
            'Escolaridad' => 'required|in:Primaria,Secundaria terminada,Bachillerato trunco,Bachillerato terminado,Técnico superior,Licenciatura trunca,Licenciatura terminada,Postgrado',
            'Correo' => 'nullable|email|max:30',
            'Experiencia' => 'required|integer|min:0',
            'Comentarios' => 'nullable|string|max:50',
        ]);

        // Actualizar el candidato con los datos enviados
        $candidato->fill($request->only([
            'Nombre', 'Apellido_P', 'Apellido_M', 'Telefono_M', 'Telefono_F',
            'Ciudad', 'Estado', 'Escolaridad', 'Correo', 'Experiencia', 'Comentarios'
        ]));
        $candidato->id_PuestoCandidatoFK = $request->id_PuestoCandidatoFK;
        $candidato->save();

        return redirect()->route('candidatos.index')->with('success', 'Candidato actualizado exitosamente.');
    }

    public function destroy(Candidato $candidato)
    {
        // Eliminar el candidato
        $candidato->delete();
        return redirect()->route('candidatos.index')->with('success', 'Candidato eliminado exitosamente.');
    }
}
