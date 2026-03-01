<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $loggedIn = session('loggedIn', false); // check for $loggedIn, if none exist, default value is false

        if(!$loggedIn) {
            return redirect()->route('home.page');
        }

        return $next($request);
    }
}
