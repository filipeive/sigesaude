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
        
        // Obter estudantes admitidos para exame nesta disciplina
        $estudantes = NotaFrequencia::where('disciplina_id', $disciplinaId)
            ->where('status', 'Admitido')
            ->with(['estudante.user', 'notasExame'])
            ->get()
            ->map(function ($notaFrequencia) {
                return [
                    'estudante' => $notaFrequencia->estudante,
                    'nota_frequencia' => $notaFrequencia->nota,
                    'notas_exame' => $notaFrequencia->estudante->notasExame
                        ->where('disciplina_id', $notaFrequencia->disciplina_id)
                        ->first()
                ];
            });
        
        return view('docente.notas_exames.show', compact('disciplina', 'estudantes'));
    }
    
    public function salvar(Request $request)
    {
        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'estudante_id' => 'required|array',
            'notas' => 'required|array',
            'ano_lectivo_id' => 'required|exists:ano_lectivos,id',
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