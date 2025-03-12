<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Inscricao;
use App\Models\InscricaoDisciplina;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InscricaoController extends Controller
{
    public function index()
    {
        // Inscrições Pendentes
        $inscricoesPendentes = Inscricao::where('status', 'Pendente')
            ->with(['estudante', 'anoLectivo'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Inscrições Confirmadas
        $inscricoesConfirmadas = Inscricao::where('status', 'Confirmada')
            ->with(['estudante', 'anoLectivo'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('admin.inscricoes.index', compact('inscricoesPendentes', 'inscricoesConfirmadas'));
    }

        
    public function create()
    {
        $estudantes = Estudante::all();
        $anoAtual = AnoLectivo::where('status', 'Ativo')->first();
        $disciplinas = Disciplina::all();
    
        return view('admin.inscricoes.create', compact('estudantes', 'anoAtual', 'disciplinas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'disciplinas' => 'required|array',
            'disciplinas.*' => 'exists:disciplinas,id',
        ]);

        $inscricao = Inscricao::create([
            'estudante_id' => $request->estudante_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
            'semestre' => $request->semestre,
            'status' => 'Pendente',
            'referencia' => 'INS-' . Str::random(8),
            'data_inscricao' => now(),
        ]);

        foreach ($request->disciplinas as $disciplinaId) {
            InscricaoDisciplina::create([
                'inscricao_id' => $inscricao->id,
                'disciplina_id' => $disciplinaId,
                'tipo' => 'Normal',
            ]);
        }

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição criada com sucesso! Aguarde a confirmação.');
    }

    public function show($id)
    {
        // Carregar a inscrição com relacionamentos
        $inscricao = Inscricao::with([
            'estudante.user', 
            'estudante.curso', 
            'estudante.nivel', 
            'anoLectivo', 
            'disciplinas'
        ])->findOrFail($id);

        return view('admin.inscricoes.show', compact('inscricao'));
    }

    public function aprovar(Request $request, $id)
    {
        $inscricao = Inscricao::findOrFail($id);

        // Atualizar status da inscrição
        $inscricao->status = 'Confirmada';
        $inscricao->save();

        // Criar matrículas para cada disciplina da inscrição
        foreach ($inscricao->disciplinas as $disciplina) {
            // Verificar se já existe uma matrícula para esta disciplina
            $matriculaExistente = Matricula::where('estudante_id', $inscricao->estudante_id)
                ->where('disciplina_id', $disciplina->id)
                ->first();

            // Se não existir, criar uma nova matrícula
            if (!$matriculaExistente) {
                Matricula::create([
                    'estudante_id' => $inscricao->estudante_id,
                    'disciplina_id' => $disciplina->id,
                ]);
            }
        }

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição aprovada com sucesso!');
    }

    public function recusar(Request $request, $id)
    {
        $inscricao = Inscricao::findOrFail($id);

        // Atualizar status da inscrição
        $inscricao->status = 'Cancelada';
        $inscricao->save();

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição recusada com sucesso!');
    }
}