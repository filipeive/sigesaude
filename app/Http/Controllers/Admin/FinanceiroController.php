<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transacao;
use App\Models\RelatorioFinanceiro;
use App\Models\ConfiguracaoPagamento;
use Illuminate\Support\Facades\Auth;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obter todas as transações com paginação
        $transacoes = Transacao::paginate(10);
        
        // Calcular totais
        $entradas = Transacao::where('tipo', 'entrada')->sum('valor');
        $saidas = Transacao::where('tipo', 'saida')->sum('valor');
        $total_transacoes = Transacao::count();
        
        // Exibir a página do painel financeiro
        return view('admin.financeiro.index', compact('transacoes', 'entradas', 'saidas', 'total_transacoes')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exibir o formulário para criar uma nova transação
        return view('admin.financeiro.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar e salvar a nova transação
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'tipo' => 'required|string|in:entrada,saida',
        ]);

        Transacao::create($validated);

        return redirect()->route('admin.financeiro.index')
            ->with('success', 'Transação criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Exibir detalhes de uma transação específica
        $transacao = Transacao::findOrFail($id);
        return view('admin.financeiro.show', compact('transacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Exibir o formulário para editar uma transação existente
        $transacao = Transacao::findOrFail($id);
        return view('admin.financeiro.edit', compact('transacao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar e atualizar a transação existente
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data' => 'required|date',
            'tipo' => 'required|string|in:entrada,saida',
        ]);

        $transacao = Transacao::findOrFail($id);
        $transacao->update($validated);

        return redirect()->route('admin.financeiro.index')
            ->with('success', 'Transação atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Excluir uma transação existente
        $transacao = Transacao::findOrFail($id);
        $transacao->delete();

        return redirect()->route('admin.financeiro.index')
            ->with('success', 'Transação excluída com sucesso.');
    }

    /**
     * Display a listing of financial reports.
     */
    public function relatorios()
    {
        // Exibir a página de relatórios financeiros
        $relatorios = RelatorioFinanceiro::all();
        return view('admin.financeiro.relatorios', compact('relatorios'));
    }

    /**
     * Display the form for configuring payment settings.
     */
    public function configuracoes()
    {
        // Exibir o formulário de configurações de pagamento
        $configuracoes = ConfiguracaoPagamento::first();
        return view('admin.financeiro.configuracoes', compact('configuracoes'));
    }

    /**
     * Update the payment settings.
     */
    public function atualizarConfiguracoes(Request $request)
    {
        // Validar e atualizar as configurações de pagamento
        $validated = $request->validate([
            'metodo_pagamento' => 'required|string|max:255',
            'detalhes' => 'nullable|string',
        ]);

        $configuracoes = ConfiguracaoPagamento::first();
        $configuracoes->update($validated);

        return redirect()->route('admin.financeiro.configuracoes')
            ->with('success', 'Configurações de pagamento atualizadas com sucesso.');
    }
}