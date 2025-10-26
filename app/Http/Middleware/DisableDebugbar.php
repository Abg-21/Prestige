<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableDebugbar
{
    public function handle(Request $request, Closure $next): Response
    {
        // Deshabilitar completamente Debugbar para máximo rendimiento
        if (app()->bound('debugbar')) {
            app('debugbar')->disable();
        }

        $response = $next($request);

        // Headers para cache del navegador más agresivo
        if ($request->isMethod('GET')) {
            $response->headers->set('Cache-Control', 'public, max-age=1800'); // 30 minutos
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 1800) . ' GMT');
            
            // Compresión
            if (!$response->headers->has('Content-Encoding')) {
                $response->headers->set('Content-Encoding', 'gzip');
            }
        }

        return $response;
    }
}