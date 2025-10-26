<?php

namespace App\Http\Controllers;

use App\Models\Eliminado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PermissionHelper;

class EliminadoController extends Controller
{
    public function __construct()
    {
        // Verificar permisos
        $this->middleware(function ($request, $next) {
            if (!PermissionHelper::hasPermission('eliminados')) {
                abort(403, 'No tiene permisos para acceder a este módulo');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Eliminado::with(['eliminable', 'usuario'])
                          ->whereNull('restaurado_en'); // Solo mostrar los que no han sido restaurados
        
        // Filtrar por tipo si se especifica
        if ($request->has('tipo') && $request->tipo != '') {
            $tipo = $request->tipo;
            $query->where(function($q) use ($tipo) {
                $q->where('tipo', $tipo)
                  ->orWhere('eliminable_type', 'LIKE', "%\\{$tipo}");
            });
        }

        // Filtrar por búsqueda si se especifica
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('motivo', 'LIKE', "%{$buscar}%")
                  ->orWhereHas('usuario', function($subQ) use ($buscar) {
                      $subQ->where('nombre_usuario', 'LIKE', "%{$buscar}%")
                           ->orWhere('nombre', 'LIKE', "%{$buscar}%");
                  });
            });
        }

        $eliminados = $query->orderBy('eliminado_en', 'desc')->get();
        
        // Si es una petición AJAX, devolver solo la tabla
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Eliminados.tabla', compact('eliminados'))->render()
            ]);
        }

        return view('Eliminados.Eliminados', compact('eliminados'));
    }

    public function show($tipo, $id)
    {
        $eliminado = Eliminado::where(function($query) use ($tipo) {
                $query->where('tipo', $tipo)
                      ->orWhere('eliminable_type', 'LIKE', "%\\{$tipo}");
            })
            ->where('eliminable_id', $id)
            ->with(['eliminable', 'usuario'])
            ->firstOrFail();

        return view('Eliminados.Ver_Eliminados', compact('eliminado'));
    }

    public function restaurar(Request $request, $tipo, $id)
    {
        try {
            $eliminado = Eliminado::where(function($query) use ($tipo) {
                    $query->where('tipo', $tipo)
                          ->orWhere('eliminable_type', 'LIKE', "%\\{$tipo}");
                })
                ->where('eliminable_id', $id)
                ->whereNull('restaurado_en')
                ->firstOrFail();

            // Verificar que el registro eliminable existe
            if (!$eliminado->eliminable) {
                throw new \Exception('El registro original no existe o fue eliminado permanentemente');
            }

            // Restaurar el registro eliminado
            $eliminado->eliminable->restaurar();

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
