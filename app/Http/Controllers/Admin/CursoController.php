<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Exibe a lista de cursos.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cursos = Curso::withCount(['estudantes', 'disciplinas'])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.cursos.index', compact('cursos'));
    }

    /**
     * Exibe o formulário de criação de curso.
     */
    public function create()
    {
        return view('admin.cursos.create');
    }

    /**
     * Armazena um novo curso no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Curso::create($request->only(['nome', 'descricao']));

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um curso.
     */
    public function show(Curso $curso)
    {
        return view('admin.cursos.show', compact('curso'));
    }

    /**
     * Exibe o formulário de edição de curso.
     */
    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        return view('admin.cursos.edit', compact('curso'));
    }

    /**
     * Atualiza um curso no banco de dados.
     */
    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $curso->update($request->only(['nome', 'descricao']));

        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove um curso do banco de dados.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('admin.cursos.index')
                         ->with('success', 'Curso removido com sucesso!');
    }
}