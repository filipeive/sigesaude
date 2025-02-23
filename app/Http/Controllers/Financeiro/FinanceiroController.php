<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceiroController extends Controller
{
    public function index()
    {
        // Obter a role do usuário logado
        $role = Auth::user()->tipo;

        // Definir o menu com base na role
        $menu = $this->getMenuForRole($role);

        return view('financeiro.dashboard', compact('menu'));
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Profile',
                'url' => 'financeiro/perfil',
                'icon' => 'fas fa-fw fa-user',
                'can' => 'auth',
            ],
            [
                'text' => 'Logout',
                'url' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'can' => 'auth',
            ],
            // Itens específicos para financeiro
            [
                'text' => 'Dashboard',
                'url' => 'financeiro/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            [
                'header' => 'Gestão de Pagamentos',
            ],
            [
                'text' => 'Listar Pagamentos',
                'url' => 'financeiro/pagamentos',
                'icon' => 'fas fa-fw fa-money-bill',
            ],
            [
                'header' => 'Relatórios Financeiros',
            ],
            [
                'text' => 'Gerar Relatórios',
                'url' => 'financeiro/relatorios',
                'icon' => 'fas fa-fw fa-chart-bar',
            ],
        ];

        return $menu;
    }
}
