<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        // Mostrar solo las no leídas
        $notificaciones = Notificacion::where('leido', false)->get();
        return response()->json($notificaciones);
    }

    public function marcarComoLeido($id)
    {
        $notificacion = Notificacion::find($id);
        $notificacion->update(['leido' => true]);

        return response()->json(['message' => 'Notificación marcada como leída']);
    }

    public function crearNotificacion($titulo, $descripcion, $tipo)
    {
        Notificacion::create([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'tipo' => $tipo
        ]);
    }
    
}

