<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSSProtection
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        return $response;
    }
}
