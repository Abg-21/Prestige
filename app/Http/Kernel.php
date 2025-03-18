<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\SessionTimeoutMiddleware;

class Kernel extends HttpKernel
{
    // ... código existente ...
    
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ... middleware existentes ...
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'session.timeout' => SessionTimeoutMiddleware::class, // Nuestro nuevo middleware
    ];
    
    /**
     * The application's middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // ... middleware existentes ...
            \Illuminate\Session\Middleware\StartSession::class,
            // ... otros middleware ...
            // Agregar nuestro middleware de timeout a todas las rutas web
            \App\Http\Middleware\SessionTimeoutMiddleware::class,
        ],
        
        'api' => [
            // ... middleware existentes para API ...
        ],
    ];
    
    // ... resto del código ...
}