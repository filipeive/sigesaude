<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InscricaoDisciplina;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\NotaDetalhada;
use App\Models\MediaFinal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasFrequenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        // Obter disciplinas com contagem de matrículas
        $disciplinas = Disciplina::where('docente_id', $docente->id)
            ->with(['curso', 'nivel'])
            ->withCount('matriculas')
            ->get();
        
        // Obter cursos e níveis para filtros
        $cursos = \App\Models\Curso::all();
        $niveis = \App\Models\Nivel::all();
        
        return view('docente.notas_frequencia.index', compact('disciplinas', 'cursos', 'niveis'));
    }
    
    public function show($disciplinaId)
    {
        $disciplina = Disciplina::with(['curso', 'nivel'])->findOrFail($disciplinaId);
        
        // Verificar se o docente está autorizado
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }
        
        // Obter o ano letivo atual
        $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
        
        if (!$anoLectivoAtual) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('warning', 'Não há ano letivo ativo definido no sistema.');
        }
        
        // Buscar estudantes através das inscrições na disciplina
        $inscricoes = $disciplina->inscricaoDisciplinas()
            ->with(['inscricao.estudante.user'])
            ->get();
        
        // Preparar array de estudantes com suas notas
        $estudantes = [];
        
        foreach ($inscricoes as $inscricao) {
            if ($inscricao->inscricao && $inscricao->inscricao->estudante && $inscricao->inscricao->estudante->user) {
                // Buscar nota de frequência existente
                $notaFrequencia = NotaFrequencia::where([
                    'estudante_id' => $inscricao->inscricao->estudante_id,
                    'disciplina_id' => $disciplinaId,
                    'ano_lectivo_id' => $anoLectivoAtual->id
                ])->with('notasDetalhadas')->first();
                
                // Adicionar ao array de estudantes
                $estudantes[] = [
                    'estudante' => $inscricao->inscricao->estudante,
                    'notas_frequencia' => $notaFrequencia,
                    'notas_detalhadas' => $notaFrequencia ? $notaFrequencia->notasDetalhadas : collect([])
                ];
            }
        }
        
        return view('docente.notas_frequencia.show', compact('disciplina', 'estudantes', 'anoLectivoAtual'));
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
            foreach ($request->estudante_id as $key => $estudanteId) {
                // Verificar se já existe registro de nota de frequência
                $notaFrequencia = NotaFrequencia::firstOrNew([
                    'estudante_id' => $estudanteId,
                    'disciplina_id' => $request->disciplina_id,
                    'ano_lectivo_id' => $request->ano_lectivo_id,
                ]);
                
                // Salvar ou atualizar a nota de frequência
                $notaFrequencia->save();
                
                // Salvar notas detalhadas
                foreach ($request->notas[$estudanteId] as $tipo => $nota) {
                    if ($nota !== null) {
                        NotaDetalhada::updateOrCreate(
                            [
                                'notas_frequencia_id' => $notaFrequencia->id,
                                'tipo' => $tipo,
                            ],
                            [
                                'nota' => $nota,
                            ]
                        );
                    }
                }
                
                // Calcular a nota final de frequência
                $this->calcularNotaFrequencia($notaFrequencia->id);
                
                // Verificar status (Admitido, Excluído ou Dispensado)
                $this->atualizarStatusFrequencia($notaFrequencia);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Notas salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao salvar notas: ' . $e->getMessage());
        }
    }
    
    private function calcularNotaFrequencia($notaFrequenciaId)
    {
        $notaFrequencia = NotaFrequencia::findOrFail($notaFrequenciaId);
        $notasDetalhadas = $notaFrequencia->notasDetalhadas;
        
        $somaTestes = 0;
        $contadorTestes = 0;
        $somaTrabalhos = 0;
        $contadorTrabalhos = 0;
        
        foreach ($notasDetalhadas as $notaDetalhada) {
            if (strpos($notaDetalhada->tipo, 'Teste') !== false) {
                $somaTestes += $notaDetalhada->nota;
                $contadorTestes++;
            } elseif (strpos($notaDetalhada->tipo, 'Trabalho') !== false) {
                $somaTrabalhos += $notaDetalhada->nota;
                $contadorTrabalhos++;
            }
        }
        
        $mediaTestes = $contadorTestes > 0 ? $somaTestes / $contadorTestes : 0;
        $mediaTrabalhos = $contadorTrabalhos > 0 ? $somaTrabalhos / $contadorTrabalhos : 0;
        
        // Cálculo da nota final (60% testes + 40% trabalhos)
        $notaFinal = ($mediaTestes * 0.6) + ($mediaTrabalhos * 0.4);
        
        // Atualizar a nota de frequência
        $notaFrequencia->nota = $notaFinal;
        $notaFrequencia->save();
        
        return $notaFinal;
    }
    
    private function atualizarStatusFrequencia($notaFrequencia)
    {
        // Regra: Se nota >= 10, status = Admitido
        // Se nota < 10, status = Excluído
        if ($notaFrequencia->nota >= 10) {
            $notaFrequencia->status = 'Admitido';
        } else {
            $notaFrequencia->status = 'Excluído';
        }
        
        $notaFrequencia->save();
    }
}