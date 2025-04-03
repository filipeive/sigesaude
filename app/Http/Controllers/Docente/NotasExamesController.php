<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Docente;
use App\Models\NotaFrequencia;
use App\Models\NotaExame;
use App\Models\MediaFinal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasExamesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        $disciplinas = Disciplina::where('docente_id', $docente->id)->get();
        
        return view('docente.notas_exames.index', compact('disciplinas'));
    }
    
    /* public function show($disciplinaId)
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
            // Obter estudantes admitidos para exame nesta disciplina
            $notasFrequencia = NotaFrequencia::where('disciplina_id', $disciplinaId)
                ->where('status', 'Admitido')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user', 'estudante.matriculas'])
                ->get();
            
            $estudantes = [];
            
            foreach ($notasFrequencia as $notaFrequencia) {
                if (!$notaFrequencia->estudante) {
                    continue;
                }
                
                // Buscar notas de exame do estudante para esta disciplina
                $notaExameNormal = NotaExame::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Normal')
                    ->first();
                    
                $notaExameRecorrencia = NotaExame::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Recorrência')
                    ->first();
                
                // Buscar média final
                $mediaFinal = MediaFinal::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->first();
                
                $estudantes[] = [
                    'estudante' => $notaFrequencia->estudante,
                    'nota_frequencia' => $notaFrequencia->nota,
                    'nota_exame_normal' => $notaExameNormal ? $notaExameNormal->nota : null,
                    'nota_exame_recorrencia' => $notaExameRecorrencia ? $notaExameRecorrencia->nota : null,
                    'media_final' => $mediaFinal ? $mediaFinal->media : null,
                    'status' => $mediaFinal ? $mediaFinal->status : 'Reprovado',
                ];
            }
            
            // Log para debug
            \Illuminate\Support\Facades\Log::info('Estudantes encontrados: ' . count($estudantes));
            
            return view('docente.notas_exames.show', compact('disciplina', 'estudantes', 'anoLectivoAtual'));
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao buscar estudantes para exame: ' . $e->getMessage());
            return redirect()->route('docente.notas_exames.index')
                ->with('error', 'Erro ao carregar estudantes: ' . $e->getMessage());
        }
    } */
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
            // Obter estudantes admitidos para exame nesta disciplina
            $notasFrequenciaAdmitidos = NotaFrequencia::where('disciplina_id', $disciplinaId)
                ->where('status', 'Admitido')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user', 'estudante.matriculas'])
                ->get();
            
            // Obter estudantes dispensados
            $notasFrequenciaDispensados = NotaFrequencia::where('disciplina_id', $disciplinaId)
                ->where('status', 'Dispensado')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user', 'estudante.matriculas'])
                ->get();
                
            // Obter estudantes excluídos
            $notasFrequenciaExcluidos = NotaFrequencia::where('disciplina_id', $disciplinaId)
                ->where('status', 'Excluído')
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->with(['estudante.user', 'estudante.matriculas'])
                ->get();
            
            $estudantes = [];
            $estudantesDispensados = [];
            $estudantesExcluidos = [];
            
            // Processar estudantes admitidos
            foreach ($notasFrequenciaAdmitidos as $notaFrequencia) {
                if (!$notaFrequencia->estudante) {
                    continue;
                }
                
                // Buscar notas de exame do estudante para esta disciplina
                $notaExameNormal = NotaExame::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Normal')
                    ->first();
                    
                $notaExameRecorrencia = NotaExame::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->where('tipo_exame', 'Recorrência')
                    ->first();
                
                // Buscar média final
                $mediaFinal = MediaFinal::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->first();
                
                $estudantes[] = [
                    'estudante' => $notaFrequencia->estudante,
                    'nota_frequencia' => $notaFrequencia->nota,
                    'nota_exame_normal' => $notaExameNormal ? $notaExameNormal->nota : null,
                    'nota_exame_recorrencia' => $notaExameRecorrencia ? $notaExameRecorrencia->nota : null,
                    'media_final' => $mediaFinal ? $mediaFinal->media : null,
                    'status' => $mediaFinal ? $mediaFinal->status : 'Reprovado',
                ];
            }
            
            // Processar estudantes dispensados
            foreach ($notasFrequenciaDispensados as $notaFrequencia) {
                if (!$notaFrequencia->estudante) {
                    continue;
                }
                
                // Buscar média final
                $mediaFinal = MediaFinal::where('estudante_id', $notaFrequencia->estudante_id)
                    ->where('disciplina_id', $disciplinaId)
                    ->where('ano_lectivo_id', $anoLectivoAtual->id)
                    ->first();
                
                $estudantesDispensados[] = [
                    'estudante' => $notaFrequencia->estudante,
                    'nota_frequencia' => $notaFrequencia->nota,
                    'media_final' => $mediaFinal ? $mediaFinal->media : $notaFrequencia->nota,
                    'status' => 'Dispensado',
                ];
            }
            
            // Processar estudantes excluídos
            foreach ($notasFrequenciaExcluidos as $notaFrequencia) {
                if (!$notaFrequencia->estudante) {
                    continue;
                }
                
                $estudantesExcluidos[] = [
                    'estudante' => $notaFrequencia->estudante,
                    'nota_frequencia' => $notaFrequencia->nota,
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
    /**
     * Salvar notas de exame
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function salvar(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Dados recebidos:', [
            'disciplina_id' => $request->disciplina_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'estudante_id' => $request->estudante_id,
            'notas' => $request->notas
        ]);
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
        // Obter nota de frequência
        $notaFrequencia = NotaFrequencia::where('estudante_id', $estudanteId)
            ->where('disciplina_id', $disciplinaId)
            ->where('ano_lectivo_id', $anoLectivoId)
            ->first();
        
        if (!$notaFrequencia) {
            return;
        }
        
        // Se o estudante foi dispensado, já está aprovado
        if ($notaFrequencia->status === 'Dispensado') {
            MediaFinal::updateOrCreate(
                [
                    'estudante_id' => $estudanteId,
                    'disciplina_id' => $disciplinaId,
                    'ano_lectivo_id' => $anoLectivoId,
                ],
                [
                    'media' => $notaFrequencia->nota,
                    'status' => 'Aprovado',
                ]
            );
            return;
        }
        
        // Se o estudante foi excluído, já está reprovado
        if ($notaFrequencia->status === 'Excluído') {
            MediaFinal::updateOrCreate(
                [
                    'estudante_id' => $estudanteId,
                    'disciplina_id' => $disciplinaId,
                    'ano_lectivo_id' => $anoLectivoId,
                ],
                [
                    'media' => $notaFrequencia->nota,
                    'status' => 'Reprovado',
                ]
            );
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
        
        $notaExame = 0;
        
        if ($notaExameRecorrencia) {
            $notaExame = $notaExameRecorrencia->nota;
        } elseif ($notaExameNormal) {
            $notaExame = $notaExameNormal->nota;
        }
        
        // Calcular média final (50% frequência + 50% exame)
        $mediaFinal = ($notaFrequencia->nota + $notaExame) / 2;
        
        // Determinar status (Aprovado ou Reprovado)
        $status = $mediaFinal >= 10 ? 'Aprovado' : 'Reprovado';
        
        // Salvar ou atualizar a média final
        MediaFinal::updateOrCreate(
            [
                'estudante_id' => $estudanteId,
                'disciplina_id' => $disciplinaId,
                'ano_lectivo_id' => $anoLectivoId,
            ],
            [
                'media' => $mediaFinal,
                'status' => $status,
            ]
        );
    }
}