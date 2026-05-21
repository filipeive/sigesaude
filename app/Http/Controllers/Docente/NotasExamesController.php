<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Docente;
use App\Models\NotaFrequencia;
use App\Models\NotaExame;
use App\Models\MediaFinal;
use App\Models\ResultadoFinal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasExamesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if (!$docente) {
            return redirect()->route('docente.dashboard')->with('error', 'Perfil de docente não encontrado.');
        }

        $disciplinas = Disciplina::where('docente_id', $docente->id)
            ->with(['classe'])
            ->get();

        $anoLectivo = \App\Models\AnoLectivo::where('status', 'Ativo')->first();

        foreach ($disciplinas as $disciplina) {
            // Conta estudantes matriculados em turmas da classe desta disciplina
            $disciplina->estudantes_count = \App\Models\Estudante::whereHas('turma', function($query) use ($disciplina) {
                $query->where('classe_id', $disciplina->classe_id);
            })->where('status', 'Ativo')->count();

            // Admitidos (classificacao_final = 'Admitido')
            $disciplina->admitidos_count = ResultadoFinal::where('disciplina_id', $disciplina->id)
                ->when($anoLectivo, function($query) use ($anoLectivo) {
                    return $query->where('ano_lectivo_id', $anoLectivo->id);
                })
                ->where('classificacao_final', 'Admitido')
                ->count();

            // Excluídos (classificacao_final = 'Excluído')
            $disciplina->excluidos_count = ResultadoFinal::where('disciplina_id', $disciplina->id)
                ->when($anoLectivo, function($query) use ($anoLectivo) {
                    return $query->where('ano_lectivo_id', $anoLectivo->id);
                })
                ->where('classificacao_final', 'Excluído')
                ->count();
        }
        
        return view('docente.notas_exames.index', compact('disciplinas'));
    }
    
    public function show($disciplinaId)
    {
        $disciplina = Disciplina::findOrFail($disciplinaId);
        
        // Verificar se o docente está autorizado a ver esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_exames.index')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }
        
        // Obter ano letivo atual
        $anoLectivoAtual = \App\Models\AnoLectivo::where('status', 'Ativo')->first();
        
        if (!$anoLectivoAtual) {
            return redirect()->route('docente.notas_exames.index')
                ->with('warning', 'Não há ano letivo ativo no momento.');
        }
        
        try {
            // Obter estudantes admitidos para exame nesta disciplina (de acordo com resultados_finais)
            $resultadosAdmitidos = ResultadoFinal::where('disciplina_id', $disciplinaId)
                ->where('classificacao_final', 'Admitido')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user'])
                ->get();
            
            // Obter estudantes dispensados
            $resultadosDispensados = ResultadoFinal::where('disciplina_id', $disciplinaId)
                ->where('classificacao_final', 'Dispensado')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user'])
                ->get();
                
            // Obter estudantes excluídos
            $resultadosExcluidos = ResultadoFinal::where('disciplina_id', $disciplinaId)
                ->where('classificacao_final', 'Excluído')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user'])
                ->get();
            
            $estudantes = [];
            $estudantesDispensados = [];
            $estudantesExcluidos = [];
            
            // Processar estudantes admitidos
            foreach ($resultadosAdmitidos as $res) {
                if (!$res->estudante) {
                    continue;
                }
                
                // Buscar notas de exame do estudante para esta disciplina
                $notaExameNormal = NotaExame::where('estudante_id', $res->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Normal')
                    ->first();
                    
                $notaExameRecorrencia = NotaExame::where('estudante_id', $res->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Recorrência')
                    ->first();
                
                $estudantes[] = [
                    'estudante' => $res->estudante,
                    'nota_frequencia' => $res->media_frequencia,
                    'nota_exame_normal' => $notaExameNormal ? $notaExameNormal->nota : null,
                    'nota_exame_recorrencia' => $notaExameRecorrencia ? $notaExameRecorrencia->nota : null,
                    'media_final' => $res->media_final,
                    'status' => $res->classificacao_final,
                ];
            }
            
            // Processar estudantes dispensados
            foreach ($resultadosDispensados as $res) {
                if (!$res->estudante) {
                    continue;
                }
                
                $estudantesDispensados[] = [
                    'estudante' => $res->estudante,
                    'nota_frequencia' => $res->media_frequencia,
                    'media_final' => $res->media_final,
                    'status' => 'Dispensado',
                ];
            }
            
            // Processar estudantes excluídos
            foreach ($resultadosExcluidos as $res) {
                if (!$res->estudante) {
                    continue;
                }
                
                $estudantesExcluidos[] = [
                    'estudante' => $res->estudante,
                    'nota_frequencia' => $res->media_frequencia,
                    'status' => 'Excluído',
                ];
            }
            
            return view('docente.notas_exames.show', compact(
                'disciplina', 
                'estudantes', 
                'estudantesDispensados', 
                'estudantesExcluidos', 
                'anoLectivoAtual'
            ));
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao buscar estudantes para exame: ' . $e->getMessage());
            return redirect()->route('docente.notas_exames.index')
                ->with('error', 'Erro ao carregar estudantes: ' . $e->getMessage());
        }
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'estudante_id' => 'required|array',
            'notas' => 'required|array',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            foreach ($request->estudante_id as $index => $estudanteId) {
                // Processar notas de exame normal
                if (isset($request->notas[$estudanteId]['Normal']) && $request->notas[$estudanteId]['Normal'] !== '') {
                    NotaExame::updateOrCreate(
                        [
                            'estudante_id' => $estudanteId,
                            'disciplina_id' => $request->disciplina_id,
                            'ano_lectivo_id' => $request->ano_lectivo_id,
                            'tipo_exame' => 'Normal',
                        ],
                        [
                            'nota' => $request->notas[$estudanteId]['Normal'],
                        ]
                    );
                }
                
                // Processar notas de exame de recorrência
                if (isset($request->notas[$estudanteId]['Recorrência']) && $request->notas[$estudanteId]['Recorrência'] !== '') {
                    NotaExame::updateOrCreate(
                        [
                            'estudante_id' => $estudanteId,
                            'disciplina_id' => $request->disciplina_id,
                            'ano_lectivo_id' => $request->ano_lectivo_id,
                            'tipo_exame' => 'Recorrência',
                        ],
                        [
                            'nota' => $request->notas[$estudanteId]['Recorrência'],
                        ]
                    );
                }
                
                // Calcular média final e atualizar status
                $this->calcularMediaFinal($estudanteId, $request->disciplina_id, $request->ano_lectivo_id);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Notas de exame salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao salvar notas de exame: ' . $e->getMessage());
        }
    }

    private function calcularMediaFinal($estudanteId, $disciplinaId, $anoLectivoId)
    {
        $res = ResultadoFinal::where('estudante_id', $estudanteId)
            ->where('disciplina_id', $disciplinaId)
            ->where('ano_lectivo_id', $anoLectivoId)
            ->first();
        
        if (!$res) {
            return;
        }
        
        // Obter a melhor nota de exame (normal ou recorrência)
        $notaExameNormal = NotaExame::where('estudante_id', $estudanteId)
            ->where('disciplina_id', $disciplinaId)
            ->where('ano_lectivo_id', $anoLectivoId)
            ->where('tipo_exame', 'Normal')
            ->first();
        
        $notaExameRecorrencia = NotaExame::where('estudante_id', $estudanteId)
            ->where('disciplina_id', $disciplinaId)
            ->where('ano_lectivo_id', $anoLectivoId)
            ->where('tipo_exame', 'Recorrência')
            ->first();
        
        $notaExame = null;
        if ($notaExameRecorrencia && $notaExameRecorrencia->nota !== null) {
            $notaExame = $notaExameRecorrencia->nota;
        } elseif ($notaExameNormal && $notaExameNormal->nota !== null) {
            $notaExame = $notaExameNormal->nota;
        }
        
        if ($notaExame !== null && $res->media_frequencia !== null) {
            // Média Final (CF) = MF*0.6 + Exame*0.4
            $mediaFinal = round($res->media_frequencia * 0.6 + $notaExame * 0.4, 2);
            $classificacao = $mediaFinal >= 10 ? 'Aprovado' : 'Reprovado';
            
            $res->update([
                'nota_exame' => $notaExame,
                'media_final' => $mediaFinal,
                'classificacao_final' => $classificacao
            ]);

            // Também atualiza a tabela antiga media_finals para manter compatibilidade com outras views
            MediaFinal::updateOrCreate(
                [
                    'estudante_id' => $estudanteId,
                    'disciplina_id' => $disciplinaId,
                    'ano_lectivo_id' => $anoLectivoId,
                ],
                [
                    'media' => $mediaFinal,
                    'status' => $classificacao,
                ]
            );
        }
    }
}