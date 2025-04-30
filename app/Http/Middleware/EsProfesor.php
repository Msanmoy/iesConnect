<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class EsProfesor
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->es_profesor) {
            return $next($request);
        } else{
            abort(403, 'Acceso no autorizado');
        }
    }
}
