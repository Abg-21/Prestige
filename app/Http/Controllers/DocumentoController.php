<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('documentos')->get();
        return view('documentos.documentoE', compact('empleados'));
    }

    public function create()
    {
        $empleados = Empleado::all();
        return view('documentos.create', compact('empleados'));
    }

    public function show($id)
    {
        $documento = Documento::with('empleado')->findOrFail($id);
        return view('documentos.show', compact('documento'));
    }

    public function edit($id)
    {
        $documento = Documento::findOrFail($id);
        $empleados = Empleado::all();
        return view('documentos.edit', compact('documento', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);
        
        $request->validate([
            'idEmpleadoFK' => 'required|exists:empleados,idEmpleado',
            'Archivo' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('Archivo')) {
            // Eliminar archivo anterior
            Storage::delete($documento->RutaArchivo);
            
            // Guardar nuevo archivo
            $ruta = $request->file('Archivo')->store('documentos');
            $documento->RutaArchivo = $ruta;
        }

        $documento->idEmpleadoFK = $request->idEmpleadoFK;
        $documento->save();

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'idEmpleadoFK' => 'required|exists:empleados,idEmpleado',
            'Archivo.*' => 'required|file|mimes:pdf|max:2048', // Cada archivo debe ser PDF y máx 2 MB
        ]);

        foreach ($request->file('Archivo') as $archivo) {
            $ruta = $archivo->store('documentos');

            Documento::create([
                'idEmpleadoFK' => $request->idEmpleadoFK,
                'TipoArchivo' => 'PDF',
                'RutaArchivo' => $ruta,
                'FechaSubida' => now(),
            ]);
        }

        return redirect()->route('documentos.index')->with('success', 'Documentos subidos correctamente.');
    }

    public function download(Documento $documento)
    {
        return Storage::download($documento->RutaArchivo);
    }

    public function view(Documento $documento)
    {
        return response()->file(storage_path('app/' . $documento->RutaArchivo));
    }

    public function destroy(Documento $documento)
    {
        Storage::delete($documento->RutaArchivo);
        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}
