<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\NotaExame;
use App\Models\Pagamento;
use App\Models\AnoLectivo;
use App\Models\Turma;
use App\Models\Notificacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstudanteController extends Controller
{
    private function getEstudanteOrRedirect()
    {
        $estudante = Estudante::where('user_id', Auth::id())
            ->with(['turma', 'anoLectivo'])
            ->first();

        if (!$estudante) {
            return redirect()->route('estudante.create.profile')
                ->with('error', 'Perfil de estudante não encontrado. Complete o cadastro na secretaria.');
        }

        return $estudante;
    }

    /**
     * Dashboard principal do estudante — ensino secundário
     * Matrícula anual na classe, sem inscrição por disciplina
     */
    public function index()
    {
        $estudante = $this->getEstudanteOrRedirect();

        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }

        // Matrícula anual actual (mais recente)
        $matriculaAtual = Matricula::where('estudante_id', $estudante->id)
            ->with(['turma', 'anoLectivo'])
            ->orderByDesc('created_at')
            ->first();

        // Histórico de matrículas (anos anteriores)
        $totalMatriculas = Matricula::where('estudante_id', $estudante->id)->count();

        // Progresso baseado no ano da turma (ex: 10º ano = 10/12 → ~83%)
        $progressoCurso = 0;
        if ($estudante->turma && $estudante->turma->ano_serie) {
            $progressoCurso = round(($estudante->turma->ano_serie / 12) * 100);
        }

        // Próximos prazos de pagamento
        $proximosPrazos = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')
            ->whereNotNull('data_vencimento')
            ->orderBy('data_vencimento', 'asc')
            ->take(5)
            ->get()
            ->map(function ($p) {
                $dias = now()->diffInDays(\Carbon\Carbon::parse($p->data_vencimento), false);
                return [
                    'referencia' => $p->referencia,
                    'descricao'  => $p->descricao ?? 'Propina Mensal',
                    'valor'      => $p->valor,
                    'dias'       => $dias,
                    'url'        => route('estudante.pagamentos'),
                    'badge'      => $dias < 0 ? 'badge-danger'
                                 : ($dias <= 5 ? 'badge-warning' : 'badge-info'),
                    'badge_text' => $dias < 0 ? 'Vencido'
                                 : ($dias == 0 ? 'Hoje' : "{$dias} dias"),
                ];
            })
            ->toArray();

        // Últimos pagamentos
        $ultimosPagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->take(5)
            ->get();

        // Notas de frequência do ano actual para progresso
        $disciplinasAtuais = collect();
        if ($estudante->turma && $estudante->turma->classe_id) {
            $disciplinasAtuais = NotaFrequencia::where('estudante_id', $estudante->id)
                ->where('ano_lectivo_id', $estudante->ano_lectivo_id)
                ->with('disciplina')
                ->get()
                ->map(function ($nota) {
                    $media = $nota->nota ?: 0;
                    return [
                        'nome'     => $nota->disciplina->nome ?? 'N/A',
                        'progresso'=> ($media / 20) * 100,
                        'media'    => $media,
                        'cor'      => $this->getCorProgresso($media),
                    ];
                });
        }

        // Estatísticas
        $pagamentosPendentes = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')->count();
        $totalPago     = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pago')->sum('valor');
        $totalPendente = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')->sum('valor');
        $mediaGeral    = $disciplinasAtuais->avg('media') ?? 0;

        $estatisticas = [
            'media_geral'          => $mediaGeral,
            'presenca'             => '92%',
            'pagamentos_pendentes' => $pagamentosPendentes,
            'total_pago'           => $totalPago,
            'total_pendente'       => $totalPendente,
        ];

        // Notificações e eventos
        $ultimasNotificacoes = $this->getUltimasNotificacoes($estudante);
        $eventosCalendario   = $this->getEventosCalendario($estudante);

        return view('estudante.dashboard', compact(
            'estudante',
            'totalMatriculas',
            'matriculaAtual',
            'progressoCurso',
            'disciplinasAtuais',
            'ultimosPagamentos',
            'ultimasNotificacoes',
            'eventosCalendario',
            'estatisticas',
            'pagamentosPendentes',
            'proximosPrazos'
        ));
    }

    /**
     * Histórico de matrículas anuais do estudante
     */
    public function matriculas()
    {
        $estudante = $this->getEstudanteOrRedirect();

        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }

        $matriculas = Matricula::where('estudante_id', $estudante->id)
            ->with(['turma', 'anoLectivo'])
            ->orderByDesc('created_at')
            ->get();

        return view('estudante.matriculas', compact('estudante', 'matriculas'));
    }

    private function getCorProgresso($media)
    {
        if ($media >= 14) return 'success';
        if ($media >= 10) return 'primary';
        if ($media >= 8)   return 'warning';
        return 'danger';
    }

    /**
     * Obtém os eventos do calendário (pagamentos + eventos do ano lectivo)
     */
    private function getEventosCalendario($estudante)
    {
        $eventos = [];

        // Pagamentos pendentes
        $pagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->where('status', 'pendente')
            ->get();

        foreach ($pagamentos as $pagamento) {
            $date = $pagamento->data_vencimento;
            if ($date) {
                $eventos[] = [
                    'title'  => '⏰ ' . ($pagamento->descricao ?? 'Pagamento'),
                    'start'  => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                    'color'  => '#f57f17',
                ];
            }
        }

        // Eventos do ano lectivo
        $anoLetivo = AnoLectivo::find($estudante->ano_lectivo_id);
        if ($anoLetivo) {
            if ($anoLetivo->data_inicio ?? false) {
                $eventos[] = [
                    'title' => '📚 Início das Aulas',
                    'start' => \Carbon\Carbon::parse($anoLetivo->data_inicio)->format('Y-m-d'),
                    'color' => '#28a745',
                ];
            }
            if ($anoLetivo->data_fim ?? false) {
                $eventos[] = [
                    'title' => '🏁 Fim das Aulas',
                    'start' => \Carbon\Carbon::parse($anoLetivo->data_fim)->format('Y-m-d'),
                    'color' => '#dc3545',
                ];
            }
        }

        // Data da matrícula (evento anual)
        $matriculaAtual = Matricula::where('estudante_id', $estudante->id)
            ->orderByDesc('created_at')
            ->first();
        if ($matriculaAtual) {
            $eventos[] = [
                'title' => '✅ Matrícula Renovada',
                'start' => $matriculaAtual->created_at->format('Y-m-d'),
                'color' => '#1565c0',
            ];
        }

        // Fallback: datas exemplo se não houver eventos
        if (empty($eventos)) {
            $ano = date('Y');
            $eventos = [
                ['title' => '📚 Início das Aulas',  'start' => "{$ano}-02-15", 'color' => '#28a745'],
                ['title' => '📝 Exames 1º Trimestre', 'start' => "{$ano}-04-20", 'color' => '#dc3545'],
                ['title' => '⏰ Pagamento de Propina', 'start' => "{$ano}-03-10", 'color' => '#f57f17'],
                ['title' => '🏁 Fim do Ano Lectivo', 'start' => "{$ano}-12-10", 'color' => '#6c757d'],
            ];
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

        if ($request->has('tipo') && $request->tipo !== 'todas') {
            $query->where('tipo', $request->tipo);
        }
        if ($request->has('lida')) {
            $query->where('lida', $request->lida === 'true');
        }

        $notificacoes = $query->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($notificacao) {
                return $notificacao->created_at->format('Y-m-d');
            });

        return view('estudante.notificacoes', compact('notificacoes'));
    }

    public function marcarComoLida($id)
    {
        $notificacao = Notificacao::where('user_id', auth()->id())->findOrFail($id);
        $notificacao->update(['lida' => true]);
        return response()->json(['success' => true, 'message' => 'Notificação marcada como lida!']);
    }

    public function marcarTodasComoLidas()
    {
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);
        return response()->json(['success' => true, 'message' => 'Todas as notificações foram marcadas como lidas!']);
    }

    public static function criarNotificacao($userId, $dados)
    {
        return Notificacao::create([
            'user_id' => $userId,
            'titulo'  => $dados['titulo'],
            'mensagem'=> $dados['mensagem'],
            'tipo'    => $dados['tipo'] ?? 'geral',
            'icone'   => $dados['icone'] ?? null,
            'cor'     => $dados['cor'] ?? null,
            'link'    => $dados['link'] ?? null,
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
                    'id'    => $notificacao->id,
                    'titulo'=> $notificacao->titulo,
                    'mensagem' => $notificacao->mensagem,
                    'data'  => $notificacao->created_at,
                    'tipo'  => $notificacao->tipo,
                    'lida'  => $notificacao->lida,
                    'link'  => $notificacao->link,
                ];
            });
    }

    /**
     * Página de pagamentos / propinas do estudante
     */
    public function pagamentos()
    {
        $estudante = $this->getEstudanteOrRedirect();

        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }

        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        $pagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->when($anoAtivo, fn($q) => $q->where('ano_lectivo_id', $anoAtivo->id))
            ->orderBy('data_vencimento', 'asc')
            ->get();

        $totalPago     = $pagamentos->where('status', 'pago')->sum('valor');
        $totalPendente = $pagamentos->where('status', 'pendente')->sum('valor');
        $anosLetivos   = AnoLectivo::orderBy('ano', 'desc')->get();

        return view('estudante.pagamentos', compact(
            'estudante', 'pagamentos', 'totalPago', 'totalPendente', 'anosLetivos', 'anoAtivo'
        ));
    }

    public function relatorios()
    {
        $estudante = $this->getEstudanteOrRedirect();

        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }

        $matriculas = Matricula::where('estudante_id', $estudante->id)
            ->with(['turma', 'anoLectivo'])
            ->orderByDesc('created_at')
            ->get();

        $totalPago     = Pagamento::where('estudante_id', $estudante->id)->where('status', 'pago')->sum('valor');
        $totalPendente = Pagamento::where('estudante_id', $estudante->id)->where('status', 'pendente')->sum('valor');

        return view('estudante.relatorios', compact('estudante', 'matriculas', 'totalPago', 'totalPendente'));
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
     * Formulário de criação de perfil (aluno novo)
     */
    public function createProfile()
    {
        $existingEstudante = Estudante::where('user_id', Auth::id())->first();

        if ($existingEstudante) {
            return redirect()->route('estudante.dashboard')
                ->with('info', 'Você já possui um perfil de estudante.');
        }

        $turmas       = Turma::orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderBy('ano', 'desc')->get();
        $matricula    = $this->generateMatricula(date('Y'));

        return view('estudante.create-profile', compact('turmas', 'anosLectivos', 'matricula'));
    }

    public function generateMatricula($anoIngresso)
    {
        $lastMatricula = Estudante::where('ano_ingresso', $anoIngresso)
            ->orderBy('matricula', 'desc')
            ->first();

        if ($lastMatricula) {
            $lastNumber = intval(substr($lastMatricula->matricula, -3));
            $newNumber  = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return sprintf('%03d.01.%d', $newNumber, $anoIngresso);
    }

    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'turma_id'         => 'required|exists:turmas,id',
            'ano_lectivo_id'   => 'required|exists:anos_lectivos,id',
            'data_nascimento'  => 'required|date',
            'genero'           => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso'     => 'required|digits:4',
            'turno'            => 'required|in:Diurno,Noturno',
            'contato_emergencia' => 'nullable|string|max:255',
        ]);

        $matricula = $this->generateMatricula($validated['ano_ingresso']);
        while (Estudante::where('matricula', $matricula)->exists()) {
            $matricula = $this->generateMatricula($validated['ano_ingresso']);
        }

        DB::beginTransaction();
        try {
            $estudante = Estudante::create([
                'user_id'           => Auth::id(),
                'matricula'         => $matricula,
                'turma_id'          => $validated['turma_id'],
                'ano_lectivo_id'    => $validated['ano_lectivo_id'],
                'data_nascimento'   => $validated['data_nascimento'],
                'genero'            => $validated['genero'],
                'ano_ingresso'      => $validated['ano_ingresso'],
                'turno'             => $validated['turno'],
                'status'            => 'Ativo',
                'contato_emergencia'=> $validated['contato_emergencia'],
            ]);

            DB::commit();

            return redirect()->route('estudante.dashboard')
                ->with('success', 'Perfil de estudante criado com sucesso! Bem-vindo à ' .
                    ($estudante->turma->classe->nome ?? 'escola') . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao criar perfil. Tente novamente.')->withInput();
        }
    }
}
