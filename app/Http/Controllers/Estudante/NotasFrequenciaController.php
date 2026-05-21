<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\NotaFrequencia;
use App\Models\ResultadoFinal;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;

class NotasFrequenciaController extends Controller
{
    /**
     * Boletim trimestral do estudante — SNE Moçambique
     * Mostra ACS1, ACS2, ACS3, ACP, ACF e MT por trimestre para cada disciplina
     */
    public function notasFrequencia(Request $request)
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
                ->with('error', 'Nenhum ano lectivo encontrado.');
        }

        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        // Get all disciplines for the student's class
        $disciplinas = collect();
        if ($estudante->turma && $estudante->turma->classe) {
            $disciplinas = $estudante->turma->classe->disciplinas()
                ->with('docente.user')
                ->orderBy('nome')
                ->get();
        }

        // Build the boletim data: for each discipline, get trimester grades + resultado final
        $boletim = [];
        foreach ($disciplinas as $disc) {
            $trimestres = NotaFrequencia::where('estudante_id', $estudante->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $anoSelecionado->id)
                ->get()
                ->keyBy('trimestre');

            $resultado = ResultadoFinal::where('estudante_id', $estudante->id)
                ->where('disciplina_id', $disc->id)
                ->where('ano_lectivo_id', $anoSelecionado->id)
                ->first();

            $boletim[] = [
                'disciplina' => $disc,
                't1' => $trimestres->get(1),
                't2' => $trimestres->get(2),
                't3' => $trimestres->get(3),
                'resultado' => $resultado,
            ];
        }

        return view('estudante.notas_frequencia.notas', compact(
            'estudante', 'boletim', 'anosLectivos', 'anoSelecionado', 'disciplinas'
        ));
    }
}