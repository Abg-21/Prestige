<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FastResponseMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // NO deshabilitar logging para login/auth para debugging
        if (!$request->is('login*')) {
            config(['logging.default' => 'null']);
        }
        
        $response = $next($request);
        
        // Solo cachear páginas que no son de autenticación
        if ($response->isSuccessful() && !$request->is('login*') && !$request->is('logout*')) {
            $response->headers->set('Cache-Control', 'public, max-age=1800');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 1800));
        }
        
        // Para login, asegurar que no se cachee
        if ($request->is('login*')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
        
        return $response;
    }
}