<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Inscricao;
use App\Models\InscricaoDisciplina;
use App\Models\Matricula;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $matriculas = Matricula::with(['estudante.user', 'turma', 'anoLectivo'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('estudante.user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhereHas('turma', function ($query) use ($search) {
                    $query->where('nome', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        $estudantes = Estudante::with('user')->whereDoesntHave('matriculas', function ($q) {
            $q->where('status', 'Ativo');
        })->get();
        $turmas = Turma::orderBy('ano_serie')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('admin.matriculas.create', compact('estudantes', 'turmas', 'anosLectivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'turma_id' => 'required|exists:turmas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'valor' => 'nullable|numeric|min:0',
            'data_matricula' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->only(['estudante_id', 'turma_id', 'ano_lectivo_id', 'valor', 'data_matricula', 'observacoes', 'status']);

        if (! $request->has('status')) {
            $data['status'] = 'Pendente';
        }

        $data['data_matricula'] = $data['data_matricula'] ?? now()->toDateString();

        // Gerar referência numérica única (ATM Style)
        $data['referencia'] = Matricula::gerarReferencia();

        DB::transaction(function () use ($data, $request) {
            Matricula::create($data);

            $estudante = Estudante::find($request->estudante_id);
            $estudante->update([
                'turma_id' => $request->turma_id,
                'ano_lectivo_id' => $request->ano_lectivo_id,
                'status' => $data['status'] === 'Ativo' ? 'Ativo' : $estudante->status,
            ]);

            // Se a matrícula ficou Ativa, matricular automaticamente o estudante
            // nas disciplinas da classe / turma para o ano lectivo em questão.
            if ($data['status'] === 'Ativo' && $estudante->turma) {
                $disciplinasIds = Disciplina::where('classe_id', $estudante->turma->classe_id)
                    ->pluck('id')
                    ->all();

                $inscricao = Inscricao::firstOrCreate(
                    [
                        'estudante_id' => $estudante->id,
                        'ano_lectivo_id' => $request->ano_lectivo_id,
                    ],
                    ['semestre' => 1, 'status' => 'Confirmada', 'referencia' => 'AUTO-'.Str::random(8)]
                );

                foreach ($disciplinasIds as $dId) {
                    InscricaoDisciplina::firstOrCreate(
                        [
                            'inscricao_id' => $inscricao->id,
                            'disciplina_id' => $dId,
                        ],
                        ['tipo' => 'Normal']
                    );
                }
            }
        });

        return redirect()->route('admin.matriculas.index')
            ->with('success', 'Matrícula criada com sucesso!');
    }

    public function show(Matricula $matricula)
    {
        $matricula->load(['estudante.user', 'turma', 'anoLectivo']);

        return view('admin.matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula)
    {
        $matricula->load(['estudante.user', 'turma', 'anoLectivo']);
        $turmas = Turma::orderBy('ano_serie')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('admin.matriculas.edit', compact('matricula', 'turmas', 'anosLectivos'));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'valor' => 'nullable|numeric|min:0',
            'data_matricula' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($matricula, $request) {
            $matricula->update($request->only(['turma_id', 'ano_lectivo_id', 'valor', 'data_matricula', 'observacoes', 'status']));

            $estudante = Estudante::find($matricula->estudante_id);
            $estudante->update([
                'turma_id' => $request->turma_id,
                'ano_lectivo_id' => $request->ano_lectivo_id,
                'status' => $request->status === 'Ativo' ? 'Ativo' : $estudante->status,
            ]);
        });

        return redirect()->route('admin.matriculas.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy(Matricula $matricula)
    {
        DB::transaction(function () use ($matricula) {
            $estudante = $matricula->estudante;
            $wasCurrentAcademicPlacement = $estudante
                && (int) $estudante->turma_id === (int) $matricula->turma_id
                && (int) $estudante->ano_lectivo_id === (int) $matricula->ano_lectivo_id;

            if ($matricula->comprovativo && Storage::disk('public')->exists($matricula->comprovativo)) {
                Storage::disk('public')->delete($matricula->comprovativo);
            }

            $matricula->delete();

            if ($estudante && $wasCurrentAcademicPlacement) {
                $matriculaAtiva = $estudante->matriculas()
                    ->where('status', 'Ativo')
                    ->latest('data_matricula')
                    ->latest('id')
                    ->first();

                $estudante->update([
                    'turma_id' => $matriculaAtiva?->turma_id,
                    'ano_lectivo_id' => $matriculaAtiva?->ano_lectivo_id,
                    'status' => $matriculaAtiva ? 'Ativo' : 'Inativo',
                ]);
            }
        });

        return redirect()->route('admin.matriculas.index')
            ->with('success', 'Matrícula removida com sucesso!');
    }

    /**
     * Gera o documento de matrícula em PDF.
     */
    public function downloadPdf(Matricula $matricula)
    {
        $matricula->load(['estudante.user', 'turma', 'anoLectivo']);

        $pdf = Pdf::loadView('pdf.guia_matricula', compact('matricula'));

        return $pdf->download('Guia_Matricula_'.$matricula->referencia.'.pdf');
    }

    public function confirmar(Matricula $matricula)
    {
        $matricula->update([
            'status' => 'Ativo',
            'data_confirmacao' => now(),
        ]);

        $estudante = $matricula->estudante;
        if ($estudante) {
            $estudante->update(['status' => 'Ativo']);
        }

        return back()->with('success', 'Matrícula confirmada com sucesso!');
    }

    public function uploadComprovativo(Request $request, Matricula $matricula)
    {
        $request->validate([
            'comprovativo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('comprovativo')) {
            $path = $request->file('comprovativo')->store('comprovativos', 'public');
            $matricula->update(['comprovativo' => $path]);
        }

        return back()->with('success', 'Comprovativo enviado com sucesso!');
    }
}
