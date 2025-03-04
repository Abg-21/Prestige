<?php

namespace App\Http\Controllers;

use App\Models\Giro;
use Illuminate\Http\Request;

class GiroController extends Controller
{
    public function index()
    {
        $giros = Giro::paginate(10); // Paginación
        return view('giros.giro', compact('giros'));
    }

    public function create(Request $request)
    {
        // Capturar los datos del formulario que se enviaron
        $formData = $request->except('_token');
        return view('giros.create_giro', compact('formData'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:30',
            'Descripcion' => 'nullable|string|max:45',
        ]);

        $giro = Giro::create($validatedData);

        
        return redirect()->route('giros.index')->with('message', 'Giro Creado correctamente.');
    }


    public function edit(Giro $giro)
    {
        return view('giros.edit_giro', compact('giro'));
    }

    public function update(Request $request, Giro $giro)
    {
        $request->validate([
            'Nombre' => 'required|max:30|unique:giros,Nombre,' . $giro->idGiros . ',idGiros',
            'Descripcion' => 'nullable|max:255',
        ]);

        $giro->update($request->only('Nombre', 'Descripcion'));
        return redirect()->route('giros.index')->with('message', 'Giro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $giro = Giro::findOrFail($id);

        if ($giro->puestos()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar el giro porque está relacionado con uno o más puestos.'
            ], 400);
        }

        $giro->delete();

        return response()->json([
            'success' => 'Giro eliminado correctamente.'
        ], 200);
    }

}
