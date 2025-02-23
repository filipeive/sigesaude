<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Verifica se o usuário está autenticado
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Verifica se o usuário tem a role necessária
        if ($request->user()->role !== $role) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
