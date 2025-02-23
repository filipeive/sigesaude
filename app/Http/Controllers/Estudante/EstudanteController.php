<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudanteController extends Controller
{
    public function index()
    {
        // Obter a role do usuário logado
        $role = Auth::user()->tipo;

        // Definir o menu com base na role
        $menu = $this->getMenuForRole($role);

        return view('estudante.dashboard', compact('menu'));
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Profile',
                'url' => 'estudante/perfil',
                'icon' => 'fas fa-fw fa-user',
                'can' => 'auth',
            ],
            [
                'text' => 'Logout',
                'url' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'can' => 'auth',
            ],
            // Itens específicos para estudantes
            [
                'text' => 'Dashboard',
                'url' => 'estudante/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            [
                'header' => 'Minhas Matrículas',
            ],
            [
                'text' => 'Ver Matrículas',
                'url' => 'estudante/matriculas',
                'icon' => 'fas fa-fw fa-graduation-cap',
            ],
            [
                'header' => 'Meus Pagamentos',
            ],
            [
                'text' => 'Ver Pagamentos',
                'url' => 'estudante/pagamentos',
                'icon' => 'fas fa-fw fa-money-bill',
            ],
            [
                'header' => 'Relatórios',
            ],
            [
                'text' => 'Gerar Relatórios',
                'url' => 'estudante/relatorios',
                'icon' => 'fas fa-fw fa-file-alt',
            ],
            [
                'header' => 'Notificações',
            ],
            [
                'text' => 'Ver Notificações',
                'url' => 'estudante/notificacoes',
                'icon' => 'fas fa-fw fa-bell',
            ],
        ];

        return $menu;
    }
}