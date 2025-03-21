<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\SessionTimeoutMiddleware;

class Kernel extends HttpKernel
{
    
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'session.timeout' => \App\Http\Middleware\SessionTimeout::class, // ✅ Nombre correcto
    ];
    
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ];
    
    
    /**
     * The application's middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
    \Illuminate\Session\Middleware\StartSession::class,
    'session.timeout',
        ],
                'api' => [
        ],
    ];
    
}