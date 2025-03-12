<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotaDetalhada;
use App\Models\NotaFrequencia;

class NotasDetalhadasController extends Controller
{
    public function index($notaFrequenciaId)
    {
        $notaFrequencia = NotaFrequencia::with('notasDetalhadas')->findOrFail($notaFrequenciaId);
        return view('estudante.notas_detalhadas.index', compact('notaFrequencia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'notas_frequencia_id' => 'required|exists:notas_frequencia,id',
            'tipo' => 'required|in:Teste 1,Teste 2,Teste 3,Trabalho 1,Trabalho 2,Trabalho 3',
            'nota' => 'required|numeric|min:0|max:20'
        ]);

        NotaDetalhada::create($request->all());

        return redirect()->back()->with('success', 'Nota detalhada adicionada com sucesso.');
    }
}
