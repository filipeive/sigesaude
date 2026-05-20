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
        $relatorios = RelatorioFinanceiro::latest()->paginate(10);
        
        // Calcular métricas para os cards (exemplo simplificado)
        $entradas_mensal = Transacao::where('tipo', 'entrada')->whereMonth('data', now()->month)->sum('valor');
        $saidas_mensal = Transacao::where('tipo', 'saida')->whereMonth('data', now()->month)->sum('valor');
        $saldo_mensal = $entradas_mensal - $saidas_mensal;
        
        $saldo_anterior = Transacao::where('tipo', 'entrada')->whereMonth('data', now()->subMonth()->month)->sum('valor') - 
                         Transacao::where('tipo', 'saida')->whereMonth('data', now()->subMonth()->month)->sum('valor');
        
        $crescimento_percentual = $saldo_anterior > 0 ? round((($saldo_mensal - $saldo_anterior) / $saldo_anterior) * 100, 2) : 100;

        return view('admin.financeiro.relatorios', compact(
            'relatorios', 
            'entradas_mensal', 
            'saidas_mensal', 
            'saldo_mensal', 
            'saldo_anterior', 
            'crescimento_percentual'
        ));
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

    public function showRelatorio($id)
    {
        $relatorio = RelatorioFinanceiro::findOrFail($id);
        return view('admin.financeiro.relatorios_show', compact('relatorio'));
    }

    public function downloadRelatorio($id)
    {
        $relatorio = RelatorioFinanceiro::findOrFail($id);
        // Lógica de download
        return back()->with('success', 'Download iniciado.');
    }

    public function destroyRelatorio($id)
    {
        $relatorio = RelatorioFinanceiro::findOrFail($id);
        $relatorio->delete();
        return back()->with('success', 'Relatório excluído com sucesso.');
    }

    public function gerarRelatorio(Request $request)
    {
        // Lógica para gerar relatório
        return back()->with('success', 'Relatório em processamento.');
    }

    public function ajaxGrafico(Request $request)
    {
        // Retornar dados JSON para o gráfico
        return response()->json([
            'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            'entradas' => [1000, 2000, 1500, 3000, 2500, 4000],
            'saidas' => [800, 1500, 1200, 2000, 1800, 3000]
        ]);
    }
}