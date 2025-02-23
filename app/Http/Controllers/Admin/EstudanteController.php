<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Estudante, User, Curso, AnoLectivo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EstudanteController extends Controller
{
    public function index()
    {
        $cursos = Curso::pluck('nome', 'id');
        $estudantes = Estudante::with(['user', 'curso', 'anoLectivo'])
            ->when(request('search'), function($query) {
                $search = request('search');
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('matricula', 'like', "%{$search}%");
            })
            ->when(request('curso'), function($query) {
                $query->where('curso_id', request('curso'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.estudantes.index', compact('estudantes', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::pluck('nome', 'id');
        $anosLectivos = AnoLectivo::where('status', 'Ativo')
            ->pluck('ano', 'id');
        
        return view('admin.estudantes.create', compact('cursos', 'anosLectivos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'matricula' => 'required|unique:estudantes,matricula',
            'curso_id' => 'required|exists:cursos,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'contato_emergencia' => 'required|string|max:255',
            'foto_perfil' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telefone' => $validated['telefone'],
                'tipo' => 'estudante',
                'genero' => $validated['genero']
            ]);

            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('perfil', 'public');
                $user->foto_perfil = $path;
                $user->save();
            }

            // Create student
            $estudante = Estudante::create([
                'user_id' => $user->id,
                'matricula' => $validated['matricula'],
                'curso_id' => $validated['curso_id'],
                'ano_lectivo_id' => $validated['ano_lectivo_id'],
                'data_nascimento' => $validated['data_nascimento'],
                'genero' => $validated['genero'],
                'ano_ingresso' => $validated['ano_ingresso'],
                'turno' => $validated['turno'],
                'contato_emergencia' => $validated['contato_emergencia'],
                'status' => 'Ativo'
            ]);

            DB::commit();
            return redirect()->route('admin.estudantes.show', $estudante->id)
                ->with('success', 'Estudante cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar estudante. Por favor, tente novamente.')
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $estudante = Estudante::with([
            'user', 
            'curso', 
            'anoLectivo',
            'matriculas.disciplina',
            'pagamentos'
        ])->findOrFail($id);

        return view('admin.estudantes.show', compact('estudante'));
    }

    public function edit(string $id)
    {
        $estudante = Estudante::with('user')->findOrFail($id);
        $cursos = Curso::pluck('nome', 'id');
        $anosLectivos = AnoLectivo::pluck('ano', 'id');

        return view('admin.estudantes.edit', compact('estudante', 'cursos', 'anosLectivos'));
    }

    public function update(Request $request, string $id)
    {
        $estudante = Estudante::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($estudante->user_id)],
            'telefone' => 'required|string|max:20',
            'matricula' => ['required', Rule::unique('estudantes')->ignore($id)],
            'curso_id' => 'required|exists:cursos,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'status' => 'required|in:Ativo,Inativo,Concluído,Desistente',
            'contato_emergencia' => 'required|string|max:255',
            'foto_perfil' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $estudante->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
                'genero' => $validated['genero']
            ]);

            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('perfil', 'public');
                $estudante->user->foto_perfil = $path;
                $estudante->user->save();
            }

            // Update student
            $estudante->update([
                'matricula' => $validated['matricula'],
                'curso_id' => $validated['curso_id'],
                'ano_lectivo_id' => $validated['ano_lectivo_id'],
                'data_nascimento' => $validated['data_nascimento'],
                'genero' => $validated['genero'],
                'ano_ingresso' => $validated['ano_ingresso'],
                'turno' => $validated['turno'],
                'status' => $validated['status'],
                'contato_emergencia' => $validated['contato_emergencia']
            ]);

            DB::commit();
            return redirect()->route('admin.estudantes.show', $estudante->id)
                ->with('success', 'Dados do estudante atualizados com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar dados do estudante. Por favor, tente novamente.')
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $estudante = Estudante::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Delete associated user
            $estudante->user->delete();
            // The student record will be deleted automatically due to cascade
            
            DB::commit();
            return redirect()->route('admin.estudantes.index')
                ->with('success', 'Estudante removido com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao remover estudante. Por favor, tente novamente.');
        }
    }
}