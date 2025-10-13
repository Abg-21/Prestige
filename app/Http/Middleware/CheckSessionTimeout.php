<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        $auth = new AuthController();
        $response = $auth->checkSessionTimeout($request);
        
        if ($response) {
            return $response;
        }

        return $next($request);
    }
}