<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\MediaFinal;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgressoAcademicoController extends Controller
{
    /**
     * Página de Progresso Acadêmico Geral.
     * Mostra: por turma e/ou por disciplina a evolução das notas.
     */
    public function index(Request $request)
    {
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();
        $anoId = $request->ano_lectivo_id ?? $anoAtivo?->id;
        $classeId = $request->classe_id;
        $turmaId = $request->turma_id;
        $discId = $request->disciplina_id;

        $classes = Classe::with(['turmas.estudantes'])->orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        // Turmas disponíveis
        $turmasQuery = Turma::with(['classe', 'anoLectivo'])
            ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->when($classeId, fn ($q) => $q->where('classe_id', $classeId));
        $turmas = $turmasQuery->get();

        // Disciplinas filtradas por classe quando houver turmas
        $disciplinas = $turmas->isNotEmpty()
            ? Disciplina::whereIn('classe_id', $turmas->pluck('classe_id'))->with('docente.user')->orderBy('nome')->get()
            : collect();

        // ---------- ESTATÍSTICAS GERAIS ----------
        $totalAlunos = Estudante::where('status', 'Ativo')
            ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->count();
        $totalAprovados = MediaFinal::when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->whereIn('status', ['Aprovado', 'Dispensado'])->distinct('estudante_id')->count('estudante_id');
        $totalReprovados = $totalAlunos - $totalAprovados;
        $taxaAprovacao = $totalAlunos > 0 ? round(($totalAprovados / $totalAlunos) * 100, 1) : 0;

        // ---------- MÉDIAS POR DISCIPLINA (ANO ACTIVO) ----------
        $mediasPorDisciplina = MediaFinal::with('disciplina')
            ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->get()
            ->groupBy('disciplina_id')
            ->map(function ($items) {
                $avg = $items->avg('media');
                $aprov = $items->whereIn('status', ['Aprovado', 'Dispensado'])->count();

                return [
                    'disciplina' => $items->first()?->disciplina?->nome ?? '—',
                    'media' => round($avg, 2),
                    'aprovados' => $aprov,
                    'total' => $items->count(),
                ];
            })
            ->values();

        // ---------- ALUNOS COM BAIXO DESEMPENHO ----------
        $alunosBaixoDesempenho = Estudante::with(['user', 'turma.classe'])
            ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->whereHas('mediaFinais', fn ($q) => $q->where('ano_lectivo_id', $anoId)->where('status', 'Reprovado'))
            ->limit(10)
            ->get()
            ->map(function ($e) use ($anoId) {
                $medias = $e->mediaFinais()->where('ano_lectivo_id', $anoId)->get();
                $pior = $medias->sortBy('media')->first();

                return (object) [
                    'aluno' => $e->user->name ?? 'N/A',
                    'turma' => $e->turma ? "{$e->turma->classe->nome} {$e->turma->nome}" : 'N/A',
                    'pior_disc' => $pior?->disciplina?->nome ?? '—',
                    'pior_media' => $pior?->media ?? 0,
                    'reprovacoes' => $medias->where('status', 'Reprovado')->count(),
                ];
            });

        // ---------- MELHORES ALUNOS (Top 10 por média geral) ----------
        $topAlunos = DB::table('estudantes')
            ->join('users', 'estudantes.user_id', '=', 'users.id')
            ->leftJoin('media_finals', function ($join) use ($anoId) {
                $join->on('estudantes.id', '=', 'media_finals.estudante_id')
                    ->where('media_finals.ano_lectivo_id', '=', $anoId);
            })
            ->select('estudantes.id', 'users.name', 'estudantes.matricula', DB::raw('AVG(media_finals.media) as media_geral'))
            ->when($anoId, fn ($q) => $q->where('estudantes.ano_lectivo_id', $anoId))
            ->groupBy('estudantes.id', 'users.name', 'estudantes.matricula')
            ->orderByDesc('media_geral')
            ->limit(10)
            ->get();

        // ---------- DESEMPENHO POR TURMA ----------
        $desempenhoPorTurma = [];
        foreach ($turmas as $t) {
            $medias = MediaFinal::where('turma_id', $t->id)
                ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
                ->get();
            $desempenhoPorTurma[] = [
                'turma' => "{$t->classe->nome} {$t->nome}",
                'total_alunos' => $t->estudantes_count,
                'media_geral' => $medias->count() > 0 ? round($medias->avg('media'), 2) : 0,
                'aprovados' => $medias->whereIn('status', ['Aprovado', 'Dispensado'])->count(),
                'reprovados' => $medias->where('status', 'Reprovado')->count(),
            ];
        }

        return view('admin.progresso_academico.index', compact(
            'classes', 'anosLectivos', 'turmas', 'disciplinas',
            'anoId', 'classeId', 'turmaId', 'discId',
            'totalAlunos', 'totalAprovados', 'totalReprovados', 'taxaAprovacao',
            'mediasPorDisciplina', 'alunosBaixoDesempenho', 'topAlunos',
            'desempenhoPorTurma', 'anoAtivo'
        ));
    }

    /**
     * Detalhes de uma turma: todas as disciplinas e médias dos alunos.
     */
    public function porTurma(Turma $turma, Request $request)
    {
        $anoId = $request->ano_lectivo_id ?? AnoLectivo::where('status', 'Ativo')->first()?->id;
        $turma->load(['classe.disciplinas.docente', 'estudantes.user']);

        $disciplinas = $turma->classe?->disciplinas()->with('docente.user')->get() ?? collect();

        // Montar matriz: aluno x disciplina = média final
        $matriz = [];
        foreach ($turma->estudantes as $aluno) {
            $matriz[$aluno->id] = [
                'aluno' => $aluno->user->name ?? 'N/A',
                'matricula' => $aluno->matricula,
                'medias' => [],
                'media_geral' => 0,
                'reprovacoes' => 0,
            ];
            $soma = 0;
            foreach ($disciplinas as $d) {
                $mf = MediaFinal::where('estudante_id', $aluno->id)
                    ->where('disciplina_id', $d->id)
                    ->where('ano_lectivo_id', $anoId)
                    ->first();
                $matriz[$aluno->id]['medias'][$d->id] = $mf?->media ?? null;
                if (! is_null($mf?->media)) {
                    $soma += $mf->media;
                    if ($mf->status === 'Reprovado') {
                        $matriz[$aluno->id]['reprovacoes']++;
                    }
                }
            }
            $matriz[$aluno->id]['media_geral'] = $disciplinas->count() > 0
                ? round($soma / $disciplinas->count(), 2) : 0;
        }

        return view('admin.progresso_academico.turma', compact('turma', 'disciplinas', 'matriz', 'anoId'));
    }

    /**
     * Detalhes de uma disciplina: desempenho dos alunos de uma disciplina.
     */
    public function porDisciplina(Disciplina $disciplina, Request $request)
    {
        $anoId = $request->ano_lectivo_id ?? AnoLectivo::where('status', 'Ativo')->first()?->id;
        $turmaId = $request->turma_id;

        $disciplina->load('classe', 'docente.user');
        $turmas = Turma::where('classe_id', $disciplina->classe_id)
            ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
            ->with('estudantes.user')
            ->get();

        $turmaSelecionada = $turmaId ? $turmas->find($turmaId) : ($turmas->first() ?? null);

        $alunos = $turmaSelecionada
            ? $turmaSelecionada->estudantes()
                ->with(['user', 'notasFrequencia' => fn ($q) => $q->where('disciplina_id', $disciplina->id)->where('ano_lectivo_id', $anoId), 'notasExame' => fn ($q) => $q->where('disciplina_id', $disciplina->id)->where('ano_lectivo_id', $anoId), 'mediaFinais' => fn ($q) => $q->where('disciplina_id', $disciplina->id)->where('ano_lectivo_id', $anoId)])
                ->get()
                ->map(function ($a) {
                    $nf = $a->notasFrequencia->first();
                    $ne = $a->notasExame->first();
                    $mf = $a->mediaFinais->first();

                    return (object) [
                        'id' => $a->id,
                        'nome' => $a->user->name ?? 'N/A',
                        'matricula' => $a->matricula,
                        'frequencia' => $nf?->media_trimestral ?? null,
                        'exame' => $ne?->nota ?? null,
                        'media_final' => $mf?->media ?? null,
                        'resultado' => $mf?->status ?? ($mf?->media >= 10 ? 'Aprovado' : 'Reprovado'),
                    ];
                })
            : collect();

        return view('admin.progresso_academico.disciplina', compact('disciplina', 'turmas', 'turmaSelecionada', 'alunos', 'anoId'));
    }
}
