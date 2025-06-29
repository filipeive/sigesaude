<?php

namespace App\Http\Controllers\Estudante;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagamentoController extends Controller
{
    /**
     * Exibe o pagamento do estudante.
     */
    public function show($id)
    {
        // Verificar se o pagamento existe
        $pagamento = Pagamento::findOrFail($id);
        
        // Retorna a view com os dados do pagamento
        return view('estudante.pagamentos.show', compact('pagamento'));
    }
}
