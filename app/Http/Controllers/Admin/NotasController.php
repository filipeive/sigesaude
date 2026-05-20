<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use App\Models\NotaExame;
use App\Models\NotaFrequencia;
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
            ->when($request->filled('classe_id'), fn ($q, $cid) => $q->where('classe_id', $cid))
            ->when($request->filled('ano_lectivo_id'), fn ($q, $aid) => $q->where('ano_lectivo_id', $aid))
            ->when($request->filled('search'), fn ($q, $s) => $q->where('nome', 'like', "%{$s}%"))
            ->orderBy('created_at', 'desc');

        $turmas = $turmasQuery->paginate(15)->appends($request->all());

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('admin.notas.index', compact('turmas', 'classes', 'anosLectivos', 'anoAtivo'));
    }

    /**
     * Formulário de criação: selecione turma + disciplina + alunos
     */
    public function create(Request $request)
    {
        $turmaId = $request->get('turma_id');
        $anoId = $request->get('ano_lectivo_id');
        $tipoNota = $request->get('tipo_nota', 'frequencia'); // frequencia | exame | detalhada

        $turma = $turmaId ? Turma::with(['classe.disciplinas', 'estudantes.user'])->findOrFail($turmaId) : null;

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        // Se turma selecionada, carregar disciplinas da classe
        $disciplinas = $turma
            ? $turma->classe?->disciplinas()->with('docente.user')->get()
            : collect();

        // Se turma + disciplina selecionadas, carregar alunos com nota atual (ou vazia)
        $alunosComNotas = collect();
        $disciplinaSelecionada = null;
        if ($turma && $request->filled('disciplina_id')) {
            $disciplinaSelecionada = Disciplina::with('docente.user')->find($request->disciplina_id);

            $alunosQuery = $turma->estudantes()
                ->with(['user', 'notasFrequencia', 'notasExame'])
                ->orderByRaw('(SELECT name FROM users WHERE id = estudante_id) ASC');

            if ($tipoNota === 'frequencia') {
                $alunosQuery->with(['notasFrequencia' => fn ($q) => $q->where('disciplina_id', $request->disciplina_id)]);
            } elseif ($tipoNota === 'exame') {
                $alunosQuery->with(['notasExame' => fn ($q) => $q->where('disciplina_id', $request->disciplina_id)]);
            }

            $alunosComNotas = $alunosQuery->get();
        }

        return view('admin.notas.create', compact(
            'turma', 'turmaId', 'anoId', 'tipoNota',
            'classes', 'anosLectivos', 'anoAtivo',
            'disciplinas', 'disciplinaSelecionada',
            'alunosComNotas'
        ));
    }

    /**
     * Armazena notas de frequência em lote (array studyante_id => nota)
     */
    public function store(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'tipo_nota' => 'required|in:frequencia,exame,detalhada',
            'notas' => 'required|array',
            'notas.*.estudante_id' => 'required|exists:estudantes,id',
            'notas.*.nota' => 'nullable|numeric|min:0|max:20',
            'tipo_exame' => 'nullable|string|max:50',
        ]);

        $turma = Turma::findOrFail($request->turma_id);
        $anoId = $request->ano_lectivo_id;
        $discId = $request->disciplina_id;
        $tipo = $request->tipo_nota;

        DB::beginTransaction();
        try {
            foreach ($request->notas as $item) {
                $estId = $item['estudante_id'];
                $nota = $item['nota'] ?? null;

                if ($tipo === 'frequencia') {
                    $status = is_null($nota) ? 'pendente' : 'lançada';
                    NotaFrequencia::updateOrCreate(
                        ['estudante_id' => $estId, 'disciplina_id' => $discId, 'ano_lectivo_id' => $anoId],
                        ['nota' => $nota, 'status' => $status, 'turma_id' => $turma->id]
                    );
                } elseif ($tipo === 'exame') {
                    NotaExame::updateOrCreate(
                        ['estudante_id' => $estId, 'disciplina_id' => $discId, 'ano_lectivo_id' => $anoId],
                        ['nota' => $nota, 'tipo_exame' => $request->tipo_exame ?? 'normal', 'turma_id' => $turma->id]
                    );
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.notas.create', array_merge($request->only(['turma_id', 'disciplina_id', 'ano_lectivo_id', 'tipo_nota'])))
                ->with('success', 'Notas salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao salvar: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Detalhes das notas de uma disciplina/turma (página de leitura)
     */
    public function show(Request $request)
    {
        $turmaId = $request->get('turma_id');
        $anoId = $request->get('ano_lectivo_id');
        $discId = $request->get('disciplina_id');
        $tipoNota = $request->get('tipo_nota', 'frequencia');

        $turma = $turmaId ? Turma::with(['classe', 'estudantes.user'])->findOrFail($turmaId) : null;
        $disc = $discId ? Disciplina::with('docente.user')->findOrFail($discId) : null;
        $ano = $anoId ? AnoLectivo::findOrFail($anoId) : null;

        $alunos = collect();
        $medias = collect();

        if ($turma && $disc) {
            $alunos = $turma->estudantes()
                ->with(['user', 'notasFrequencia', 'notasExame', 'mediaFinais'])
                ->get();

            $medias = \App\Models\MediaFinal::where('disciplina_id', $discId)
                ->when($anoId, fn ($q) => $q->where('ano_lectivo_id', $anoId))
                ->get()
                ->keyBy('estudante_id');
        }

        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $disciplinas = $turma ? ($turma->classe?->disciplinas ?? collect()) : collect();

        return view('admin.notas.show', compact(
            'turma', 'disc', 'ano', 'alunos', 'medias', 'tipoNota',
            'classes', 'anosLectivos', 'disciplinas'
        ));
    }

    /**
     * Lançar/editar Notas Frequência
     */
    public function editFrequencia(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::with(['classe.disciplinas', 'estudantes.user'])->findOrFail($request->turma_id);
        $disciplina = Disciplina::with('docente.user')->findOrFail($request->disciplina_id);
        $anoLectivo = AnoLectivo::findOrFail($request->ano_lectivo_id);

        $alunos = $turma->estudantes()
            ->with(['user', 'notasFrequencia' => fn ($q) => $q->where('disciplina_id', $request->disciplina_id)->where('ano_lectivo_id', $request->ano_lectivo_id)])
            ->get();

        return view('admin.notas.edit_frequencia', compact('turma', 'disciplina', 'anoLectivo', 'alunos'));
    }

    /**
     * Atualiza notas de frequência individual
     */
    public function updateFrequencia(Request $request, NotaFrequencia $nota)
    {
        $request->validate([
            'nota' => 'nullable|numeric|min:0|max:20',
        ]);

        $nota->update([
            'nota' => $request->nota,
            'status' => is_null($request->nota) ? 'pendente' : 'lançada',
        ]);

        return back()->with('success', 'Nota de frequência atualizada!');
    }

    /**
     * Lançar/editar Notas de Exame
     */
    public function editExame(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::with(['classe.disciplinas', 'estudantes.user'])->findOrFail($request->turma_id);
        $disciplina = Disciplina::with('docente.user')->findOrFail($request->disciplina_id);
        $anoLectivo = AnoLectivo::findOrFail($request->ano_lectivo_id);

        $alunos = $turma->estudantes()
            ->with(['user', 'notasExame' => fn ($q) => $q->where('disciplina_id', $request->disciplina_id)->where('ano_lectivo_id', $request->ano_lectivo_id)])
            ->get();

        return view('admin.notas.edit_exame', compact('turma', 'disciplina', 'anoLectivo', 'alunos'));
    }

    /**
     * Atualiza nota de exame individual
     */
    public function updateExame(Request $request, NotaExame $nota)
    {
        $request->validate([
            'nota' => 'required|numeric|min:0|max:20',
        ]);

        $nota->update(['nota' => $request->nota]);

        return back()->with('success', 'Nota de exame atualizada!');
    }

    /**
     * Calcula e salva médias finais
     */
    public function calcularMedias(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::findOrFail($request->turma_id);
        $discId = $request->disciplina_id;
        $anoId = $request->ano_lectivo_id;

        $alunos = $turma->estudantes()->pluck('id');
        $criadas = 0;

        foreach ($alunos as $estId) {
            $notaFreq = NotaFrequencia::where('estudante_id', $estId)
                ->where('disciplina_id', $discId)
                ->where('ano_lectivo_id', $anoId)
                ->first();

            $notaExam = NotaExame::where('estudante_id', $estId)
                ->where('disciplina_id', $discId)
                ->where('ano_lectivo_id', $anoId)
                ->first();

            $mediaFreq = $notaFreq?->nota ?? 0;
            $mediaExam = $notaExam?->nota ?? 0;
            $mediaFinal = round(($mediaFreq + $mediaExam) / 2, 2);

            $resultado = $mediaFinal >= 10 ? 'Aprovado' : 'Reprovado';

            MediaFinal::updateOrCreate(
                ['estudante_id' => $estId, 'disciplina_id' => $discId, 'ano_lectivo_id' => $anoId],
                [
                    'media_frequencia' => $mediaFreq,
                    'media_exame' => $mediaExam,
                    'media_final' => $mediaFinal,
                    'resultado' => $resultado,
                    'turma_id' => $turma->id,
                ]
            );
            $criadas++;
        }

        return back()->with('success', "Médias calculadas para {$criadas} estudantes!");
    }

    /**
     * Gera PDF do boletim por turma/disciplina
     */
    public function pdfBoletim(Request $request)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);

        $turma = Turma::with(['classe', 'estudantes.user', 'anoLectivo'])->findOrFail($request->turma_id);
        $disc = Disciplina::with('docente.user')->findOrFail($request->disciplina_id);
        $ano = AnoLectivo::findOrFail($request->ano_lectivo_id);

        $alunos = $turma->estudantes()
            ->with([
                'user',
                'notasFrequencia' => fn ($q) => $q->where('disciplina_id', $disc->id)->where('ano_lectivo_id', $ano->id),
                'notasExame' => fn ($q) => $q->where('disciplina_id', $disc->id)->where('ano_lectivo_id', $ano->id),
                'mediaFinais' => fn ($q) => $q->where('disciplina_id', $disc->id)->where('ano_lectivo_id', $ano->id),
            ])
            ->get();

        $pdf = Pdf::loadView('pdf.boletim_disciplina', compact('turma', 'disc', 'ano', 'alunos'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("Boletim_{$disc->nome}_{$turma->nome}_{$ano->ano}.pdf");
    }

    /**
     * Remove uma nota de frequência
     */
    public function destroyFrequencia(NotaFrequencia $notaFrequencia)
    {
        $notaFrequencia->delete();

        return back()->with('success', 'Nota de frequência removida.');
    }

    /**
     * Remove uma nota de exame
     */
    public function destroyExame(NotaExame $notaExame)
    {
        $notaExame->delete();

        return back()->with('success', 'Nota de exame removida.');
    }
}
