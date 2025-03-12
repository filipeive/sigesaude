<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Estudante, NotaExame, MediaFinal, AnoLectivo};
use Illuminate\Support\Facades\Auth;

class NotasExameController extends Controller
{
    /**
     * Página inicial do módulo de notas de exame
     */
    public function index()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();

        // Verificar se o estudante foi encontrado
        if (!$estudante) {
            return redirect()->route('login')->with('error', 'Estudante não encontrado.');
        }

        // Obter o ano letivo ativo
        $anoLetivoAtual = AnoLectivo::where('status', 'Ativo')->first();

        // Obter as notas de exame do estudante para o ano letivo ativo
        $notasExame = NotaExame::where('estudante_id', $estudante->id)
                            ->where('ano_lectivo_id', $anoLetivoAtual->id)
                            ->with(['disciplina'])
                            ->get();

        // Obter as médias finais do estudante para o ano letivo ativo
        $mediasFinais = MediaFinal::where('estudante_id', $estudante->id)
                                ->where('ano_lectivo_id', $anoLetivoAtual->id)
                                ->with(['disciplina'])
                                ->get();

        // Obter todos os anos letivos para o seletor
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();

        return view('estudante.notas_exame.index', compact(
            'estudante', 
            'notasExame', 
            'mediasFinais', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    }
    /**
     * Exibe as notas de exame e médias finais do estudante
     */
    public function notasExame(Request $request)
{
    $estudante = Estudante::where('user_id', Auth::id())->first();

    // Verificar se o estudante foi encontrado
    if (!$estudante) {
        return redirect()->route('login')->with('error', 'Estudante não encontrado.');
    }

    // Obter o ano letivo selecionado ou o ativo por padrão
    $anoLetivoId = $request->ano_letivo_id ?? AnoLectivo::where('status', 'Ativo')->first()->id;

    // Obter as notas de exame do estudante para o ano letivo selecionado
    $notasExame = NotaExame::where('estudante_id', $estudante->id)
                          ->where('ano_lectivo_id', $anoLetivoId)
                          ->with(['disciplina'])
                          ->get();

    // Obter todos os anos letivos para o seletor
    $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();
    $anoLetivoAtual = AnoLectivo::find($anoLetivoId);

    return view('estudante.notas_exame.notas', compact(
        'estudante', 
        'notasExame', 
        'anosLetivos', 
        'anoLetivoAtual'
    ));
}
}