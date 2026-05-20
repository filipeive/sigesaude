<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $classes = Classe::withCount(['turmas', 'disciplinas'])
            ->when($search, function ($query, $search) {
                return $query->where('nome', 'like', "%{$search}%");
            })
            ->orderBy('nivel')
            ->paginate(10);

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:classes,nome',
            'nivel' => 'required|integer|min:1|max:12',
            'descricao' => 'nullable|string',
        ]);

        Classe::create($request->only(['nome', 'nivel', 'descricao']));

        return redirect()->route('admin.classes.index')
                         ->with('success', 'Classe criada com sucesso!');
    }

    public function show(Classe $classe)
    {
        $classe->load(['turmas.anoLectivo', 'disciplinas.docente']);
        return view('admin.classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        return view('admin.classes.edit', compact('classe'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:classes,nome,' . $classe->id,
            'nivel' => 'required|integer|min:1|max:12',
            'descricao' => 'nullable|string',
        ]);

        $classe->update($request->only(['nome', 'nivel', 'descricao']));

        return redirect()->route('admin.classes.index')
                         ->with('success', 'Classe atualizada com sucesso!');
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();
        return redirect()->route('admin.classes.index')
                         ->with('success', 'Classe removida com sucesso!');
    }
}
