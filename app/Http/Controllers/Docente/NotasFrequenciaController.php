<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Docente;
use App\Models\NotaFrequencia;
use App\Models\ResultadoFinal;
use App\Models\NotaExame;
use App\Models\AnoLectivo;
use App\Models\Classe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotasFrequenciaController extends Controller
{
    /**
     * Lista as disciplinas do docente para lançar notas
     */
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();

        if (!$docente) {
            return redirect()->route('docente.dashboard')->with('error', 'Perfil de docente não encontrado.');
        }

        // Obter disciplinas com contagem de estudantes baseada na Classe/Turma
        $disciplinas = Disciplina::where('docente_id', $docente->id)
            ->with(['classe'])
            ->get();

        foreach ($disciplinas as $disciplina) {
            $disciplina->estudantes_count = Estudante::whereHas('turma', function ($query) use ($disciplina) {
                $query->where('classe_id', $disciplina->classe_id);
            })->where('status', 'Ativo')->count();
        }

        return view('docente.notas_frequencia.index', compact('disciplinas'));
    }

    /**
     * Pauta trimestral da disciplina — lançamento de ACS1, ACS2, ACS3, ACP, ACF
     */
    public function show(Request $request, $disciplinaId)
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        $disciplina = Disciplina::with(['classe'])->findOrFail($disciplinaId);

        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }

        $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();

        if (!$anoLectivoAtual) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('warning', 'Não há ano lectivo activo no momento.');
        }

        $trimestre = $request->get('trimestre', 1);

        // Get students from turmas of this discipline's classe
        $estudantes = Estudante::whereHas('turma', function ($query) use ($disciplina) {
            $query->where('classe_id', $disciplina->classe_id);
        })
            ->with(['user', 'turma'])
            ->where('status', 'Ativo')
            ->get()
            ->sortBy(fn ($e) => $e->user->name ?? '');

        // Get existing notes for this trimester
        $notasExistentes = NotaFrequencia::where('disciplina_id', $disciplinaId)
            ->where('ano_lectivo_id', $anoLectivoAtual->id)
            ->where('trimestre', $trimestre)
            ->whereIn('estudante_id', $estudantes->pluck('id'))
            ->get()
            ->keyBy('estudante_id');

        foreach ($estudantes as $est) {
            $est->nota_trimestre = $notasExistentes->get($est->id);
        }

        return view('docente.notas_frequencia.show', compact(
            'disciplina', 'estudantes', 'anoLectivoAtual', 'docente', 'trimestre'
        ));
    }

    /**
     * Salvar notas trimestrais
     */
    public function store(Request $request, $disciplinaId)
    {
        $request->validate([
            'notas'          => 'required|array',
            'disciplina_id'  => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'trimestre'      => 'required|in:1,2,3',
        ]);

        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        $disciplina = Disciplina::findOrFail($disciplinaId);

        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Você não está autorizado a modificar notas desta disciplina.');
        }

        $anoId = $request->ano_lectivo_id;
        $trimestre = $request->trimestre;

        DB::beginTransaction();
        try {
            foreach ($request->notas as $estudanteId => $dados) {
                $acs1 = isset($dados['acs1']) && $dados['acs1'] !== '' ? (float) $dados['acs1'] : null;
                $acs2 = isset($dados['acs2']) && $dados['acs2'] !== '' ? (float) $dados['acs2'] : null;
                $acs3 = isset($dados['acs3']) && $dados['acs3'] !== '' ? (float) $dados['acs3'] : null;
                $acp  = isset($dados['acp']) && $dados['acp'] !== '' ? (float) $dados['acp'] : null;
                $acf  = isset($dados['acf']) && $dados['acf'] !== '' ? (float) $dados['acf'] : null;

                $mediaTrimestral = null;
                $acsValues = array_filter([$acs1, $acs2, $acs3], fn ($v) => $v !== null);

                if (count($acsValues) > 0 && $acp !== null) {
                    $mac = array_sum($acsValues) / count($acsValues);
                    if ($acf !== null) {
                        $mediaTrimestral = round(($mac + $acp + $acf) / 3, 2);
                    } else {
                        $mediaTrimestral = round(($mac + $acp) / 2, 2);
                    }
                }

                NotaFrequencia::updateOrCreate(
                    [
                        'estudante_id'   => $estudanteId,
                        'disciplina_id'  => $disciplinaId,
                        'ano_lectivo_id' => $anoId,
                        'trimestre'      => $trimestre,
                    ],
                    [
                        'acs1'             => $acs1,
                        'acs2'             => $acs2,
                        'acs3'             => $acs3,
                        'acp'              => $acp,
                        'acf'              => $acf,
                        'comportamento'    => $dados['comportamento'] ?? null,
                        'faltas'           => $dados['faltas'] ?? 0,
                        'media_trimestral' => $mediaTrimestral,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('docente.notas_frequencia.show', [
                'disciplina' => $disciplinaId,
                'trimestre'  => $trimestre,
            ])->with('success', 'Notas do ' . $trimestre . 'º trimestre salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar notas: ' . $e->getMessage());
            return back()->with('error', 'Erro ao salvar notas: ' . $e->getMessage());
        }
    }

    /**
     * Vista da pauta completa anual para consulta do docente
     */
    public function pauta(Request $request, $disciplinaId)
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        $disciplina = Disciplina::with(['classe'])->findOrFail($disciplinaId);

        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Acesso não autorizado.');
        }

        $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();

        if (!$anoLectivoAtual) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('warning', 'Não há ano lectivo activo.');
        }

        $estudantes = Estudante::whereHas('turma', function ($query) use ($disciplina) {
            $query->where('classe_id', $disciplina->classe_id);
        })
            ->with(['user', 'turma'])
            ->where('status', 'Ativo')
            ->get()
            ->sortBy(fn ($e) => $e->user->name ?? '');

        foreach ($estudantes as $aluno) {
            $notasTrimestres = NotaFrequencia::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disciplina->id)
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->get()
                ->keyBy('trimestre');

            $aluno->t1 = $notasTrimestres->get(1);
            $aluno->t2 = $notasTrimestres->get(2);
            $aluno->t3 = $notasTrimestres->get(3);

            $aluno->resultado = ResultadoFinal::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disciplina->id)
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->first();

            $aluno->exame = NotaExame::where('estudante_id', $aluno->id)
                ->where('disciplina_id', $disciplina->id)
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->first();
        }

        return view('docente.notas_frequencia.pauta', compact(
            'disciplina', 'estudantes', 'anoLectivoAtual', 'docente'
        ));
    }
}