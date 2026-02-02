<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $loggedIn = session('loggedIn', false);

        if (!$loggedIn) {
            return response('Access denied. You are not logged in.');
        }
        
        return $next($request);
    }
}
