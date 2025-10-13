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

    public function create()
    {
        return view('clientes.create_cliente');
    }

    public function show($id)
    {
        $cliente = Cliente::with('puestos')->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    public function store(Request $request)
    {
        // Debug: ver los datos que llegan
        \Log::info('Datos del formulario clientes:', $request->all());
        
        // Verificar si ya existe un cliente con ese nombre
        $existingCliente = Cliente::where('Nombre', $request->Nombre)->first();
        \Log::info('Cliente existente encontrado', ['exists' => !!$existingCliente, 'data' => $existingCliente ? $existingCliente->toArray() : null]);

        $messages = [
            'Nombre.required' => 'El nombre del cliente es obligatorio',
            'Nombre.unique' => 'El nombre ya está ocupado por otro cliente',
            'Nombre.max' => 'El nombre no puede tener más de :max caracteres',
            'Telefono.unique' => 'El número de teléfono ya está registrado por otro cliente',
            'Telefono.max' => 'El teléfono no puede tener más de :max caracteres',
            'Descripcion.max' => 'La descripción no puede tener más de :max caracteres'
        ];

        try {
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:30|unique:clientes,Nombre',
                'Telefono' => 'nullable|max:15|unique:clientes,Telefono',
                'Descripcion' => 'nullable|max:45'
            ], $messages);

            $cliente = Cliente::create($validatedData);

            if ($request->ajax()) {
                // Para peticiones AJAX, devolver la vista completa de clientes
                $clientes = Cliente::with('puestos')->get();
                return view('clientes.cliente', compact('clientes'))->with('success', 'Cliente creado correctamente');
            }

            return redirect()->route('clientes.index')
                ->with('success', 'Cliente creado correctamente.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación en clientes:', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit(Cliente $cliente)
    {
        $puestos = Puesto::all();
        return view('clientes.edit_cliente', compact('cliente', 'puestos'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $messages = [
            'Nombre.required' => 'El nombre del cliente es obligatorio',
            'Nombre.unique' => 'El nombre ya está ocupado por otro cliente',
            'Nombre.max' => 'El nombre no puede tener más de :max caracteres',
            'Telefono.unique' => 'El número de teléfono ya está registrado por otro cliente',
            'Telefono.max' => 'El teléfono no puede tener más de :max caracteres',
            'Descripcion.max' => 'La descripción no puede tener más de :max caracteres'
        ];

        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:30|unique:clientes,Nombre,'.$cliente->idClientes.',idClientes',
            'Telefono' => 'nullable|max:15|unique:clientes,Telefono,'.$cliente->idClientes.',idClientes',
            'Descripcion' => 'nullable|max:45'
        ], $messages);

        $cliente->update($validatedData);

        if ($request->ajax()) {
            // Para peticiones AJAX, devolver la vista completa de clientes
            $clientes = Cliente::with('puestos')->get();
            return view('clientes.cliente', compact('clientes'))->with('success', 'Cliente actualizado correctamente');
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id, Request $request)
    {
        $cliente = Cliente::findOrFail($id);

        try {
            if ($cliente->puestos()->exists() && !$request->has('force')) {
                return response()->json([
                    'confirm' => true,
                    'message' => 'El cliente está relacionado a uno o más puestos, ¿Aún así deseas eliminarlo? Los registros de Puestos a los que se relacionen se eliminarán automáticamente al aceptar.'
                ], 409);
            }

            \DB::beginTransaction();
            
            if ($cliente->puestos()->exists()) {
                $cliente->puestos()->delete();
            }
            
            $cliente->delete();
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $cliente->puestos()->exists() ? 
                    'Cliente y sus puestos relacionados eliminados correctamente.' : 
                    'Cliente eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error al eliminar el cliente.'
            ], 500);
        }
    }

    public function lista()
    {
        $clientes = Cliente::all();
        return view('clientes.select_options', compact('clientes'));
    }
}
