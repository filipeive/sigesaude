<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Classe;
use App\Models\AnoLectivo;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $turmas = Turma::with(['classe', 'anoLectivo'])
            ->withCount('estudantes')
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%")
                    ->orWhereHas('classe', function($q) use ($search) {
                        $q->where('nome', 'like', "%{$search}%");
                    });
            })
            ->when($request->input('classe_id'), function($query, $classeId) {
                $query->where('classe_id', $classeId);
            })
            ->when($request->input('ano_lectivo_id'), function($query, $anoId) {
                $query->where('ano_lectivo_id', $anoId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $classes = Classe::orderBy('nivel')->pluck('nome', 'id');
        $anosLectivos = AnoLectivo::orderByDesc('ano')->pluck('ano', 'id');

        return view('admin.turmas.index', compact('turmas', 'classes', 'anosLectivos'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        return view('admin.turmas.create', compact('classes', 'anosLectivos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'classe_id' => 'required|exists:classes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'descricao' => 'nullable|string',
        ]);

        $classe = Classe::findOrFail($request->classe_id);

        Turma::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ano_serie' => $classe->nivel,
            'classe_id' => $request->classe_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
        ]);

        return redirect()->route('admin.turmas.index')
                         ->with('success', 'Turma criada com sucesso!');
    }

    public function show(Turma $turma)
    {
        $turma->load(['classe.disciplinas.docente', 'anoLectivo', 'estudantes.user']);
        return view('admin.turmas.show', compact('turma'));
    }

    public function edit(Turma $turma)
    {
        $classes = Classe::orderBy('nivel')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        return view('admin.turmas.edit', compact('turma', 'classes', 'anosLectivos'));
    }

    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'classe_id' => 'required|exists:classes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'descricao' => 'nullable|string',
        ]);

        $classe = Classe::findOrFail($request->classe_id);

        $turma->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'ano_serie' => $classe->nivel,
            'classe_id' => $request->classe_id,
            'ano_lectivo_id' => $request->ano_lectivo_id,
        ]);

        return redirect()->route('admin.turmas.index')
                         ->with('success', 'Turma atualizada com sucesso!');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();
        return redirect()->route('admin.turmas.index')
                         ->with('success', 'Turma removida com sucesso!');
    }
}