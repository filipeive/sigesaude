<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $tipo)
    {
        // Verifica se o usuário está autenticado
        if (!$request->user()) {
            return redirect()->route('login'); // Redireciona para a página de login
        }

        // Verifica se o usuário tem o tipo necessário
        if ($request->user()->tipo !== $tipo) {
            abort(403, 'Acesso não autorizado.'); // Retorna um erro 403
        }

        return $next($request);
    }
}