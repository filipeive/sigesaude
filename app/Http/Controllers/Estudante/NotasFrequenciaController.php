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
    public function index()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();
        
        return view('estudante.notas_frequencia.index', compact('estudante', 'anosLetivos'));
    }

    /**
     * Exibe as notas e frequência do estudante
     */
    public function notasFrequencia(Request $request)
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        $anoLetivoId = $request->ano_letivo_id ?? AnoLectivo::where('status', 'Ativo')->first()->id;
        
        // Obter todas as matrículas do estudante
        $matriculas = Matricula::where('estudante_id', $estudante->id)
                               ->with(['disciplina'])
                               ->get();
        
        // Obter as notas de frequência
        $notasFrequencia = NotaFrequencia::where('estudante_id', $estudante->id)
                                        ->where('ano_lectivo_id', $anoLetivoId)
                                        ->with(['disciplina'])
                                        ->get();
        
        // Obter os exames
        $exames = Exame::where('estudante_id', $estudante->id)
                       ->where('ano_lectivo_id', $anoLetivoId)
                       ->with(['disciplina'])
                       ->get();
        
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();
        $anoLetivoAtual = AnoLectivo::find($anoLetivoId);
        
        return view('estudante.notas_frequencia.notas', compact(
            'estudante', 
            'matriculas', 
            'notasFrequencia', 
            'exames', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    }
}