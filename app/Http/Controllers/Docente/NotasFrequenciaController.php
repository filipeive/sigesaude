<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InscricaoDisciplina;
use App\Models\Inscricao;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Docente;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\NotaDetalhada;
use App\Models\AnoLectivo;
use App\Models\MediaFinal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotasFrequenciaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        // Verificar se o docente existe
        if (!$docente) {
            return redirect()->route('home')->with('error', 'Perfil de docente não encontrado.');
        }
        
        // Obter disciplinas com contagem de estudantes
        $disciplinas = Disciplina::where('docente_id', $docente->id)
            ->withCount(['inscricaoDisciplinas as estudantes_count' => function ($query) {
                // Filtrar por ano letivo ativo
                $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
                if ($anoLectivoAtual) {
                    $query->whereHas('inscricao', function ($q) use ($anoLectivoAtual) {
                        $q->where('ano_lectivo_id', $anoLectivoAtual->id);
                    });
                }
            }])
            ->with(['curso', 'nivel'])
            ->get();
        
        // Obter dados para filtros
        $cursos = \App\Models\Curso::all();
        $niveis = \App\Models\Nivel::all();
        
        return view('docente.notas_frequencia.index', compact('disciplinas', 'cursos', 'niveis'));
    }
    
    public function show($disciplinaId)
    {
        // Obter a disciplina
        $disciplina = Disciplina::with(['curso', 'nivel'])->findOrFail($disciplinaId);
        
        // Verificar se o docente está autorizado a ver esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }
        
        // Obter ano letivo atual
        $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
        
        if (!$anoLectivoAtual) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('warning', 'Não há ano letivo ativo no momento.');
        }
        
        try {
            // Buscar estudantes e notas utilizando um método confiável
            $estudantes = $this->getEstudantesComNotas($disciplina, $anoLectivoAtual);
            
            return view('docente.notas_frequencia.show', compact('disciplina', 'estudantes', 'anoLectivoAtual', 'docente'));
            
        } catch (\Exception $e) {
            Log::error("Erro ao buscar estudantes: " . $e->getMessage());
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Erro ao carregar estudantes: ' . $e->getMessage());
        }
    }
    private function getEstudantesComNotas($disciplina, $anoLectivoAtual)
    {
        // Primeiro, buscar todos os estudantes inscritos na disciplina para o ano letivo atual
        $inscricoes = InscricaoDisciplina::where('disciplina_id', $disciplina->id)
            ->whereHas('inscricao', function ($query) use ($anoLectivoAtual) {
                $query->where('ano_lectivo_id', $anoLectivoAtual->id);
            })
            ->with(['inscricao.estudante.user', 'inscricao.estudante.matriculas'])
            ->get();
        
        $estudantes = [];
        
        foreach ($inscricoes as $inscricao) {
            if (!$inscricao->inscricao || !$inscricao->inscricao->estudante) {
                continue;
            }
            
            $estudante = $inscricao->inscricao->estudante;
            
            // Buscar notas de frequência do estudante para esta disciplina
            $notaFrequencia = NotaFrequencia::with('notasDetalhadas')
                ->where('estudante_id', $estudante->id)
                ->where('disciplina_id', $disciplina->id)
                ->where('ano_lectivo_id', $anoLectivoAtual->id)
                ->first();
            
            // Organizar notas detalhadas em um formato mais fácil de usar na view
            $notasDetalhadas = collect();
            
            if ($notaFrequencia) {
                foreach ($notaFrequencia->notasDetalhadas as $nota) {
                    $notasDetalhadas->push((object)[
                        'tipo' => $nota->tipo,
                        'nota' => $nota->nota
                    ]);
                }
            }
            
            // Adicionar dados do estudante com suas notas
            $estudantes[] = [
                'estudante' => (object)[
                    'id' => $estudante->id,
                    'user' => $estudante->user,
                    'matricula' => $estudante->matriculas->first() ? $estudante->matriculas->first()->matricula : 'N/A'
                ],
                'notas_frequencia' => $notaFrequencia,
                'notas_detalhadas' => $notasDetalhadas
            ];
        }
        
        return $estudantes;
    } 
    public function store(Request $request, $disciplinaId)
    {
        Log::info('Método store chamado para disciplina: ' . $disciplinaId);
        Log::info('Dados recebidos: ', $request->all());
        $request->validate([
            'notas' => 'required|array',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            $disciplina = Disciplina::findOrFail($disciplinaId);
            $anoLectivoId = $request->ano_lectivo_id;
            
            // Verificar se o docente está autorizado
            $user = Auth::user();
            $docente = Docente::where('user_id', $user->id)->first();
            
            if ($disciplina->docente_id != $docente->id) {
                return redirect()->route('docente.notas_frequencia.index')
                    ->with('error', 'Você não está autorizado a modificar notas desta disciplina.');
            }
            
            // Log para debug
            Log::info('Processando notas para disciplina: ' . $disciplinaId);
            Log::info('Dados recebidos: ', $request->all());
            
            foreach ($request->notas as $estudanteId => $notasEstudante) {
                // Log para debug
                Log::info('Processando estudante: ' . $estudanteId);
                Log::info('Notas: ', $notasEstudante);
                
                // Obter ou criar o registro de nota de frequência
                $notaFrequencia = NotaFrequencia::firstOrCreate([
                    'estudante_id' => $estudanteId,
                    'disciplina_id' => $disciplinaId,
                    'ano_lectivo_id' => $anoLectivoId,
                ]);
                
                // Array para armazenar valores para cálculo final
                $valoresNotas = [
                    'Teste 1' => null,
                    'Teste 2' => null,
                    'Teste 3' => null,
                    'Trabalho 1' => null,
                    'Trabalho 2' => null,
                    'Trabalho 3' => null
                ];
                
                // Salvar as notas detalhadas
                foreach ($notasEstudante as $tipo => $valor) {
                    if (!empty($valor) || $valor === '0') {
                        $notaDetalhada = NotaDetalhada::updateOrCreate(
                            [
                                'notas_frequencia_id' => $notaFrequencia->id,
                                'tipo' => $tipo,
                            ],
                            [
                                'nota' => $valor,
                            ]
                        );
                        
                        $valoresNotas[$tipo] = $valor;
                    }
                }
                
                // Calcular média de testes (70%)
                $testes = array_filter([
                    $valoresNotas['Teste 1'],
                    $valoresNotas['Teste 2'],
                    $valoresNotas['Teste 3']
                ], function($value) {
                    return $value !== null && $value !== '';
                });
                
                $mediaTestes = count($testes) > 0 ? array_sum($testes) / count($testes) : 0;
                
                // Calcular média de trabalhos (30%)
                $trabalhos = array_filter([
                    $valoresNotas['Trabalho 1'],
                    $valoresNotas['Trabalho 2'],
                    $valoresNotas['Trabalho 3']
                ], function($value) {
                    return $value !== null && $value !== '';
                });
                
                $mediaTrabalhos = count($trabalhos) > 0 ? array_sum($trabalhos) / count($trabalhos) : 0;
                
                // Calcular nota final (70% testes + 30% trabalhos)
                $notaFinal = ($mediaTestes * 0.7) + ($mediaTrabalhos * 0.3);
                $notaFinal = round($notaFinal, 1); // Arredondar para uma casa decimal
                
                // Atualizar a nota final
                $notaFrequencia->nota = $notaFinal;
                
                // Definir status baseado na nota final
                if ($notaFinal >= 14) {
                    $notaFrequencia->status = 'Dispensado';
                    
                    // Atualizar a média final para estudantes dispensados
                    MediaFinal::updateOrCreate(
                        [
                            'estudante_id' => $estudanteId,
                            'disciplina_id' => $disciplinaId,
                            'ano_lectivo_id' => $anoLectivoId,
                        ],
                        [
                            'media' => $notaFinal,
                            'status' => 'Dispensado'
                        ]
                    );
                } elseif ($notaFinal >= 10) {
                    $notaFrequencia->status = 'Admitido';
                } else {
                    $notaFrequencia->status = 'Excluído';
                    
                    // Atualizar a média final para estudantes excluídos
                    MediaFinal::updateOrCreate(
                        [
                            'estudante_id' => $estudanteId,
                            'disciplina_id' => $disciplinaId,
                            'ano_lectivo_id' => $anoLectivoId,
                        ],
                        [
                            'media' => $notaFinal,
                            'status' => 'Reprovado'
                        ]
                    );
                }
                
                // Salvar a nota de frequência
                $notaFrequencia->save();
            }
            
            DB::commit();
            
            return redirect()->route('docente.notas_frequencia.show', $disciplinaId)
                ->with('success', 'Notas de frequência salvas com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar notas de frequência: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('docente.notas_frequencia.show', $disciplinaId)
                ->with('error', 'Erro ao salvar notas: ' . $e->getMessage());
        }
    }
    /**
     * Mostrar página para importar notas de frequência
     */
    public function importForm($disciplinaId)
    {
        $disciplina = Disciplina::with(['curso', 'nivel'])->findOrFail($disciplinaId);
        
        // Verificar autorização
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }
        
        // Obter ano letivo atual
        $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
        
        return view('docente.notas_frequencia.import', compact('disciplina', 'anoLectivoAtual'));
    }
    
    /**
     * Processar importação de notas de frequência
     */
    public function import(Request $request, $disciplinaId)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls,csv',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
        ]);
        
        try {
            // Obter ano letivo e disciplina
            $anoLectivoId = $request->ano_lectivo_id;
            $disciplina = Disciplina::findOrFail($disciplinaId);
            
            // Lógica para importar o arquivo (pode usar pacotes como Maatwebsite/Excel)
            // Aqui você implementaria a leitura do arquivo e processamento dos dados
            
            // Exemplo simplificado:
            // $importacao = new NotasFrequenciaImport($disciplinaId, $anoLectivoId);
            // Excel::import($importacao, $request->file('arquivo'));
            
            // Contar itens processados
            $processados = 0; // Substituir pelo valor real quando implementar
            
            return redirect()->route('docente.notas_frequencia.show', $disciplinaId)
                ->with('success', 'Arquivo importado com sucesso! Processados: ' . $processados . ' registros.');
        } catch (\Exception $e) {
            Log::error('Erro ao importar notas: ' . $e->getMessage());
            return redirect()->route('docente.notas_frequencia.import', $disciplinaId)
                ->with('error', 'Erro ao importar arquivo: ' . $e->getMessage());
        }
    }
    
    /**
     * Exportar notas de frequência
     */
    public function export($disciplinaId)
    {
        try {
            $disciplina = Disciplina::findOrFail($disciplinaId);
            
            // Verificar autorização
            $user = Auth::user();
            $docente = Docente::where('user_id', $user->id)->first();
            
            if ($disciplina->docente_id != $docente->id) {
                return redirect()->route('docente.notas_frequencia.index')
                    ->with('error', 'Você não está autorizado a exportar dados desta disciplina.');
            }
            
            // Obter ano letivo atual
            $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
            
            if (!$anoLectivoAtual) {
                return redirect()->route('docente.notas_frequencia.index')
                    ->with('warning', 'Não há ano letivo ativo no momento.');
            }
            
            // Lógica para exportar dados (pode usar pacotes como Maatwebsite/Excel)
            // Exemplo simplificado:
            // return Excel::download(new NotasFrequenciaExport($disciplinaId, $anoLectivoAtual->id), 
            //     'notas_frequencia_' . $disciplina->nome . '.xlsx');
            
            // Retorno temporário
            return redirect()->route('docente.notas_frequencia.show', $disciplinaId)
                ->with('info', 'Funcionalidade de exportação em desenvolvimento.');
        } catch (\Exception $e) {
            Log::error('Erro ao exportar notas: ' . $e->getMessage());
            return redirect()->route('docente.notas_frequencia.show', $disciplinaId)
                ->with('error', 'Erro ao exportar notas: ' . $e->getMessage());
        }
    }
    
    /**
     * Gerar relatório de desempenho da turma
     */
    public function relatorio($disciplinaId)
    {
        try {
            $disciplina = Disciplina::with(['curso', 'nivel'])->findOrFail($disciplinaId);
            
            // Verificar autorização
            $user = Auth::user();
            $docente = Docente::where('user_id', $user->id)->first();
            
            if ($disciplina->docente_id != $docente->id) {
                return redirect()->route('docente.notas_frequencia.index')
                    ->with('error', 'Você não está autorizado a acessar esta disciplina.');
            }
            
            // Obter ano letivo atual
            $anoLectivoAtual = AnoLectivo::where('status', 'Ativo')->first();
            
            if (!$anoLectivoAtual) {
                return redirect()->route('docente.notas_frequencia.index')
                    ->with('warning', 'Não há ano letivo ativo no momento.');
            }
            
            // Obter estatísticas da disciplina
            $estatisticas = $this->getEstatisticasDisciplina($disciplina, $anoLectivoAtual);
            
            // Obter estudantes com suas notas
            $estudantes = $this->getEstudantesComNotas($disciplina, $anoLectivoAtual);
            
            return view('docente.notas_frequencia.relatorio', 
                compact('disciplina', 'anoLectivoAtual', 'estatisticas', 'estudantes'));
        } catch (\Exception $e) {
            Log::error('Erro ao gerar relatório: ' . $e->getMessage());
            return redirect()->route('docente.notas_frequencia.index')
                ->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }
    
    /**
     * Obter estatísticas da disciplina para o relatório
     */
    private function getEstatisticasDisciplina($disciplina, $anoLectivoAtual)
    {
        // Buscar todas as notas de frequência para a disciplina e ano letivo atual
        $notas = NotaFrequencia::where('disciplina_id', $disciplina->id)
            ->where('ano_lectivo_id', $anoLectivoAtual->id)
            ->get();
        
        $total = $notas->count();
        $aprovados = $notas->where('status', 'Admitido')->count();
        $reprovados = $notas->where('status', 'Excluído')->count();
        $dispensados = $notas->where('status', 'Dispensado')->count();
        
        // Calcular médias
        $todasNotas = $notas->pluck('nota')->filter();
        $mediaGeral = $todasNotas->count() > 0 ? $todasNotas->avg() : 0;
        $mediaMaisAlta = $todasNotas->count() > 0 ? $todasNotas->max() : 0;
        $mediaMaisBaixa = $todasNotas->count() > 0 ? $todasNotas->min() : 0;
        
        // Calcular distribuição por faixas
        $faixas = [
            '0-4' => $notas->filter(function($n) { return $n->nota >= 0 && $n->nota <= 4; })->count(),
            '5-9' => $notas->filter(function($n) { return $n->nota >= 5 && $n->nota <= 9; })->count(),
            '10-13' => $notas->filter(function($n) { return $n->nota >= 10 && $n->nota <= 13; })->count(),
            '14-16' => $notas->filter(function($n) { return $n->nota >= 14 && $n->nota <= 16; })->count(),
            '17-20' => $notas->filter(function($n) { return $n->nota >= 17 && $n->nota <= 20; })->count(),
        ];
        
        return [
            'total' => $total,
            'aprovados' => $aprovados,
            'reprovados' => $reprovados,
            'dispensados' => $dispensados,
            'taxa_aprovacao' => $total > 0 ? round(($aprovados / $total) * 100, 1) : 0,
            'media_geral' => round($mediaGeral, 1),
            'media_mais_alta' => round($mediaMaisAlta, 1),
            'media_mais_baixa' => round($mediaMaisBaixa, 1),
            'distribuicao' => $faixas
        ];
    }
}