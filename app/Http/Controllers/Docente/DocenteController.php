<?php
namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Docente;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        $disciplinas = Disciplina::where('docente_id', $docente->id)
            ->get();
        $totalDisciplinas = $disciplinas->count();
        
        // Contagem de estudantes por disciplina
        $estudantesPorDisciplina = [];
        foreach ($disciplinas as $disciplina) {
            $estudantesPorDisciplina[$disciplina->id] = $disciplina->inscricaoDisciplinas()->count();
        }
        
        return view('docente.dashboard', compact('docente', 'disciplinas', 'totalDisciplinas', 'estudantesPorDisciplina'));
    }
    
    public function disciplinas()
    {
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        $disciplinas = Disciplina::where('docente_id', $docente->id)->get();
        
        return view('docente.disciplinas', compact('disciplinas'));
    }
    public function show($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        
        // Verificar se o docente está autorizado a ver esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.disciplinas')
                ->with('error', 'Você não está autorizado a acessar esta disciplina.');
        }
        
        return view('docente.disciplina', compact('disciplina'));
    }
    public function edit($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        
        // Verificar se o docente está autorizado a editar esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.disciplinas')
                ->with('error', 'Você não está autorizado a editar esta disciplina.');
        }
        
        return view('docente.edit_disciplina', compact('disciplina'));
    }
    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::findOrFail($id);
        
        // Verificar se o docente está autorizado a editar esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.disciplinas')
                ->with('error', 'Você não está autorizado a editar esta disciplina.');
        }
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ano_lectivo_id' => 'required|exists:ano_lectivos,id',
        ]);
        
        $disciplina->update($request->all());
        
        return redirect()->route('docente.disciplinas')
            ->with('success', 'Disciplina atualizada com sucesso.');
    }
    public function destroy($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        
        // Verificar se o docente está autorizado a excluir esta disciplina
        $user = Auth::user();
        $docente = Docente::where('user_id', $user->id)->first();
        
        if ($disciplina->docente_id != $docente->id) {
            return redirect()->route('docente.disciplinas')
                ->with('error', 'Você não está autorizado a excluir esta disciplina.');
        }
        
        $disciplina->delete();
        
        return redirect()->route('docente.disciplinas')
            ->with('success', 'Disciplina excluída com sucesso.');
    }
    //profile docente
    
}