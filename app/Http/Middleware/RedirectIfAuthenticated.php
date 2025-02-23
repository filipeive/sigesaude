<?php
namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirecionar com base na role do usuário
                switch ($user->tipo) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'estudante':
                        return redirect()->route('estudante.dashboard');
                    case 'docente':
                        return redirect()->route('docente.dashboard');
                    case 'secretaria':
                        return redirect()->route('secretaria.dashboard');
                    case 'financeiro':
                        return redirect()->route('financeiro.dashboard');
                    default:
                        return redirect('/login'); // Redireciona para login se a role for desconhecida
                }
            }
        }

        return $next($request);
    }
}