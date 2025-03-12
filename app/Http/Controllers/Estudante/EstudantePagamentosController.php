<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Pagamento;
use App\Models\AnoLectivo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class EstudantePagamentosController extends Controller
{
    /**
     * Check if student exists and return the student or redirect
     */
    private function getEstudanteOrRedirect()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        
        if (!$estudante) {
            return redirect()->route('estudante.create.profile')
                ->with('error', 'Perfil de estudante não encontrado. Por favor, complete seu cadastro.');
        }
        
        return $estudante;
    }

    /**
     * Exibe os pagamentos do estudante para um ano letivo específico
     */
    public function pagamentos(Request $request)
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Obter o ano letivo selecionado ou usar o atual
        $anoLetivoId = $request->input('ano_letivo', null);
        
        // Obter todos os anos letivos disponíveis para este estudante
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();
        
        if (!$anoLetivoId && $anosLetivos->count() > 0) {
            $anoLetivoId = $anosLetivos->first()->id;
        }
        
        // Obter o ano letivo atual
        $anoLetivo = AnoLectivo::find($anoLetivoId);
        
        // Obter pagamentos do estudante para o ano letivo selecionado
        $query = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_vencimento', 'asc');
            
        if ($anoLetivoId) {
            // Assumindo que o ano letivo tem atributos data_inicio e data_fim
            // Se não tiver, você precisará adaptar esta lógica
            $query->whereYear('data_vencimento', $anoLetivo->ano);
        }
        
        $pagamentos = $query->get();
        
        // Agrupar pagamentos por mês
        $pagamentosPorMes = [];
        foreach ($pagamentos as $pagamento) {
            $mes = Carbon::parse($pagamento->data_vencimento)->format('m-Y');
            if (!isset($pagamentosPorMes[$mes])) {
                $pagamentosPorMes[$mes] = [];
            }
            $pagamentosPorMes[$mes][] = $pagamento;
        }
        
        // Calcular totais
        $totalPago = $pagamentos->where('status', 'pago')->sum('valor');
        $totalPendente = $pagamentos->where('status', 'pendente')->sum('valor');
        $proximoVencimento = $pagamentos->where('status', 'pendente')
            ->where('data_vencimento', '>=', Carbon::now())
            ->sortBy('data_vencimento')
            ->first();
            
        // Obter referências para pagamento (propinas)
        $propinas = $this->getPropinasAtuais($estudante, $anoLetivo);
        
        return view('estudante.pagamentos', compact(
            'estudante', 
            'pagamentos', 
            'pagamentosPorMes', 
            'anosLetivos', 
            'anoLetivo',
            'totalPago', 
            'totalPendente', 
            'proximoVencimento',
            'propinas'
        ));
    }    
    
    /**
     * Gera as propinas do ano letivo atual para o estudante
     */
    private function getPropinasAtuais($estudante, $anoLetivo)
    {
        if (!$anoLetivo) {
            return collect();
        }
        
        // Aqui vamos gerar ou recuperar as propinas mensais para exibição
        $propinas = [];
        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];
        
        // Valor padrão da propina
        $valorPadrao = 2420.00; // Valor para meses normais
        $valorFevereiro = 2820.00; // Valor para Fevereiro
        
        $hoje = Carbon::now();
        $ano = $anoLetivo->ano;
        
        // Buscar propinas existentes para não duplicar
        $propinaExistente = Pagamento::where('estudante_id', $estudante->id)
            ->whereYear('data_vencimento', $ano)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->data_vencimento)->format('m');
            });
        
        foreach ($meses as $numero => $nome) {
            // Verificar se o mês já foi processado
            $mesKey = str_pad($numero, 2, '0', STR_PAD_LEFT);
            
            if (isset($propinaExistente[$mesKey])) {
                // A propina para este mês já existe
                $propinas[] = $propinaExistente[$mesKey];
                continue;
            }
            
            // Gerar referência única para o pagamento
            $referencia = $this->gerarReferenciaUnica();
            
            // Definir data de vencimento (dia 10 de cada mês)
            $dataVencimento = Carbon::createFromDate($ano, $numero, 10)->format('Y-m-d');
            
            // Definir o valor da propina 
            $valor = ($numero == 2) ? $valorFevereiro : $valorPadrao;
            
            // Definir o status do pagamento
            $status = 'pendente';
            
            // Adicionar à lista de propinas
            $propinas[] = Pagamento::create([
                'estudante_id' => $estudante->id,
                'descricao' => 'Propina ' . $nome . ' ' . $ano,
                'referencia' => $referencia,
                'valor' => $valor,
                'data_vencimento' => $dataVencimento,
                'status' => $status
            ]);
        }
        
        // Retornar uma instância paginada de LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($propinas, ($currentPage - 1) * $perPage, $perPage);
        $paginatedPropinas = new LengthAwarePaginator($currentItems, count($propinas), $perPage);
        $paginatedPropinas->setPath(request()->url());
        
        return $paginatedPropinas;
    }
    
    /**
     * Gera uma referência única para o pagamento
     */
    private function gerarReferenciaUnica()
    {
        do {
            $referencia = '0' . rand(1000000000, 9999999999);
        } while (Pagamento::where('referencia', $referencia)->exists());
        
        return $referencia;
    }
    
    /**
     * Registra um novo pagamento manual (para fins de demonstração)
     */
    public function registrarPagamento(Request $request)
    {
        $validated = $request->validate([
            'referencia' => 'required|string',
            'comprovante' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Buscar o pagamento pela referência
        $pagamento = Pagamento::where('referencia', $validated['referencia'])
            ->where('estudante_id', $estudante->id)
            ->first();
            
        if (!$pagamento) {
            return back()->with('error', 'Referência de pagamento não encontrada.');
        }
        
        // Salvar o comprovante
        $path = $request->file('comprovante')->store('comprovantes', 'public');
        
        // Atualizar o pagamento
        $pagamento->status = 'em_analise'; // Crie este status no enum se necessário
        $pagamento->comprovante = $path;
        $pagamento->save();
        
        return redirect()->route('estudante.pagamentos')
            ->with('success', 'Comprovante enviado com sucesso. Seu pagamento está em análise.');
    }
}