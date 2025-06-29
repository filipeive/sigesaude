<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Docente;

class CheckDocenteProfile
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar se existe perfil de docente
        if (!Docente::where('user_id', auth()->id())->exists()) {
            return redirect()->route('docente.profile.create')
                ->with('warning', 'Por favor, complete seu perfil de docente primeiro.');
        }

        return $next($request);
    }
}