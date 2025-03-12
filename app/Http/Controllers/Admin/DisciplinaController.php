<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Disciplina, Curso, Docente, Nivel};
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    /**
     * Exibe a lista de disciplinas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $disciplinas = Disciplina::with(['curso', 'docente', 'nivel'])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.disciplinas.index', compact('disciplinas'));
    }

    /**
     * Exibe o formulário de criação de disciplina.
     */
    public function create()
    {
        $cursos = Curso::all();
        $docentes = Docente::all();
        $niveis = Nivel::pluck('nome', 'id');
        $disciplina = new Disciplina();
        return view('admin.disciplinas.create', compact('cursos', 'docentes', 'niveis', 'disciplina'));
    }    
    
    /**
     * Armazena uma nova disciplina no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'docente_id' => 'required|exists:docentes,id',
            'nivel_id' => 'required|exists:niveis,id',
        ]);

        Disciplina::create($request->only(['nome', 'curso_id', 'docente_id', 'nivel_id']));

        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina criada com sucesso!');
    }

    /**
     * Exibe os detalhes de uma disciplina.
     */
    public function show($id)
    {
        $disciplina = Disciplina::with(['curso.docentes', 'nivel'])->find($id);

        if (!$disciplina) {
            return redirect()->back()->with('error', 'Disciplina não encontrada.');
        }

        return view('admin.disciplinas.show', compact('disciplina'));
    }

    /**
     * Exibe o formulário de edição de disciplina.
     */
    public function edit($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $cursos = Curso::all();
        $docentes = Docente::all();
        $niveis = Nivel::all(); // Certifique-se de obter todos os objetos Nivel
        return view('admin.disciplinas.edit', compact('disciplina', 'cursos', 'docentes', 'niveis'));
    }

    /**
     * Atualiza uma disciplina no banco de dados.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'docente_id' => 'required|exists:docentes,id',
            'nivel_id' => 'required|exists:niveis,id',
        ]);

        $disciplina = Disciplina::findOrFail($id);
        $disciplina->update($request->only(['nome', 'curso_id', 'docente_id', 'nivel_id']));

        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina atualizada com sucesso!');
    }

    /**
     * Remove uma disciplina do banco de dados.
     */
    public function destroy($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();
        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina removida com sucesso!');
    }
}