<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Disciplina, Classe, Docente, Nivel};
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $disciplinas = Disciplina::with(['classe', 'docente.user', 'nivel'])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })
            ->when($request->input('classe_id'), function($query, $classeId) {
                $query->where('classe_id', $classeId);
            })
            ->orderBy('nome')
            ->paginate(10);

        $classes = Classe::orderBy('nivel')->pluck('nome', 'id');

        return view('admin.disciplinas.index', compact('disciplinas', 'classes'));
    }

    public function create()
    {
        $classes = Classe::orderBy('nivel')->get();
        $docentes = Docente::with('user')->get();
        $niveis = Nivel::pluck('nome', 'id');
        $disciplina = new Disciplina();
        return view('admin.disciplinas.create', compact('classes', 'docentes', 'niveis', 'disciplina'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'classe_id' => 'required|exists:classes,id',
            'docente_id' => 'required|exists:docentes,id',
            'nivel_id' => 'required|exists:niveis,id',
            'carga_horaria' => 'nullable|string|max:50',
        ]);

        Disciplina::create($request->only(['nome', 'classe_id', 'docente_id', 'nivel_id', 'carga_horaria']));

        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina criada com sucesso!');
    }

    public function show($id)
    {
        $disciplina = Disciplina::with(['classe', 'docente.user', 'nivel'])->find($id);

        if (!$disciplina) {
            return redirect()->back()->with('error', 'Disciplina não encontrada.');
        }

        return view('admin.disciplinas.show', compact('disciplina'));
    }

    public function edit($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $classes = Classe::orderBy('nivel')->get();
        $docentes = Docente::with('user')->get();
        $niveis = Nivel::all();
        return view('admin.disciplinas.edit', compact('disciplina', 'classes', 'docentes', 'niveis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'classe_id' => 'required|exists:classes,id',
            'docente_id' => 'required|exists:docentes,id',
            'nivel_id' => 'required|exists:niveis,id',
            'carga_horaria' => 'nullable|string|max:50',
        ]);

        $disciplina = Disciplina::findOrFail($id);
        $disciplina->update($request->only(['nome', 'classe_id', 'docente_id', 'nivel_id', 'carga_horaria']));

        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->delete();
        return redirect()->route('admin.disciplinas.index')
                         ->with('success', 'Disciplina removida com sucesso!');
    }
}