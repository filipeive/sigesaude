<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // Importe esta classe aqui

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Sobrescreve o método redirectPath() para redirecionar com base na role do usuário.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (auth()->check()) {
            $user = auth()->user();
            // Redireciona com base na role do usuário
            switch ($user->tipo) {
                case 'admin':
                    return route('admin.dashboard');
                case 'estudante':
                    return route('estudante.dashboard');
                case 'docente':
                    return route('docente.dashboard');
                case 'secretaria':
                    return route('secretaria.dashboard');
                case 'financeiro':
                    return route('financeiro.dashboard');
                default:
                    return '/login'; // Redireciona para login se a role for desconhecida
            }
        }
        return '/login'; // Redireciona para login se o usuário não estiver autenticado
    }

    /**
     * Define o campo de nome de usuário usado para autenticação.
     *
     * @return string
     */
    public function username()
    {
        return 'email'; // Ou qualquer outro campo que você usa para login
    }

    /**
     * Define o comportamento ao falhar no login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ])->redirectTo('/login'); // Certifique-se de que o redirecionamento vá para /login
    }
}