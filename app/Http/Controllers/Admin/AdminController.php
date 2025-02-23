<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudante;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Pagamento;
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
        // Estatísticas Principais
        $totalEstudantes = Estudante::where('status', 'Ativo')->count();
        $totalDocentes = Docente::where('status', 'Ativo')->count();
        $totalCursos = Curso::count();
        $totalPagamentos = Pagamento::whereMonth('created_at', Carbon::now()->month)
                                   ->sum('valor');

        // Dados para o gráfico de distribuição de estudantes
        $estudantesPorCurso = Estudante::select('cursos.nome', DB::raw('count(*) as total'))
            ->join('cursos', 'estudantes.curso_id', '=', 'cursos.id')
            ->groupBy('cursos.id', 'cursos.nome')
            ->get();

        $cursosLabels = $estudantesPorCurso->pluck('nome');
        $cursosData = $estudantesPorCurso->pluck('total');

        // Dados para o gráfico de pagamentos
        $pagamentosUltimos6Meses = Pagamento::select(
            DB::raw('sum(valor) as total'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes")
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')")) // Certifique-se de agrupar corretamente
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();        

        $pagamentosLabels = $pagamentosUltimos6Meses->pluck('mes');
        $pagamentosData = $pagamentosUltimos6Meses->pluck('total');

        // Cursos mais procurados
        $cursosMaisProcurados = Curso::withCount('estudantes')
            ->orderByDesc('estudantes_count')
            ->limit(5)
            ->get();

        // Atividades recentes (exemplo - ajuste conforme sua necessidade)
        $atividadesRecentes = collect([
            (object)[
                'tipo' => 'Nova Matrícula',
                'descricao' => 'Novo estudante matriculado em Medicina',
                'created_at' => Carbon::now()->subHours(2)
            ],
            // Adicione mais atividades conforme necessário
        ]);

        return view('admin.dashboard', compact(
            'totalEstudantes',
            'totalDocentes',
            'totalCursos',
            'totalPagamentos',
            'cursosLabels',
            'cursosData',
            'pagamentosLabels',
            'pagamentosData',
            'cursosMaisProcurados',
            'atividadesRecentes'
        ));
    }
}