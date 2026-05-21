<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Disciplina;
use App\Models\NotaExame;
use App\Models\NotaFrequencia;
use App\Models\ResultadoFinal;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    /**
     * Dashboard de Notas — lista de turmas/classes com opção de ver notas.
     */
    public function index(Request $request)
    {
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        $turmasQuery = Turma::with(['classe', 'anoLectivo', 'estudantes'])
            ->withCount('estudantes')
            ->when($anoAtivo, fn ($q) => $q->where('ano_lectivo_id', $anoAtivo->id))
            ->when($request->filled('classe_id'), fn ($q) => $q->where('classe_id', $request->classe_id))
            ->when($request->filled('ano_lectivo_id'), fn ($q) => $q->where('ano_lectivo_id', $request->ano_lectivo_id))
            ->when($request->filled('search'), fn ($q) => $q->where('nome', 'like', "%{$request->search}%"))
            ->orderBy('created_at', 'desc');

        $turmas = $turmasQuery->paginate(15)->appends($request->all());

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('admin.notas.index', compact('turmas', 'classes', 'anosLectivos', 'anoAtivo'));
    }

    /**
     * Formulário de lançamento de notas trimestrais (ACS1, ACS2, ACS3, ACP, ACF)
     */
    public function create(Request $request)
    {
        $turmaId = $request->get('turma_id');
        $anoId = $request->get('ano_lectivo_id');
        $trimestre = $request->get('trimestre', 1);

        $turma = $turmaId ? Turma::with(['classe.disciplinas.docente.user', 'estudantes.user'])->findOrFail($turmaId) : null;

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        // Se turma selecionada, carregar disciplinas da classe
        $disciplinas = $turma
            ? ($turma->classe?->disciplinas()->with('docente.user')->get() ?? collect())
            : collect();

        // Se turma + disciplina selecionadas, carregar alunos com notas do trimestre
        $alunosComNotas = collect();
        $disciplinaSelecionada = null;

        if ($turma && $request->filled('disciplina_id')) {
            $disciplinaSelecionada = Disciplina::with('docente.user')->find($request->disciplina_id);

            $alunosComNotas = $turma->estudantes()
                ->with(['user'])
                ->orderBy(
                    \App\Models\User::select('name')
                        ->whereColumn('users.id', 'estudantes.user_id')
                        ->limit(1),
                    'asc'
                )
                ->get();

            // Attach existing notas for this trimester
            $notasExistentes = NotaFrequencia::where('disciplina_id', $request->disciplina_id)
                ->where('ano_lectivo_id', $anoAtivo?->id ?? $anoId)
                ->where('trimestre', $trimestre)
                ->whereIn('estudante_id', $alunosComNotas->pluck('id'))
                ->get()
                ->keyBy('estudante_id');

            foreach ($alunosComNotas as $aluno) {
                $aluno->nota_trimestre = $notasExistentes->get($aluno->id);
            }
        }

        return view('admin.notas.create', compact(
            'turma', 'turmaId', 'anoId', 'trimestre',
            'classes', 'anosLectivos', 'anoAtivo',
            'disciplinas', 'disciplinaSelecionada',
            'alunosComNotas'
        ));
    }

    /**
     * Calcula a Média Trimestral segundo o SNE Moçambique.
     *
     * - MAC = média das ACS lançadas (1, 2 ou 3)
     * - Se ACF presente:  MT = (MAC + ACP + ACF) / 3
     * - Se ACF ausente:   MT = (MAC + ACP) / 2
     * - ACP obrigatório para calcular MT
     */
    private function calcularMT($acs1, $acs2, $acs3, $acp, $acf): ?float
    {
        $acsValues = array_filter([$acs1, $acs2, $acs3], fn ($v) => $v !== null);

        if (count($acsValues) === 0 || $acp === null) {
            return null;
        }

        $mac = array_sum($acsValues) / count($acsValues);

        if ($acf !== null) {
            $mt = ($mac + $acp + $acf) / 3;
        } else {
            $mt = ($mac + $acp) / 2;
        }

        return round($mt, 2);
    }

    /**
     * Armazena notas trimestrais em lote (ACS1, ACS2, ACS3, ACP, ACF)
     */
    public function store(Request $request)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'trimestre'      => 'required|in:1,2,3',
            'notas'          => 'required|array',
            'notas.*.estudante_id' => 'required|exists:estudantes,id',
            'notas.*.acs1'   => 'nullable|numeric|min:0|max:20',
            'notas.*.acs2'   => 'nullable|numeric|min:0|max:20',
            'notas.*.acs3'   => 'nullable|numeric|min:0|max:20',
            'notas.*.acp'    => 'nullable|numeric|min:0|max:20',
            'notas.*.acf'    => 'nullable|numeric|min:0|max:20',
            'notas.*.comportamento' => 'nullable|in:Bom,Razoável,Mau',
            'notas.*.faltas' => 'nullable|integer|min:0',
        ]);

        $turma = Turma::findOrFail($request->turma_id);
        $anoId = $request->ano_lectivo_id;
        $discId = $request->disciplina_id;
        $trimestre = $request->trimestre;

        DB::beginTransaction();
        try {
            foreach ($request->notas as $item) {
                $estId = $item['estudante_id'];

                $acs1 = isset($item['acs1']) && $item['acs1'] !== '' ? (float) $item['acs1'] : null;
                $acs2 = isset($item['acs2']) && $item['acs2'] !== '' ? (float) $item['acs2'] : null;
                $acs3 = isset($item['acs3']) && $item['acs3'] !== '' ? (float) $item['acs3'] : null;
                $acp  = isset($item['acp']) && $item['acp'] !== '' ? (float) $item['acp'] : null;
                $acf  = isset($item['acf']) && $item['acf'] !== '' ? (float) $item['acf'] : null;

                $mediaTrimestral = $this->calcularMT($acs1, $acs2, $acs3, $acp, $acf);

                NotaFrequencia::updateOrCreate(
                    [
                        'estudante_id'  => $estId,
                        'disciplina_id' => $discId,
                        'ano_lectivo_id'=> $anoId,
                        'trimestre'     => $trimestre,
                    ],
                    [
                        'acs1'              => $acs1,
                        'acs2'              => $acs2,
                        'acs3'              => $acs3,
                        'acp'               => $acp,
                        'acf'               => $acf,
                        'comportamento'     => $item['comportamento'] ?? null,
                        'faltas'            => $item['faltas'] ?? 0,
                        'media_trimestral'  => $mediaTrimestral,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.notas.create', [
                    'turma_id'       => $request->turma_id,
                    'disciplina_id'  => $request->disciplina_id,
                    'ano_lectivo_id' => $anoId,
                    'trimestre'      => $trimestre,
                ])
                ->with('success', 'Notas do ' . $trimestre . 'º trimestre salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao salvar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Pauta completa — vista anual de todas as notas de uma turma/disciplina
     */
    public function show(Request $request)
    {
        $turmaId = $request->get('turma_id');
        $anoId = $request->get('ano_lectivo_id');
        $discId = $request->get('disciplina_id');

        $turma = $turmaId ? Turma::with(['classe', 'estudantes.user', 'anoLectivo'])->findOrFail($turmaId) : null;
        $disc = $discId ? Disciplina::with('docente.user')->findOrFail($discId) : null;
        $ano = $anoId ? AnoLectivo::findOrFail($anoId) : null;

        $alunos = collect();
        $disciplinas = collect();

        if ($turma) {
            $disciplinas = $turma->classe?->disciplinas()->with('docente.user')->get() ?? collect();
        }

        if ($turma && $disc && $ano) {
            $alunos = $turma->estudantes()
                ->with('user')
                ->orderBy(
                    \App\Models\User::select('name')
                        ->whereColumn('users.id', 'estudantes.user_id')
                        ->limit(1),
                    'asc'
                )
                ->get();

            // Attach trimester notas for each student
            foreach ($alunos as $aluno) {
                $notasTrimestres = NotaFrequencia::where('estudante_id', $aluno->id)
                    ->where('disciplina_id', $disc->id)
                    ->where('ano_lectivo_id', $ano->id)
                    ->get()
                    ->keyBy('trimestre');

                $aluno->t1 = $notasTrimestres->get(1);
                $aluno->t2 = $notasTrimestres->get(2);
                $aluno->t3 = $notasTrimestres->get(3);

                // Get resultado final
                $aluno->resultado = ResultadoFinal::where('estudante_id', $aluno->id)
                    ->where('disciplina_id', $disc->id)
                    ->where('ano_lectivo_id', $ano->id)
                    ->first();

                // Get nota exame
                $aluno->exame = NotaExame::where('estudante_id', $aluno->id)
                    ->where('disciplina_id', $disc->id)
                    ->where('ano_lectivo_id', $ano->id)
                    ->first();
            }
        }

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('admin.notas.show', compact(
            'turma', 'disc', 'ano', 'alunos',
            'classes', 'anosLectivos', 'disciplinas'
        ));
    }

    /**
     * Calcula resultados finais anuais (MT1+MT2+MT3)/3 → MF → Classificação
     */
    public function calcularMedias(Request $request)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::findOrFail($request->turma_id);
        $discId = $request->disciplina_id;
        $anoId = $request->ano_lectivo_id;

        $alunos = $turma->estudantes()->pluck('estudantes.id');
        $criadas = 0;

        foreach ($alunos as $estId) {
            // Get 3 trimester medias
            $notasTrimestres = NotaFrequencia::where('estudante_id', $estId)
                ->where('disciplina_id', $discId)
                ->where('ano_lectivo_id', $anoId)
                ->get()
                ->keyBy('trimestre');

            $mt1 = $notasTrimestres->get(1)?->media_trimestral;
            $mt2 = $notasTrimestres->get(2)?->media_trimestral;
            $mt3 = $notasTrimestres->get(3)?->media_trimestral;

            // Media de Frequência = média das MTs disponíveis (idealmente 3)
            $mediaFrequencia = null;
            $mts = array_filter([$mt1, $mt2, $mt3], fn ($v) => $v !== null);
            if (count($mts) > 0) {
                $mediaFrequencia = round(array_sum($mts) / count($mts), 2);
            }

            // Get exame if exists
            $notaExame = NotaExame::where('estudante_id', $estId)
                ->where('disciplina_id', $discId)
                ->where('ano_lectivo_id', $anoId)
                ->first();

            // Classificação SNE:
            // MF >= 14 → Dispensado (não faz exame)
            // MF >= 10 e < 14 → Admitido (faz exame)
            //   CF = MF*0.6 + Exame*0.4 → >= 10 → Aprovado, < 10 → Reprovado
            // MF < 10 → Excluído (reprovado)
            $classificacao = null;
            $mediaFinal = null;

            if ($mediaFrequencia !== null) {
                if ($mediaFrequencia >= 14) {
                    $classificacao = 'Dispensado';
                    $mediaFinal = $mediaFrequencia;
                } elseif ($mediaFrequencia >= 10) {
                    $classificacao = 'Admitido';
                    if ($notaExame && $notaExame->nota !== null) {
                        $mediaFinal = round($mediaFrequencia * 0.6 + $notaExame->nota * 0.4, 2);
                        $classificacao = $mediaFinal >= 10 ? 'Aprovado' : 'Reprovado';
                    }
                } else {
                    $classificacao = 'Excluído';
                    $mediaFinal = $mediaFrequencia;
                }
            }

            ResultadoFinal::updateOrCreate(
                ['estudante_id' => $estId, 'disciplina_id' => $discId, 'ano_lectivo_id' => $anoId],
                [
                    'mt1'                => $mt1,
                    'mt2'                => $mt2,
                    'mt3'                => $mt3,
                    'media_frequencia'   => $mediaFrequencia,
                    'nota_exame'         => $notaExame?->nota,
                    'media_final'        => $mediaFinal,
                    'classificacao_final'=> $classificacao,
                ]
            );

            // Também atualiza a tabela media_finals para manter estatísticas legadas
            if ($classificacao !== null) {
                $statusFinal = null;
                if ($classificacao == 'Dispensado') {
                    $statusFinal = 'Dispensado';
                } elseif ($classificacao == 'Aprovado') {
                    $statusFinal = 'Aprovado';
                } elseif (in_array($classificacao, ['Excluído', 'Reprovado'])) {
                    $statusFinal = 'Reprovado';
                }

                if ($statusFinal !== null && $mediaFinal !== null) {
                    \App\Models\MediaFinal::updateOrCreate(
                        ['estudante_id' => $estId, 'disciplina_id' => $discId, 'ano_lectivo_id' => $anoId],
                        [
                            'media' => $mediaFinal,
                            'status' => $statusFinal,
                        ]
                    );
                }
            }

            $criadas++;
        }

        return back()->with('success', "Resultados finais calculados para {$criadas} estudantes!");
    }

    /**
     * Pauta Final — mostra TODAS as disciplinas de uma turma com resultados de cada aluno.
     * Permite ver se o aluno passou ou reprovou no geral.
     */
    public function pautaFinal(Request $request)
    {
        $turmaId = $request->get('turma_id');
        $anoId = $request->get('ano_lectivo_id');

        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();
        $ano = $anoId ? AnoLectivo::find($anoId) : $anoAtivo;
        $turma = $turmaId ? Turma::with(['classe', 'anoLectivo'])->find($turmaId) : null;

        $turmas = Turma::with(['classe'])
            ->when($ano, fn ($q) => $q->where('ano_lectivo_id', $ano->id))
            ->orderBy('nome')
            ->get();

        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $alunos = collect();
        $disciplinas = collect();

        if ($turma && $ano) {
            $disciplinas = $turma->classe?->disciplinas()->orderBy('nome')->get() ?? collect();

            $alunos = $turma->estudantes()
                ->with('user')
                ->orderBy(
                    \App\Models\User::select('name')
                        ->whereColumn('users.id', 'estudantes.user_id')
                        ->limit(1),
                    'asc'
                )
                ->get();

            foreach ($alunos as $aluno) {
                $resultados = ResultadoFinal::where('estudante_id', $aluno->id)
                    ->where('ano_lectivo_id', $ano->id)
                    ->whereIn('disciplina_id', $disciplinas->pluck('id'))
                    ->get()
                    ->keyBy('disciplina_id');

                $aluno->resultados = $resultados;

                // Calcular se o aluno passou no geral
                // Regra: Passa se não tem nenhuma disciplina Excluído/Reprovado
                $totalDisc = $disciplinas->count();
                $aprovadas = 0;
                $reprovadas = 0;
                $pendentes = 0;

                foreach ($disciplinas as $d) {
                    $res = $resultados->get($d->id);
                    if (!$res || !$res->classificacao_final) {
                        $pendentes++;
                    } elseif (in_array($res->classificacao_final, ['Dispensado', 'Aprovado'])) {
                        $aprovadas++;
                    } else {
                        $reprovadas++;
                    }
                }

                $aluno->total_aprovadas = $aprovadas;
                $aluno->total_reprovadas = $reprovadas;
                $aluno->total_pendentes = $pendentes;

                // Decisão final: aprovado se tem 0 reprovações e 0 pendentes
                if ($pendentes > 0) {
                    $aluno->decisao_final = 'Pendente';
                } elseif ($reprovadas == 0) {
                    $aluno->decisao_final = 'Transitou';
                } else {
                    $aluno->decisao_final = 'Não Transitou';
                }
            }
        }

        return view('admin.notas.pauta_final', compact(
            'turma', 'ano', 'turmas', 'anosLectivos',
            'alunos', 'disciplinas'
        ));
    }

    /**
     * Gera PDF da Pauta por turma/disciplina
     */
    public function pdfPauta(Request $request)
    {
        $request->validate([
            'turma_id'       => 'required|exists:turmas,id',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::with(['classe', 'anoLectivo'])->findOrFail($request->turma_id);
        $disc = Disciplina::with('docente.user')->findOrFail($request->disciplina_id);
        $ano = AnoLectivo::findOrFail($request->ano_lectivo_id);

        $alunos = $turma->estudantes()
            ->with('user')
            ->orderBy(
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'estudantes.user_id')
                    ->limit(1),
                'asc'
            )
            ->get();

        foreach ($alunos as $aluno) {
            $notasTrimestres = NotaFrequencia::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $ano->id)
                ->get()
                ->keyBy('trimestre');

            $aluno->t1 = $notasTrimestres->get(1);
            $aluno->t2 = $notasTrimestres->get(2);
            $aluno->t3 = $notasTrimestres->get(3);

            $aluno->resultado = ResultadoFinal::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $ano->id)
                ->first();

            $aluno->exame = NotaExame::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $ano->id)
                ->first();
        }

        $pdf = Pdf::loadView('pdf.pauta_notas', compact('turma', 'disc', 'ano', 'alunos'))
            ->setPaper('A4', 'landscape');

        return $pdf->download("Pauta_{$disc->nome}_{$turma->nome}_{$ano->ano}.pdf");
    }
}
