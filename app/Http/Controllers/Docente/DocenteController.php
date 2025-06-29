<?php
namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disciplina;
use App\Models\Docente;
use App\Models\Departamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocenteController extends Controller
{
    /**
     * Verifica se o docente existe e retorna o docente ou redireciona
     */
    private function getDocenteOrRedirect()
    {
        $docente = Docente::where('user_id', Auth::id())->first();
        
        if (!$docente) {
            return redirect()->route('docente.profile.create')
                ->with('warning', 'Perfil de docente não encontrado. Por favor, complete seu cadastro.');
        }
        
        return $docente;
    }

    public function index()
    {
        $docente = $this->getDocenteOrRedirect();
        
        // Se a função retornou uma resposta de redirecionamento, retorne-a
        if (!($docente instanceof Docente)) {
            return $docente;
        }

        $disciplinas = Disciplina::where('docente_id', $docente->id)->get();
        $totalDisciplinas = $disciplinas->count();
        
        $estudantesPorDisciplina = [];
        foreach ($disciplinas as $disciplina) {
            $estudantesPorDisciplina[$disciplina->id] = $disciplina->inscricaoDisciplinas()->count();
        }
        
        return view('docente.dashboard', compact('docente', 'disciplinas', 'totalDisciplinas', 'estudantesPorDisciplina'));
    }

    public function createProfile()
    {
        // Verifica se já existe um perfil
        if (Docente::where('user_id', Auth::id())->exists()) {
            return redirect()->route('docente.dashboard')
                ->with('info', 'Você já possui um perfil de docente.');
        }

        // Verifica se o usuário é do tipo docente
        if (Auth::user()->tipo !== 'docente') {
            return redirect()->route('login')
                ->with('error', 'Acesso não autorizado.');
        }

        $departamentos = Departamento::all();
        return view('docente.profile.create', compact('departamentos'));
    }

    public function storeProfile(Request $request)
    {
        // Verificar se já existe um perfil
        if (Docente::where('user_id', Auth::id())->exists()) {
            return redirect()->route('docente.dashboard')
                ->with('info', 'Seu perfil já está configurado.');
        }

        $request->validate([
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'required|integer|min:0',
            'departamento_id' => 'required|exists:departamentos,id',
            'telefone' => 'required|string|max:20',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'foto_perfil' => 'nullable|image|max:2048'
        ]);

        try {
            // Iniciar transação
            \DB::beginTransaction();

            // Atualizar informações do usuário
            $user = Auth::user();
            $userData = $request->only(['telefone', 'genero']);
            
            if ($request->hasFile('foto_perfil')) {
                if ($user->foto_perfil) {
                    Storage::delete('public/fotos_perfil/' . $user->foto_perfil);
                }
                $path = $request->file('foto_perfil')->store('public/fotos_perfil');
                $userData['foto_perfil'] = basename($path);
            }
            
            $user->update($userData);

            // Criar perfil do docente
            Docente::create([
                'user_id' => $user->id,
                'formacao' => $request->formacao,
                'anos_experiencia' => $request->anos_experiencia,
                'departamento_id' => $request->departamento_id,
                'status' => 'Ativo'
            ]);

            \DB::commit();

            return redirect()->route('docente.dashboard')
                ->with('success', 'Perfil de docente criado com sucesso!');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Erro ao criar perfil. Por favor, tente novamente.');
        }
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