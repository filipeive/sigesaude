<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          // Obter a role do usuário logado
          $role = Auth::user()->tipo;

          // Definir o menu com base na role
          $menu = $this->getMenuForRole($role);

          // Exibir a página do painel financeiro
        return view('admin.financeiro.index', compact('menu')); // View do painel financeiro
    }
    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Profile',
                'url' => 'perfil',
                'icon' => 'fas fa-fw fa-user',
                'can' => 'auth', // Apenas usuários autenticados podem ver isso
            ],
        ];

        // Adicionar itens de menu específicos para cada role
        switch ($role) {
            case 'admin':
                $menu = array_merge($menu, [
                    [
                        'text' => 'Dashboard',
                        'url' => 'dashboard',
                        'icon' => 'fas fa-fw fa-tachometer-alt',
                    ],
                    [
                        'header' => 'Gestão de Usuários',
                    ],
                    [
                        'text' => 'Estudantes',
                        'url' => 'aestudantes',
                        'icon' => 'fas fa-fw fa-users',
                    ],
                    [
                        'text' => 'Docentes',
                        'url' => 'docentes',
                        'icon' => 'fas fa-fw fa-chalkboard-teacher',
                    ],
                    [
                        'header' => 'Gestão Acadêmica',
                    ],
                    [
                        'text' => 'Cursos',
                        'url' => 'cursos',
                        'icon' => 'fas fa-fw fa-university',
                    ],
                    [
                        'text' => 'Disciplinas',
                        'url' => 'disciplinas',
                        'icon' => 'fas fa-fw fa-book',
                    ],
                    [
                        'text' => 'Matrículas',
                        'url' => 'matriculas',
                        'icon' => 'fas fa-fw fa-graduation-cap',
                    ],
                    [
                        'header' => 'Financeiro',
                    ],
                    [
                        'text' => 'Pagamentos',
                        'url' => 'pagamentos',
                        'icon' => 'fas fa-fw fa-money-bill',
                    ],
                    [
                        'text' => 'Financeiro',
                        'url' => 'financeiro',
                        'icon' => 'fas fa-fw fa-chart-line',
                    ],
                    [
                        'header' => 'Configurações',
                    ],
                    [
                        'text' => 'Atualizar Perfil',
                        'url' => 'perfil/update',
                        'icon' => 'fas fa-fw fa-cog',
                    ],
                ]);
                break;

            case 'estudante':
                $menu = array_merge($menu, [
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
                ]);
                break;

            // Adicione casos para 'docente', 'secretaria', 'financeiro', etc.
            default:
                // Menu padrão (se necessário)
                break;
        }

        return $menu;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}