<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo optimizar respuestas HTML
        if ($response instanceof \Illuminate\Http\Response && 
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            
            $content = $response->getContent();
            
            // Minificar HTML eliminando espacios innecesarios
            $content = preg_replace('/>\s+</', '><', $content);
            $content = preg_replace('/\s+/', ' ', $content);
            $content = trim($content);
            
            $response->setContent($content);
            
            // Headers de cache para navegador
            $response->headers->set('Cache-Control', 'public, max-age=300');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 300) . ' GMT');
        }

        return $response;
    }
}