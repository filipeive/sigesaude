<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretariaController extends Controller
{
    public function index()
    {
        // Obter a role do usuário logado
        $role = Auth::user()->tipo;

        // Definir o menu com base na role
        $menu = $this->getMenuForRole($role);

        return view('secretaria.dashboard', compact('menu'));
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Profile',
                'url' => 'secretaria/perfil',
                'icon' => 'fas fa-fw fa-user',
                'can' => 'auth',
            ],
            [
                'text' => 'Logout',
                'url' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'can' => 'auth',
            ],
            // Itens específicos para secretaria
            [
                'text' => 'Dashboard',
                'url' => 'secretaria/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            [
                'header' => 'Gestão de Estudantes',
            ],
            [
                'text' => 'Cadastrar Estudante',
                'url' => 'secretaria/estudantes/create',
                'icon' => 'fas fa-fw fa-user-plus',
            ],
            [
                'text' => 'Listar Estudantes',
                'url' => 'secretaria/estudantes',
                'icon' => 'fas fa-fw fa-users',
            ],
            [
                'header' => 'Gestão de Matrículas',
            ],
            [
                'text' => 'Cadastrar Matrícula',
                'url' => 'secretaria/matriculas/create',
                'icon' => 'fas fa-fw fa-graduation-cap',
            ],
            [
                'text' => 'Listar Matrículas',
                'url' => 'secretaria/matriculas',
                'icon' => 'fas fa-fw fa-list-ul',
            ],
            [
                'header' => 'Financeiro',
            ],
            [
                'text' => 'Gerenciar Pagamentos',
                'url' => 'secretaria/pagamentos',
                'icon' => 'fas fa-fw fa-money-bill',
            ],
            [
                'header' => 'Relatórios',
            ],
            [
                'text' => 'Gerar Relatórios',
                'url' => 'secretaria/relatorios',
                'icon' => 'fas fa-fw fa-file-alt',
            ],
            [
                'header' => 'Configurações',
            ],
            [
                'text' => 'Configurar Sistema',
                'url' => 'secretaria/configuracoes',
                'icon' => 'fas fa-fw fa-cog',
            ],
        ];

        return $menu;
    }
}