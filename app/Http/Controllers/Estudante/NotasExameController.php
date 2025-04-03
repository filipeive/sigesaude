<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Estudante, 
    NotaExame, 
    MediaFinal, 
    AnoLectivo,
    Inscricao,
    InscricaoDisciplina
};
use Illuminate\Support\Facades\Auth;

class NotasExameController extends Controller
{
    /**
     * Página inicial do módulo de notas de exame
     */
    public function index(Request $request)
    {
        // Redirecionamos para o método notasExame para manter a consistência
        return $this->notasExame($request);
    }

    /**
     * Exibe as notas de exame e médias finais do estudante
     */
    public function notasExame(Request $request)
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

        // Obter as notas de exame do estudante
        $notasExame = NotaExame::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->with(['disciplina'])
            ->get();

        // Obter as médias finais
        $mediasFinais = MediaFinal::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoLetivoAtual->id)
            ->get();

        // Criar uma coleção que combina inscrições e notas
        $disciplinasComNotas = $inscricaoDisciplinas->map(function ($inscricaoDisciplina) use ($notasExame, $mediasFinais) {
            // Buscar a nota de exame correspondente à disciplina
            $notaExame = $notasExame->firstWhere('disciplina_id', $inscricaoDisciplina->disciplina_id);
            
            // Buscar a média final correspondente à disciplina
            $mediaFinal = $mediasFinais->firstWhere('disciplina_id', $inscricaoDisciplina->disciplina_id);
            
            return [
                'disciplina' => $inscricaoDisciplina->disciplina,
                'inscricao_id' => $inscricaoDisciplina->inscricao_id,
                'tipo_inscricao' => $inscricaoDisciplina->tipo,
                'media_freq' => $notaExame ? $notaExame->media_freq : null,
                'exame_normal' => $notaExame ? $notaExame->exame_normal : null,
                'exame_recorrencia' => $notaExame ? $notaExame->exame_recorrencia : null,
                'media_final' => $mediaFinal ? $mediaFinal->media : ($notaExame ? $notaExame->media_final : null),
                'resultado_final' => $mediaFinal ? $mediaFinal->status : ($notaExame ? $notaExame->resultado_final : 'Pendente'),
                'tem_nota' => ($notaExame || $mediaFinal) ? true : false
            ];
        });

        // Obter todos os anos letivos para o seletor
        $anosLetivos = AnoLectivo::orderBy('ano', 'desc')->get();

        return view('estudante.notas_exame.notas', compact(
            'estudante', 
            'disciplinasComNotas', 
            'anosLetivos', 
            'anoLetivoAtual'
        ));
    }
}