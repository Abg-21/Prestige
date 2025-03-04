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
