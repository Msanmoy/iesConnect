<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Redirige si el usuario no está autenticado.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}
