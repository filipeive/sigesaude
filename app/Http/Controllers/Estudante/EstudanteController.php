<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\NotaExame;
use App\Models\NotaDetalhada;
use App\Models\MediaFinal;
use App\Models\Pagamento;
use App\Models\User;
use App\Models\Curso;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;

class EstudanteController extends Controller
{
    /**
     * Check if student exists and return the student or redirect
     */
    private function getEstudanteOrRedirect()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        
        if (!$estudante) {
            // Redirect to a route where the user can create their student profile
            return redirect()->route('estudante.create.profile')
                ->with('error', 'Perfil de estudante não encontrado. Por favor, complete seu cadastro.');
        }
        
        return $estudante;
    }

    /* /**
     * Dashboard principal do estudante
     */
   /*  public function index()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        // If the function returned a redirect response, return it
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        $totalDisciplinas = Matricula::where('estudante_id', $estudante->id)->count();
        
        $ultimosPagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->take(5)
            ->get();
            
        // Obter a última notificação (você precisará criar esta tabela se não existir)
        $ultimasNotificacoes = []; // Substitua com a consulta apropriada quando criar a tabela de notificações
        
        return view('estudante.dashboard', compact('estudante', 'totalDisciplinas', 'ultimosPagamentos', 'ultimasNotificacoes'));
    }
 */
    /**
     * Exibe as matrículas do estudante
     */
    /** 
     * Dashboard principal do estudante */
    /**
 * Dashboard principal do estudante
 */
    public function index()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        // If the function returned a redirect response, return it
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Dados básicos
        $totalDisciplinas = Matricula::where('estudante_id', $estudante->id)->count();
        
        // Últimos pagamentos
        $ultimosPagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->take(5)
            ->get();
        
        // Calcular progresso do curso usando inscrições confirmadas e notas
        // Primeiro, obter todas as inscrições confirmadas
        $inscricoesConfirmadas = \DB::table('inscricoes')
            ->where('estudante_id', $estudante->id)
            ->where('status', 'Confirmada')
            ->count();
        
        // Total de disciplinas do curso
        $totalDisciplinasCurso = Disciplina::where('curso_id', $estudante->curso_id)->count();
        
        // Calcular progresso baseado nas inscrições confirmadas
        $progressoCurso = $totalDisciplinasCurso > 0 
            ? round(($inscricoesConfirmadas / $totalDisciplinasCurso) * 100) 
            : 0;

        // Disciplinas atuais com notas (usando join com inscricoes)
        $disciplinasAtuais = \DB::table('inscricoes')
            ->join('disciplinas', function ($join) use ($estudante) {
                $join->on('disciplinas.curso_id', '=', \DB::raw($estudante->curso_id));
            })
            ->where('inscricoes.estudante_id', $estudante->id)
            ->where('inscricoes.status', 'Confirmada')
            ->where('inscricoes.ano_lectivo_id', $estudante->ano_lectivo_id)
            ->select('disciplinas.nome', 'inscricoes.id as inscricao_id')
            ->take(3)
            ->get()
            ->map(function ($item) {
                // Aqui você pode buscar a nota média para esta disciplina
                // Como não temos acesso direto à tabela notas_frequencia, vamos simular
                $mediaNotas = rand(50, 95); // Simulação - substitua por dados reais
                
                return [
                    'nome' => $item->nome,
                    'progresso' => $mediaNotas,
                    'cor' => $this->getProgressColor($mediaNotas)
                ];
            });

        // Próximos prazos
        $proximosPrazos = [
            [
                'titulo' => 'Pagamento de Propina',
                'data_limite' => now()->addDays(2),
                'tipo' => 'pagamento',
                'status' => 'pendente'
            ],
            [
                'titulo' => 'Entrega de Trabalho',
                'data_limite' => now()->addDays(5),
                'tipo' => 'trabalho',
                'status' => 'pendente'
            ],
            [
                'titulo' => 'Inscrição para Exames',
                'data_limite' => now()->addWeek(),
                'tipo' => 'inscricao',
                'status' => 'pendente'
            ]
        ];

        // Eventos do calendário
        $eventosCalendario = $this->getEventosCalendario($estudante);

        // Notificações
        $ultimasNotificacoes = $this->getUltimasNotificacoes($estudante);

        // Estatísticas gerais (adaptadas para usar inscrições)
        $estatisticas = [
            'media_geral' => 75, // Valor simulado - substitua por dados reais
            'disciplinas_concluidas' => $inscricoesConfirmadas,
            'total_disciplinas' => $totalDisciplinasCurso,
            'pagamentos_pendentes' => Pagamento::where('estudante_id', $estudante->id)
                ->where('status', 'pendente')
                ->count()
        ];
        
        // Verificar se existe um ano letivo ativo
        $anoLetivoAtual = AnoLectivo::where('status', 'ativo')->first();
        if (!$anoLetivoAtual) {
            return view('estudante.dashboard', [
                'progressoCurso' => 0,
                'notasFrequencia' => collect([])
            ])->with('warning', 'Nenhum ano letivo ativo encontrado.');
        }

        // Buscar disciplinas e notas do estudante - CORRIGIDO: usando $estudante->id em vez de $estudanteId
        $disciplinas = MediaFinal::with(['disciplina'])
            ->where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->get();

        // Calcular progresso geral do curso - CORRIGIDO: usando $estudante->id em vez de $estudanteId
        $totalDisciplinas = Disciplina::count();
        $disciplinasAprovadas = MediaFinal::where('estudante_id', $estudante->id)
            ->where('status', 'Aprovado')
            ->count();
        
        $progressoCurso = $totalDisciplinas > 0 
            ? round(($disciplinasAprovadas / $totalDisciplinas) * 100) 
            : 0;

        // Buscar notas de frequência das disciplinas atuais - CORRIGIDO: usando $estudante->id em vez de $estudanteId
        $notasFrequencia = NotaFrequencia::with(['disciplina', 'notasDetalhadas'])
            ->where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->get();

        // Transformar os dados para exibição
        $notasFrequenciaFormatadas = $notasFrequencia->map(function($nota) {
            // Verificar se a disciplina existe
            if (!$nota->disciplina) {
                return null;
            }
            
            // Calcular média das notas detalhadas
            $notasDetalhadas = $nota->notasDetalhadas->pluck('nota')->toArray();
            $media = count($notasDetalhadas) > 0 ? array_sum($notasDetalhadas) / count($notasDetalhadas) : 0;
            
            return [
                'disciplina' => $nota->disciplina->nome,
                'media' => round($media, 2),
                'cor' => $this->getCorProgresso($media)
            ];
        })->filter(); // Remover valores nulos

        // Variável para pagamentos pendentes
        $pagamentosPendentes = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')
            ->count();

        return view('estudante.dashboard', compact(
            'estudante',
            'totalDisciplinas',
            'ultimosPagamentos',
            'progressoCurso',
            'disciplinasAtuais',
            'proximosPrazos',
            'eventosCalendario',
            'ultimasNotificacoes',
            'estatisticas',
            'notasFrequenciaFormatadas', // Usando a versão formatada
            'disciplinasAprovadas',
            'pagamentosPendentes'
        ));
    }
    private function getCorProgresso($media)
    {
        if ($media >= 14) return 'success';
        if ($media >= 10) return 'primary';
        if ($media >= 8) return 'warning';
        return 'danger';
    }
    /**
     * Retorna a cor do progresso baseado na nota
     */
    private function getProgressColor($nota)
    {
        if ($nota >= 75) return 'bg-success';
        if ($nota >= 50) return 'bg-primary';
        if ($nota >= 35) return 'bg-warning';
        return 'bg-danger';
    }

    /**
     * Obtém os eventos do calendário
     */
    private function getEventosCalendario($estudante)
    {
        $eventos = [];

        // Adicionar datas de pagamento
        $pagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')
            ->get();

        foreach ($pagamentos as $pagamento) {
            $eventos[] = [
                'title' => 'Pagamento: ' . ($pagamento->descricao ?? 'Propina'),
                'start' => $pagamento->data_vencimento ?? $pagamento->data_pagamento,
                'backgroundColor' => '#17a2b8',
                'borderColor' => '#17a2b8'
            ];
        }

        // Adicionar inscrições
        $inscricoes = \DB::table('inscricoes')
            ->where('estudante_id', $estudante->id)
            ->where('data_inscricao', '>=', now()->subMonths(1))
            ->get();

        foreach ($inscricoes as $inscricao) {
            $eventos[] = [
                'title' => 'Inscrição: Semestre ' . $inscricao->semestre,
                'start' => $inscricao->data_inscricao,
                'backgroundColor' => '#dc3545',
                'borderColor' => '#dc3545'
            ];
        }

        // Adicionar outros eventos importantes do ano letivo
        $anoLetivo = AnoLectivo::find($estudante->ano_lectivo_id);
        if ($anoLetivo) {
            // Verificar se as colunas existem antes de usá-las
            if (isset($anoLetivo->data_inicio)) {
                $eventos[] = [
                    'title' => 'Início das Aulas',
                    'start' => $anoLetivo->data_inicio,
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745'
                ];
            }
            
            if (isset($anoLetivo->data_fim)) {
                $eventos[] = [
                    'title' => 'Fim das Aulas',
                    'start' => $anoLetivo->data_fim,
                    'backgroundColor' => '#ffc107',
                    'borderColor' => '#ffc107'
                ];
            }
        }

        return $eventos;
    }


    public function notificacoes(Request $request)
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }

        $query = Notificacao::where('user_id', Auth::id());

        // Aplicar filtros
        if ($request->has('tipo') && $request->tipo !== 'todas') {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('lida')) {
            $query->where('lida', $request->lida === 'true');
        }

        // Buscar notificações e agrupar por data
        $notificacoes = $query->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($notificacao) {
                return $notificacao->created_at->format('Y-m-d');
            });

        return view('estudante.notificacoes', compact('notificacoes'));
    }

    /**
     * Marca uma notificação como lida
     */
    public function marcarComoLida($id)
    {
        $notificacao = Notificacao::where('user_id', auth()->id())
            ->findOrFail($id);

        $notificacao->update(['lida' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida!'
        ]);
    }

    public function marcarTodasComoLidas()
    {
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas!'
        ]);
    }

    /**
     * Criar uma nova notificação (método helper)
     */
    public static function criarNotificacao($userId, $dados)
    {
        return Notificacao::create([
            'user_id' => $userId,
            'titulo' => $dados['titulo'],
            'mensagem' => $dados['mensagem'],
            'tipo' => $dados['tipo'] ?? 'geral',
            'icone' => $dados['icone'] ?? null,
            'cor' => $dados['cor'] ?? null,
            'link' => $dados['link'] ?? null,
        ]);
    }
    private function getUltimasNotificacoes($estudante)
    {
        return Notificacao::where('user_id', $estudante->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($notificacao) {
                return [
                    'id' => $notificacao->id,
                    'titulo' => $notificacao->titulo,
                    'mensagem' => $notificacao->mensagem,
                    'data' => $notificacao->created_at,
                    'tipo' => $notificacao->tipo,
                    'icone' => $notificacao->icone_class,
                    'cor' => $notificacao->cor_class,
                    'lida' => $notificacao->lida,
                    'link' => $notificacao->link
                ];
            });
    }
    /**
     * Exibe os pagamentos do estudante
     */
    public function pagamentos()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        $pagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->get();
            
        $totalPago = $pagamentos->sum('valor');
        
        return view('estudante.pagamentos', compact('estudante', 'pagamentos', 'totalPago'));
    }

    /**
     * Exibe os relatórios do estudante
     */
    public function relatorios()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Aqui você pode reunir dados para relatórios acadêmicos
        $dadosAcademicos = [
            'progresso' => 0, // Calcular baseado nas disciplinas concluídas vs. total
            'mediasGerais' => [], // Adicionar médias gerais por semestre ou ano letivo
        ];
        
        return view('estudante.relatorios', compact('estudante', 'dadosAcademicos'));
    }

    public function configuracoes()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        return view('estudante.configuracoes', compact('estudante'));
    }
    
    /**
     * Mostra o formulário para criar um perfil de estudante
     * (Você precisará criar esta view e rota)
     */
    public function createProfile()
    {
        // Verifique se o usuário já tem um perfil
        $existingEstudante = Estudante::where('user_id', Auth::id())->first();
        
        if ($existingEstudante) {
            return redirect()->route('estudante.dashboard')
                ->with('info', 'Você já possui um perfil de estudante.');
        }
        
        $cursos = Curso::all();
        $anosLectivos = AnoLectivo::all();
        
        // Generate matricula for the current year
        $matricula = $this->generateMatricula(date('Y'));
        
        return view('estudante.create-profile', compact('cursos', 'anosLectivos', 'matricula'));
    }
    
    /**
     * Salva o novo perfil de estudante
     */
    public function generateMatricula($anoIngresso)
    {
        // Find the last matricula for the given year
        $lastMatricula = Estudante::where('ano_ingresso', $anoIngresso)
            ->orderBy('matricula', 'desc')
            ->first();

        if ($lastMatricula) {
            // Extract the numeric part and increment
            $lastNumber = intval(substr($lastMatricula->matricula, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Start with 001 if no previous matricula exists for this year
            $newNumber = '001';
        }

        // Format: 000.01.YYYY
        return sprintf('%03d.01.%d', $newNumber, $anoIngresso);
    }

    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'contato_emergencia' => 'nullable|string|max:255',
        ]);

        // Generate unique matricula
        $matricula = $this->generateMatricula($validated['ano_ingresso']);

        // Check if the generated matricula is unique
        while (Estudante::where('matricula', $matricula)->exists()) {
            $matricula = $this->generateMatricula($validated['ano_ingresso']);
        }

        $estudante = new Estudante();
        $estudante->user_id = Auth::id();
        $estudante->matricula = $matricula;
        $estudante->curso_id = $validated['curso_id'];
        $estudante->ano_lectivo_id = $validated['ano_lectivo_id'];
        $estudante->data_nascimento = $validated['data_nascimento'];
        $estudante->genero = $validated['genero'];
        $estudante->ano_ingresso = $validated['ano_ingresso'];
        $estudante->turno = $validated['turno'];
        $estudante->status = 'Ativo';
        $estudante->contato_emergencia = $validated['contato_emergencia'];
        $estudante->save();

        return redirect()->route('estudante.dashboard')
            ->with('success', 'Perfil de estudante criado com sucesso!');
    }
}