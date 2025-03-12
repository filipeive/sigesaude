<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Matricula, Estudante, Disciplina};
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Exibe a lista de matrículas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $matriculas = Matricula::with(['estudante.user', 'disciplina'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('estudante.user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhereHas('disciplina', function ($query) use ($search) {
                    $query->where('nome', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return view('admin.matriculas.index', compact('matriculas'));
    }

    /**
     * Exibe o formulário de criação de matrícula.
     */
    public function create()
    {
        $estudantes = Estudante::all();
        $disciplinas = Disciplina::all();
        return view('admin.matriculas.create', compact('estudantes', 'disciplinas'));
    }

    /**
     * Armazena uma nova matrícula no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
        ]);

        Matricula::create($request->only(['estudante_id', 'disciplina_id']));

        return redirect()->route('admin.matriculas.index')
                         ->with('success', 'Matrícula criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma matrícula.
     */
    public function show(Matricula $matricula)
    {
        $matricula->load(['estudante.user', 'disciplina']);
        return view('admin.matriculas.show', compact('matricula'));
    }

    /**
     * Exibe o formulário de edição de matrícula.
     */
    public function edit(Matricula $matricula)
    {
        $estudantes = Estudante::all();
        $disciplinas = Disciplina::all();
        return view('admin.matriculas.edit', compact('matricula', 'estudantes', 'disciplinas'));
    }

    /**
     * Atualiza uma matrícula no banco de dados.
     */
    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
        ]);

        $matricula->update($request->only(['estudante_id', 'disciplina_id']));

        return redirect()->route('admin.matriculas.index')
                         ->with('success', 'Matrícula atualizada com sucesso!');
    }

    /**
     * Remove uma matrícula do banco de dados.
     */
    public function destroy(Matricula $matricula)
    {
        $matricula->delete();
        return redirect()->route('admin.matriculas.index')
                         ->with('success', 'Matrícula removida com sucesso!');
    }
}