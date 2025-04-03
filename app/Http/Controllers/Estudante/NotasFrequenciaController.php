<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\MediaFinal;
use App\Models\NotaFrequencia;
use App\Models\NotaDetalhada;
use App\Models\AnoLectivo;
use App\Models\Inscricao;
use App\Models\InscricaoDisciplina;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotasFrequenciaController extends Controller
{
    public function notasFrequencia(Request $request)
    {
        // Buscar estudante baseado no user_id do usuário autenticado
        $estudante = Estudante::where('user_id', Auth::id())->first();

        if (!$estudante) {
            return redirect()->route('login')->with('error', 'Estudante não encontrado.');
        }

        // Obter o ano letivo selecionado ou o ativo por padrão
        $anoLetivoAtual = $request->ano_letivo_id 
            ? AnoLectivo::find($request->ano_letivo_id)
            : AnoLectivo::where('status', 'Ativo')->first();

        if (!$anoLetivoAtual) {
            return redirect()->route('home')->with('error', 'Ano letivo ativo não encontrado.');
        }

        // Obter todas as inscrições do estudante para o ano letivo atual
        $inscricoes = Inscricao::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->where('status', 'Confirmada')
            ->get();

        // Obter todas as disciplinas em que o estudante está inscrito
        $inscricaoDisciplinas = InscricaoDisciplina::whereIn('inscricao_id', $inscricoes->pluck('id'))
            ->with('disciplina')
            ->get();

        // Obter as notas de frequência do estudante para o ano letivo atual
        $notasFrequencia = NotaFrequencia::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->with(['disciplina', 'notasDetalhadas'])
            ->get();

        // Obter as médias finais do estudante para o ano letivo atual
        $mediasFinais = MediaFinal::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->get();

        // Criar uma coleção que combina inscrições e notas
        $disciplinasComNotas = $inscricaoDisciplinas->map(function ($inscricaoDisciplina) use ($notasFrequencia, $mediasFinais) {
            // Buscar a nota de frequência correspondente à disciplina
            $notaFrequencia = $notasFrequencia->firstWhere('disciplina_id', $inscricaoDisciplina->disciplina_id);
            
            // Buscar a média final correspondente à disciplina
            $mediaFinal = $mediasFinais->firstWhere('disciplina_id', $inscricaoDisciplina->disciplina_id);
            
            // Organizar as notas detalhadas em um array associativo por tipo
            $notasDetalhadas = [];
            if ($notaFrequencia && $notaFrequencia->notasDetalhadas) {
                foreach ($notaFrequencia->notasDetalhadas as $notaDetalhada) {
                    $notasDetalhadas[$notaDetalhada->tipo] = $notaDetalhada->nota;
                }
            }
            
            return [
                'disciplina' => $inscricaoDisciplina->disciplina,
                'inscricao_id' => $inscricaoDisciplina->inscricao_id,
                'tipo_inscricao' => $inscricaoDisciplina->tipo,
                'nota_frequencia' => $notaFrequencia ? $notaFrequencia->nota : ($mediaFinal ? $mediaFinal->media : null),
                'status' => $mediaFinal ? $mediaFinal->status : ($notaFrequencia ? $notaFrequencia->status : 'Pendente'),
                'notas_detalhadas' => $notasDetalhadas,
                'tem_nota' => ($notaFrequencia || $mediaFinal) ? true : false
            ];
        });

        // Obter todos os anos letivos para o seletor
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();

        return view('estudante.notas_frequencia.notas', compact(
            'estudante', 
            'disciplinasComNotas', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    }
}