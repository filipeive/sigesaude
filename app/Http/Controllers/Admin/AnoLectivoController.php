<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use Illuminate\Http\Request;

class AnoLectivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anosLectivos = AnoLectivo::orderByDesc('ano')->paginate(10);
        return view('admin.anos_lectivos.index', compact('anosLectivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.anos_lectivos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ano' => 'required|integer|unique:anos_lectivos,ano',
            'status' => 'required|in:Ativo,Inativo',
        ]);

        if ($request->status == 'Ativo') {
            AnoLectivo::where('status', 'Ativo')->update(['status' => 'Inativo']);
        }

        AnoLectivo::create($request->all());

        return redirect()->route('admin.anos-lectivos.index')
            ->with('success', 'Ano Lectivo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AnoLectivo $anos_lectivo)
    {
        return view('admin.anos_lectivos.show', ['anoLectivo' => $anos_lectivo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnoLectivo $anos_lectivo)
    {
        return view('admin.anos_lectivos.edit', ['anoLectivo' => $anos_lectivo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnoLectivo $anos_lectivo)
    {
        $request->validate([
            'ano' => 'required|integer|unique:anos_lectivos,ano,' . $anos_lectivo->id,
            'status' => 'required|in:Ativo,Inativo',
        ]);

        if ($request->status == 'Ativo' && $anos_lectivo->status != 'Ativo') {
            AnoLectivo::where('status', 'Ativo')->update(['status' => 'Inativo']);
        }

        $anos_lectivo->update($request->all());

        return redirect()->route('admin.anos-lectivos.index')
            ->with('success', 'Ano Lectivo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnoLectivo $anos_lectivo)
    {
        if ($anos_lectivo->matriculas()->count() > 0 || $anos_lectivo->turmas()->count() > 0) {
            return redirect()->route('admin.anos-lectivos.index')
                ->with('error', 'Não é possível excluir um ano lectivo que possui matrículas ou turmas associadas.');
        }

        $anos_lectivo->delete();

        return redirect()->route('admin.anos-lectivos.index')
            ->with('success', 'Ano Lectivo excluído com sucesso!');
    }
}
