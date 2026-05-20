<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Disciplina;
use App\Models\Docente;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();
        $anoAtivoId = $anoAtivo?->id;

        $totalEstudantes = Estudante::where('status', 'Ativo')
            ->when($anoAtivoId, fn ($q) => $q->where('ano_lectivo_id', $anoAtivoId))
            ->count();
        $totalDocentes = Docente::where('status', 'Ativo')->count();
        $totalTurmas = Turma::count();
        $totalClasses = Classe::count();
        $totalDisciplinas = Disciplina::count();
        $totalMatriculas = Matricula::count();
        $totalPagamentos = Pagamento::when($anoAtivoId, fn ($q) => $q->where('ano_lectivo_id', $anoAtivoId))
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('valor');

        $matriculasPendentes = Matricula::where('status', 'Pendente')->count();

        // Estudantes por Turma (chart)
        $estudantesPorTurma = Estudante::select('turmas.nome', DB::raw('count(*) as total'))
            ->join('turmas', 'estudantes.turma_id', '=', 'turmas.id')
            ->groupBy('turmas.id', 'turmas.nome')
            ->get();
        $turmasLabels = $estudantesPorTurma->pluck('nome');
        $turmasData = $estudantesPorTurma->pluck('total');

        // Pagamentos últimos 6 meses (chart)
        $pg6m = Pagamento::select(DB::raw('sum(valor) as total'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"))
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();
        $pagamentosLabels = $pg6m->pluck('mes');
        $pagamentosData = $pg6m->pluck('total');

        // Turmas com mais estudantes (mantém relacionamentos Eloquente para a blade)
        $turmasMaisPovoadas = Turma::with(['classe', 'anoLectivo'])
            ->withCount('estudantes')
            ->orderByDesc('estudantes_count')
            ->limit(5)
            ->get();

        // Atividades recentes
        $recentStudents = Estudante::with(['user', 'turma.classe'])->latest()->limit(3)->get()->map(function ($e) {
            $tur = $e->turma ? ($e->turma->classe->nome ?? '').' '.$e->turma->nome : 'N/A';

            return (object) ['tipo' => 'Novo Estudante', 'descricao' => "{$e->user->name} → {$tur}", 'created_at' => $e->created_at];
        });

        $recentPayments = Pagamento::latest()->limit(3)->get()->map(function ($p) {
            return (object) ['tipo' => 'Pagamento', 'descricao' => 'Recebido '.number_format($p->valor, 2, ',', '.').' MZN', 'created_at' => $p->created_at];
        });

        $atividadesRecentes = $recentStudents->concat($recentPayments)->sortByDesc('created_at')->take(5);

        // ── MENU DO SIDEBAR ────────────────────────────────────────────────
        $menu = [
            ['header' => 'PRINCIPAL'],
            ['text' => 'Dashboard',  'url' => route('admin.dashboard'), 'icon' => 'fas fa-tachometer-alt'],

            ['header' => 'ACADÉMICO'],
            ['text' => 'Turmas',    'url' => route('admin.turmas.index'),       'icon' => 'fas fa-chalkboard'],
            ['text' => 'Estudantes', 'url' => route('admin.estudantes.index'),    'icon' => 'fas fa-user-graduate'],
            ['text' => 'Docentes',  'url' => route('admin.docentes.index'),      'icon' => 'fas fa-chalkboard-teacher'],
            ['text' => 'Disciplinas', 'url' => route('admin.disciplinas.index'),   'icon' => 'fas fa-book'],
            ['text' => 'Classes',   'url' => route('admin.classes.index'),       'icon' => 'fas fa-layer-group'],
            ['text' => 'Notas',     'url' => route('admin.notas.index'),         'icon' => 'fas fa-chart-line'],
            ['text' => 'Progresso Acadêmico', 'url' => route('admin.progresso_academico.index'), 'icon' => 'fas fa-chart-bar'],

            ['header' => 'ADMINISTRATIVO'],
            ['text' => 'Matrículas', 'url' => route('admin.matriculas.index'), 'icon' => 'fas fa-file-signature'],

            ['header' => 'ANOS LECTIVOS'],
            ['text' => 'Anos Lectivos', 'url' => route('admin.anos-lectivos.index'), 'icon' => 'fas fa-calendar-alt'],

            ['header' => 'FINANCEIRO'],
            ['text' => 'Pagamentos', 'url' => route('admin.pagamentos.index'), 'icon' => 'fas fa-money-bill-wave'],
            ['text' => 'Financeiro', 'url' => route('admin.financeiro.index'),  'icon' => 'fas fa-chart-pie'],

            ['header' => 'SISTEMA'],
            ['text' => 'Usuários',   'url' => route('admin.users.index'),  'icon' => 'fas fa-users-cog'],
            ['text' => 'Notificações', 'url' => route('admin.notificacoes.index'), 'icon' => 'fas fa-bell'],
            ['text' => 'Perfil',     'url' => route('admin.perfil'), 'icon' => 'fas fa-user-circle'],
        ];

        return view('admin.dashboard', compact(
            'totalEstudantes', 'totalDocentes', 'totalTurmas', 'totalClasses',
            'totalDisciplinas', 'totalMatriculas', 'totalPagamentos',
            'matriculasPendentes', 'turmasLabels', 'turmasData',
            'pagamentosLabels', 'pagamentosData', 'turmasMaisPovoadas',
            'atividadesRecentes', 'menu'
        ));
    }
}
