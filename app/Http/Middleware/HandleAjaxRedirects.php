<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleAjaxRedirects
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Si es una solicitud AJAX y la respuesta es una redirección
        if ($request->ajax() && $response->isRedirection()) {
            // Extraer la URL de redirección
            $redirectUrl = $response->headers->get('Location');
            
            // Crear una nueva respuesta que incluya la URL de redirección como encabezado
            return response()->json(['redirect' => true], 200)
                ->header('X-Redirect-To', $redirectUrl);
        }

        return $response;
    }
}