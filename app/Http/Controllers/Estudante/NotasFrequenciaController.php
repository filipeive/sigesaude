<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\Exame;
use App\Models\AnoLectivo;
use Illuminate\Support\Facades\Auth;

class NotasFrequenciaController extends Controller
{
    /**
     * Página inicial do módulo de notas e frequência
     */
    /* public function index()
    {
        // Obter o estudante autenticado
        $estudante = Estudante::find(Auth::id());

        // Verificar se o estudante foi encontrado
        if (!$estudante) {
            return redirect()->route('login')->with('error', 'Estudante não encontrado.');
        }

        // Obter o ano letivo ativo
        $anoLetivoAtual = AnoLectivo::where('status', 'Ativo')->first();

        // Verificar se o ano letivo ativo foi encontrado
        if (!$anoLetivoAtual) {
            return redirect()->route('home')->with('error', 'Ano letivo ativo não encontrado.');
        }

        // Obter as notas de frequência do estudante para o ano letivo ativo
        $notasFrequencia = NotaFrequencia::where('estudante_id', $estudante->id)
                                        ->where('ano_lectivo_id', $anoLetivoAtual->id)
                                        ->with(['disciplina'])
                                        ->get();

        // Obter todos os anos letivos para o seletor
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();

        return view('estudante.notas_frequencia.index', compact(
            'estudante', 
            'notasFrequencia', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    } */

    /**
     * Exibe as notas e frequência do estudante para o ano letivo selecionado
     */
    public function notasFrequencia(Request $request)
    {
            // Buscar estudante baseado no user_id do usuário autenticado
        $estudante = Estudante::where('user_id', Auth::id())->first();

        if (!$estudante) {
            return redirect()->route('login')->with('error', 'Estudante não encontrado.');
        }

        // Obter o ano letivo selecionado ou o ativo por padrão
        $anoLetivoId = $request->ano_letivo_id ?? AnoLectivo::where('status', 'Ativo')->first()->id;

        // Verificar se o ano letivo ativo foi encontrado
        if (!$anoLetivoId) {
            return redirect()->route('home')->with('error', 'Ano letivo ativo não encontrado.');
        }

        // Obter as notas de frequência do estudante para o ano letivo selecionado
        $notasFrequencia = NotaFrequencia::where('estudante_id', $estudante->id)
                                        ->where('ano_lectivo_id', $anoLetivoId)
                                        ->with(['disciplina', 'notasDetalhadas'])
                                        ->get();

        // Obter todos os anos letivos para o seletor
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();

        // Obter o ano letivo atual selecionado
        $anoLetivoAtual = AnoLectivo::find($anoLetivoId);

        return view('estudante.notas_frequencia.notas', compact(
            'estudante', 
            'notasFrequencia', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    }
}
