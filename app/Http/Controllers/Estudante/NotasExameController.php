<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\ResultadoFinal;
use App\Models\AnoLectivo;
use Illuminate\Support\Facades\Auth;

class NotasExameController extends Controller
{
    /**
     * Página inicial do módulo de notas de exame
     */
    public function index(Request $request)
    {
        return $this->notasExame($request);
    }

    /**
     * Exibe as notas de exame e médias finais do estudante
     */
    public function notasExame(Request $request)
    {
        $estudante = Estudante::where('user_id', Auth::id())
            ->with(['turma.classe.disciplinas'])
            ->first();

        if (!$estudante) {
            return redirect()->route('estudante.create.profile')
                ->with('error', 'Perfil de estudante não encontrado.');
        }

        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();
        $anoSelecionado = $request->ano_lectivo_id 
            ? AnoLectivo::find($request->ano_lectivo_id)
            : $anoAtivo;

        if (!$anoSelecionado) {
            return redirect()->route('estudante.dashboard')
                ->with('error', 'Ano letivo não encontrado.');
        }

        $anosLectivos = AnoLectivo::orderBy('ano', 'desc')->get();

        $disciplinas = collect();
        if ($estudante->turma && $estudante->turma->classe) {
            $disciplinas = $estudante->turma->classe->disciplinas()
                ->with('docente.user')
                ->orderBy('nome')
                ->get();
        }

        $resultados = [];
        foreach ($disciplinas as $disc) {
            $resultado = ResultadoFinal::where('estudante_id', $estudante->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $anoSelecionado->id)
                ->first();

            $resultados[] = [
                'disciplina' => $disc,
                'resultado' => $resultado,
            ];
        }

        return view('estudante.notas_exame.notas', compact(
            'estudante', 
            'resultados', 
            'anosLectivos', 
            'anoSelecionado'
        ));
    }
}