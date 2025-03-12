<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Pagamento, Estudante};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacaoPagamento;
use Carbon\Carbon;

class PagamentoController extends Controller
{
    /**
     * Exibe a lista de pagamentos com filtros e paginação.
     */
    public function index(Request $request)
    {
        $query = Pagamento::with('estudante.user');

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por estudante
        if ($request->filled('estudante')) {
            $query->whereHas('estudante.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->estudante . '%');
            });
        }

        // Filtro por data de vencimento
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

        // Ordenação
        $ordem = $request->get('ordem', 'data_vencimento');
        $direcao = $request->get('direcao', 'desc');
        $query->orderBy($ordem, $direcao);

        // Paginação
        $pagamentos = $query->paginate(10)->appends($request->all());

        // Dados para filtros
        $estudantes = Estudante::with('user')->get();

        return view('admin.pagamentos.index', compact('pagamentos', 'estudantes'));
    }


    /**
     * Exibe o formulário de criação de pagamento.
     */
    public function create()
    {
        $estudantes = Estudante::with('user')->get();
        return view('admin.pagamentos.create', compact('estudantes'));
    }

    /**
     * Armazena um novo pagamento no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric|min:0',
        ]);

        // Gerar referência única
        $referencia = Pagamento::gerarReferencia();

        // Criar pagamento
        $pagamento = Pagamento::create([
            'estudante_id' => $request->estudante_id,
            'valor' => $request->valor ?? 2500, // Valor padrão se não for especificado
            'data_vencimento' => $request->data_vencimento,
            'referencia' => $referencia,
            'status' => 'pendente', // Status inicial
            'observacao' => $request->observacao,
        ]);

        // Notificar o estudante
        $estudante = $pagamento->estudante;
        Mail::to($estudante->user->email)->send(new NotificacaoPagamento($pagamento));

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento criado e notificação enviada com sucesso!');
    }
    public function edit(Pagamento $pagamento)
    {
        $estudantes = Estudante::with('user')->get();
        return view('admin.pagamentos.edit', compact('pagamento', 'estudantes'));
    }
    public function update(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'data_vencimento' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:pago,pendente,cancelado',
        ]);

        $pagamento->update([
            'estudante_id' => $request->estudante_id,
            'data_vencimento' => $request->data_vencimento,
            'valor' => $request->valor,
            'status' => $request->status,
            'observacao' => $request->observacao,
        ]);

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento atualizado com sucesso!');
    }
    /**
     * Exibe os detalhes de um pagamento.
     */
    public function show(Pagamento $pagamento)
    {
        // pegar estudantes
        $estudantes = Estudante::with('user')->get();

        // carregar os dados do pagamento com relacionamentos e tratar observações
        $pagamento->observacao = $pagamento->observacao? nl2br($pagamento->observacao) : null;
        $pagamento->load('estudante.user');
        return view('admin.pagamentos.show', compact('pagamento', 'estudantes'));
    }

    /**
     * Atualiza o status de um pagamento.
     */
    public function updateStatus(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'status' => 'required|in:pago,pendente,cancelado',
        ]);

        $pagamento->update([
            'status' => $request->status,
            'data_pagamento' => $request->status == 'pago' ? now() : null,
            'observacao' => $request->filled('observacao') ? 
                $pagamento->observacao . "\n" . now()->format('d/m/Y H:i') . " - Status alterado para " . $request->status . ". " . $request->observacao : 
                $pagamento->observacao
        ]);

        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Status do pagamento atualizado com sucesso!');
    }

    /**
     * Remove um pagamento do banco de dados.
     */
    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();
        return redirect()->route('admin.pagamentos.index')
            ->with('success', 'Pagamento removido com sucesso!');
    }
    
    /**
     * Exporta pagamentos para CSV
     */
    public function exportar(Request $request)
    {
        // Aplicar os mesmos filtros que no método index()
        $query = Pagamento::with('estudante.user');

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por estudante
        if ($request->filled('estudante')) {
            $query->whereHas('estudante.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->estudante . '%');
            });
        }

        // Filtro por data de vencimento
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [
                Carbon::parse($request->data_inicio)->startOfDay(),
                Carbon::parse($request->data_fim)->endOfDay()
            ]);
        } elseif ($request->filled('data_inicio')) {
            $query->where('data_vencimento', '>=', Carbon::parse($request->data_inicio)->startOfDay());
        } elseif ($request->filled('data_fim')) {
            $query->where('data_vencimento', '<=', Carbon::parse($request->data_fim)->endOfDay());
        }

        // Ordenação
        $ordem = $request->ordem ?? 'data_vencimento';
        $direcao = $request->direcao ?? 'desc';
        $query->orderBy($ordem, $direcao);

        // Buscar os pagamentos filtrados
        $pagamentos = $query->get();

        // Configurar o nome do arquivo
        $fileName = 'pagamentos_' . date('Y-m-d') . '.csv';

        // Configurar os headers para download
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Função para gerar o CSV
        $callback = function() use ($pagamentos) {
            $file = fopen('php://output', 'w');

            // Adicionar BOM para garantir que o arquivo seja aberto corretamente no Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalho do CSV
            fputcsv($file, ['Referência', 'Estudante', 'Valor (MZN)', 'Data de Vencimento', 'Status']);

            // Dados do CSV
            foreach ($pagamentos as $pagamento) {
                fputcsv($file, [
                    $pagamento->referencia,
                    $pagamento->estudante->user->name,
                    number_format($pagamento->valor, 2, ',', '.'), // Formatar valor
                    Carbon::parse($pagamento->data_vencimento)->format('d/m/Y'), // Formatar data
                    ucfirst($pagamento->status), // Formatar status
                ]);
            }

            fclose($file);
        };

        // Retornar a resposta de download
        return response()->stream($callback, 200, $headers);
    }
}