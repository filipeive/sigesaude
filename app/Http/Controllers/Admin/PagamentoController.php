<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Estudante;
use App\Models\Notificacao;
use App\Models\Pagamento;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * ENTIDADE DO SISTEMA (fixa — como no guia de matrícula).
     * Para alterar, edite este valor.
     */
    public const ENTIDADE_BANCARIA = '11151';

    /**
     * Exibe a lista de pagamentos com filtros e paginação.
     * Filtros disponíveis: estudante, turma, categoria (tipo), status, data_inicio, data_fim, search (referência).
     */
    public function index(Request $request)
    {
        $query = Pagamento::with(['estudante.user', 'estudante.turma', 'turma']);

        // ── Filtro por Estudante ──
        if ($request->filled('estudante')) {
            $query->whereHas('estudante.user', fn ($q) => $q->where('name', 'like', '%'.$request->estudante.'%'));
        }

        // ── Filtro por Turma ──
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }

        // ── Filtro por Categoria (Tipo de Pagamento) ──
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // ── Filtro por Status ──
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ── Filtro por Data ──
        try {
            if ($request->filled('data_inicio')) {
                $dataInicio = Carbon::createFromFormat('d/m/Y', $request->data_inicio)->startOfDay();
            }
            if ($request->filled('data_fim')) {
                $dataFim = Carbon::createFromFormat('d/m/Y', $request->data_fim)->endOfDay();
            }
            if (isset($dataInicio) && isset($dataFim)) {
                $query->whereBetween('data_vencimento', [$dataInicio, $dataFim]);
            } elseif (isset($dataInicio)) {
                $query->where('data_vencimento', '>=', $dataInicio);
            } elseif (isset($dataFim)) {
                $query->where('data_vencimento', '<=', $dataFim);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['data' => 'Formato de data inválido. Use DD/MM/YYYY.']);
        }

        // ── Ordenação ──
        $ordem = $request->get('ordem', 'data_vencimento');
        $direcao = $request->get('direcao', 'desc');
        $query->orderBy($ordem, $direcao);

        // ── Paginação ──
        $pagamentos = $query->paginate(10)->appends($request->all());

        // ── Dados auxiliares ──
        $estudantes = Estudante::with('user')->get();
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $categorias = [
            'propina' => 'Propina Mensal',
            'matricula' => 'Matrícula',
            'taxa' => 'Taxa / Outros',
            'inscricao' => 'Inscrição',
        ];

        // ── Estatísticas Rápidas ──
        $totalPendentes = Pagamento::where('status', 'pendente')->count();
        $totalVencidas = Pagamento::where('status', 'pendente')
            ->where('data_vencimento', '<', now())
            ->count();
        $totalPagos = Pagamento::where('status', 'pago')->count();

        return view('admin.pagamentos.index', compact(
            'pagamentos', 'estudantes', 'turmas', 'anosLectivos', 'categorias',
            'totalPendentes', 'totalVencidas', 'totalPagos'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE / STORE
    // ─────────────────────────────────────────────────────────────

    public function create(Request $request)
    {
        $estudantes = Estudante::with('user')->get();
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();
        $metodosPagamento = [
            'dinheiro'     => 'Dinheiro',
            'transferencia'=> 'Transferência',
            'mpesa'        => 'M-Pesa',
            'emola'        => 'eMola',
            'mkesh'        => 'M-Kesh',
            'cheque'       => 'Cheque',
            'outro'        => 'Outro',
        ];

        // Se vier turma por querystring, pré-seleciona
        $turmaSelecionada = $request->get('turma_id')
            ? Turma::with('estudantes.user')->find($request->get('turma_id'))
            : null;

        return view('admin.pagamentos.create', compact(
            'estudantes', 'turmas', 'anosLectivos', 'anoAtivo', 'turmaSelecionada', 'metodosPagamento'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'tipo' => 'nullable|in:propina,matricula,taxa,inscricao',
            'turma_id' => 'nullable|exists:turmas,id',
            'metodo_pagamento' => 'nullable|in:dinheiro,transferencia,mpesa,emola,mkesh,cheque,outro',
        ]);

        $referencia = Pagamento::gerarReferencia();

        $pagamento = Pagamento::create([
            'estudante_id' => $request->estudante_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'valor' => $request->valor ?? 2500,
            'data_vencimento' => $request->data_vencimento,
            'referencia' => $referencia,
            'status' => 'pendente',
            'tipo' => $request->tipo,
            'turma_id' => $request->turma_id,
            'metodo_pagamento' => $request->metodo_pagamento,
            'descricao' => $request->observacao,
        ]);

        $estudante = $pagamento->estudante;
        Notificacao::notificarPagamentoPendente($estudante->user_id, $pagamento);

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento criado e notificação enviada com sucesso!');
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW — com instruções de pagamento
    // ─────────────────────────────────────────────────────────────

    public function show(Pagamento $pagamento)
    {
        $pagamento->load(['estudante.user', 'estudante.turma', 'estudante.anoLectivo', 'turma']);
        $pagamento->observacao = $pagamento->observacao ? nl2br($pagamento->observacao) : null;

        $estudantes = Estudante::with('user')->get();
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        // Texto de instrução conforme o tipo
        $instrucoes = [
            'propina' => 'Propina mensal referente ao mês indicado. Deve ser saldada até ao dia 10 de cada mês.',
            'matricula' => 'Pagamento da matrícula anual. Utilize os dados abaixo para efectuar o pagamento em qualquer ATM ou Internet Banking.',
            'taxa' => 'Taxa adicional ou multa. A referência acima é utilizada para efectuar o pagamento.',
            'inscricao' => 'Taxa de inscrição para o ano lectivo corrente. Não reembolsável.',
        ];
        $instrucaoTexto = $instrucoes[$pagamento->tipo] ?? $instrucoes['taxa'];

        return view('admin.pagamentos.show', compact(
            'pagamento', 'estudantes', 'turmas', 'anosLectivos', 'instrucaoTexto'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT / UPDATE
    // ─────────────────────────────────────────────────────────────

    public function edit(Pagamento $pagamento)
    {
        $estudantes = Estudante::with('user')->get();
        $turmas = Turma::orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $metodosPagamento = [
            'dinheiro'     => 'Dinheiro',
            'transferencia'=> 'Transferência',
            'mpesa'        => 'M-Pesa',
            'emola'        => 'eMola',
            'mkesh'        => 'M-Kesh',
            'cheque'       => 'Cheque',
            'outro'        => 'Outro',
        ];

        return view('admin.pagamentos.edit', compact('pagamento', 'estudantes', 'turmas', 'anosLectivos', 'metodosPagamento'));
    }

    public function update(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:pago,pendente,cancelado',
            'tipo' => 'nullable|in:propina,matricula,taxa,inscricao',
            'turma_id' => 'nullable|exists:turmas,id',
            'metodo_pagamento' => 'nullable|in:dinheiro,transferencia,mpesa,emola,mkesh,cheque,outro',
        ]);

        $pagamento->update([
            'estudante_id' => $request->estudante_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'data_vencimento' => $request->data_vencimento,
            'valor' => $request->valor,
            'status' => $request->status,
            'tipo' => $request->tipo,
            'turma_id' => $request->turma_id,
            'metodo_pagamento' => $request->metodo_pagamento,
            'descricao' => $request->observacao,
        ]);

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

    // ─────────────────────────────────────────────────────────────
    // STATUS / RECIBO / EXPORTAR
    // ─────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'status' => 'required|in:pago,pendente,cancelado',
        ]);

        $pagamento->update([
            'status' => $request->status,
            'data_pagamento' => $request->status == 'pago' ? now() : null,
            'observacao' => $request->filled('observacao')
                ? ($pagamento->observacao ?? '')."\n".now()->format('d/m/Y H:i')." — Status alterado para {$request->status}. ".$request->observacao
                : $pagamento->observacao,
        ]);

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Status do pagamento atualizado com sucesso!');
    }

    public function downloadRecibo(Pagamento $pagamento)
    {
        $pagamento->load(['estudante.user', 'estudante.turma', 'estudante.anoLectivo', 'turma']);

        $pdf = Pdf::loadView('pdf.recibo_pagamento', compact('pagamento'));

        return $pdf->download('Recibo_'.$pagamento->referencia.'.pdf');
    }

    public function exportar(Request $request)
    {
        $query = Pagamento::with('estudante.user');

        if ($request->filled('estudante')) {
            $query->whereHas('estudante.user', fn ($q) => $q->where('name', 'like', '%'.$request->estudante.'%'));
        }
        if ($request->filled('turma_id')) {
            $query->where('turma_id', $request->turma_id);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [
                Carbon::parse($request->data_inicio)->startOfDay(),
                Carbon::parse($request->data_fim)->endOfDay(),
            ]);
        }

        $ordem = $request->ordem ?? 'data_vencimento';
        $direcao = $request->direcao ?? 'desc';
        $query->orderBy($ordem, $direcao);

        $pagamentos = $query->get();

        $fileName = 'pagamentos_'.date('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        $callback = function () use ($pagamentos) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Referência', 'Estudante', 'Turma', 'Categoria', 'Valor (MZN)', 'Vencimento', 'Status']);
            foreach ($pagamentos as $p) {
                fputcsv($file, [
                    $p->referencia,
                    $p->estudante->user->name ?? 'N/A',
                    $p->turma?->nome ?? '—',
                    $p->tipo ?? '—',
                    number_format($p->valor, 2, ',', '.'),
                    Carbon::parse($p->data_vencimento)->format('d/m/Y'),
                    ucfirst($p->status),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove um pagamento.
     */
    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento removido com sucesso!');
    }

    /**
     * Página de configurações (stub).
     */
    public function configuracoes()
    {
        return view('admin.pagamentos.configuracoes');
    }
}
