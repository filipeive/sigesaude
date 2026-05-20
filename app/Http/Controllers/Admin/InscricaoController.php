<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Estudante;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InscricaoController extends Controller
{
    public function index()
    {
        $inscricoesPendentes = Inscricao::where('status', 'Pendente')
            ->with(['estudante.user', 'estudante.turma', 'anoLectivo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $inscricoesConfirmadas = Inscricao::where('status', 'Confirmada')
            ->with(['estudante.user', 'estudante.turma', 'anoLectivo'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('admin.inscricoes.index', compact('inscricoesPendentes', 'inscricoesConfirmadas'));
    }

    public function create()
    {
        $estudantes = Estudante::with('user', 'turma')->orderBy('ano_ingresso', 'desc')->get();
        return view('admin.inscricoes.create', compact('estudantes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estudante_id'     => 'required|exists:estudantes,id',
            'ano_lectivo_id'   => 'required|exists:anos_lectivos,id',
            'semestre'         => 'required|in:1,2',
            'valor'            => 'nullable|numeric|min:0',
            'referencia'       => 'nullable|string|max:255',
            'observacoes'      => 'nullable|string',
        ]);

        $inscricao = Inscricao::create([
            'estudante_id'     => $validated['estudante_id'],
            'ano_lectivo_id'   => $validated['ano_lectivo_id'],
            'semestre'         => $validated['semestre'],
            'status'           => 'Pendente',
            'referencia'       => $validated['referencia'] ?? 'INS-' . Str::random(8),
            'data_inscricao'   => now(),
        ]);

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição criada com sucesso! Aguarde a confirmação da secretaria.');
    }

    public function show($id)
    {
        $inscricao = Inscricao::with(['estudante.user', 'estudante.turma', 'estudante.turma.classe', 'anoLectivo'])
            ->findOrFail($id);

        return view('admin.inscricoes.show', compact('inscricao'));
    }

    public function aprovar(Request $request, $id)
    {
        $inscricao = Inscricao::findOrFail($id);
        $inscricao->update(['status' => 'Confirmada']);

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição aprovada com sucesso! O estudante foi confirmado para o semestre/ano lectivo.');
    }

    public function recusar(Request $request, $id)
    {
        $inscricao = Inscricao::findOrFail($id);
        $inscricao->update(['status' => 'Cancelada']);

        return redirect()->route('admin.inscricoes.index')
            ->with('success', 'Inscrição recusada com sucesso.');
    }
}
