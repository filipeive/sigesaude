<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use App\Models\Turma;
use Carbon\Carbon;
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

        $stats = [
            'pendentes' => Pagamento::where('status', 'pendente')->count(),
            'pagos' => Pagamento::where('status', 'pago')->count(),
            'cancelados' => Pagamento::where('status', 'cancelado')->count(),
            'total_pago' => Pagamento::where('status', 'pago')->sum('valor'),
        ];

        $pagamentosRecentes = Pagamento::with(['estudante.user', 'turma'])
            ->latest()
            ->take(8)
            ->get();

        return view('financeiro.dashboard', compact('menu', 'stats', 'pagamentosRecentes'));
    }

    public function pagamentos(Request $request)
    {
        $pagamentos = Pagamento::with(['estudante.user', 'turma'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where('referencia', 'like', "%{$search}%")
                    ->orWhereHas('estudante.user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo', $request->tipo))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return view('financeiro.pagamentos.index', compact('pagamentos'));
    }

    public function relatorios(Request $request)
    {
        $dataInicio = $request->filled('data_inicio') ? Carbon::parse($request->data_inicio)->startOfDay() : now()->startOfMonth();
        $dataFim = $request->filled('data_fim') ? Carbon::parse($request->data_fim)->endOfDay() : now()->endOfMonth();

        $base = Pagamento::whereBetween('created_at', [$dataInicio, $dataFim]);

        $resumo = [
            'total_emitido' => (clone $base)->sum('valor'),
            'total_pago' => (clone $base)->where('status', 'pago')->sum('valor'),
            'total_pendente' => (clone $base)->where('status', 'pendente')->sum('valor'),
            'pagamentos' => (clone $base)->count(),
        ];

        $porCategoria = (clone $base)
            ->selectRaw('COALESCE(tipo, "sem_categoria") as tipo, COUNT(*) as total, SUM(valor) as valor')
            ->groupBy('tipo')
            ->orderBy('tipo')
            ->get();

        return view('financeiro.relatorios.index', compact('resumo', 'porCategoria', 'dataInicio', 'dataFim'));
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
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
