<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Agregar aquí rutas que no requieren CSRF si es necesario
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Regenerar token CSRF en cada login para evitar problemas
        if ($request->is('login') && $request->isMethod('GET')) {
            $request->session()->regenerateToken();
        }
        
        return parent::handle($request, $next);
    }
}