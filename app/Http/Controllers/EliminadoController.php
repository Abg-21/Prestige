<?php

namespace App\Http\Controllers;

use App\Models\Eliminado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EliminadoController extends Controller
{
    public function index()
    {
        $eliminados = Eliminado::with(['eliminable', 'usuario'])->get();
        return view('Eliminados.Eliminados', compact('eliminados'));
    }

    public function show($tipo, $id)
    {
        $eliminado = Eliminado::where('tipo', $tipo)
            ->where('eliminable_id', $id)
            ->with(['eliminable', 'usuario'])
            ->firstOrFail();

        return view('Eliminados.Ver_Eliminados', compact('eliminado'));
    }

    public function restaurar(Request $request, $tipo, $id)
    {
        try {
            $eliminado = Eliminado::where('tipo', $tipo)
                ->where('eliminable_id', $id)
                ->firstOrFail();

            // Restaurar el registro eliminado
            $eliminado->eliminable->restaurar();
            
            // Actualizar campos de restauración
            $eliminado->update([
                'restaurado_en' => now(),
                'restaurado_por' => Auth::user()->nombre_usuario
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registro restaurado exitosamente'
                ]);
            }

            return redirect()->route('eliminados.index')
                ->with('success', 'Registro restaurado exitosamente');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al restaurar el registro: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al restaurar el registro: ' . $e->getMessage());
        }
    }
}
