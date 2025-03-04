<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Puesto;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('puestos')->get();
        return view('clientes.cliente', compact('clientes'));
    }

    public function create(Request $request)
    {
        $puestos = Puesto::all(); // Supone que ya tienes el modelo Puesto
        $formData = $request->except('_token');
        return view('clientes.create_cliente', compact('puestos', 'formData'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:30',
            'Telefono' => 'nullable|string|max:15',
            'Descripcion' => 'nullable|string|max:45',
        ]);

        $cliente = Cliente::create($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }


    public function edit(Cliente $cliente)
    {
        $puestos = Puesto::all();
        return view('clientes.edit_cliente', compact('cliente', 'puestos'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'Nombre' => 'required|max:30',
            'Telefono' => 'nullable|max:15',
            'Descripcion' => 'nullable|max:45',
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->puestos()->exists()) {
            return response()->json([
                'error' => 'No se puede eliminar el cliente porque está relacionado con uno o más puestos.'
            ], 400);
        }

        $cliente->delete();

        return response()->json([
            'success' => 'Cliente eliminado correctamente.'
        ], 200);
    }


}
