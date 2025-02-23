<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function index()
    {
        // Obter a role do usuário logado
        $role = Auth::user()->tipo;

        // Definir o menu com base na role
        $menu = $this->getMenuForRole($role);

        return view('docente.dashboard', compact('menu'));
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Profile',
                'url' => 'docente/perfil',
                'icon' => 'fas fa-fw fa-user',
                'can' => 'auth',
            ],
            [
                'text' => 'Logout',
                'url' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'can' => 'auth',
            ],
            // Itens específicos para docentes
            [
                'text' => 'Dashboard',
                'url' => 'docente/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            [
                'header' => 'Minhas Disciplinas',
            ],
            [
                'text' => 'Ver Disciplinas',
                'url' => 'docente/disciplinas',
                'icon' => 'fas fa-fw fa-book',
            ],
            [
                'header' => 'Alunos',
            ],
            [
                'text' => 'Listar Alunos',
                'url' => 'docente/alunos',
                'icon' => 'fas fa-fw fa-users',
            ],
        ];

        return $menu;
    }
}