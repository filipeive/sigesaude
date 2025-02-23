<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Docente, User, Departamento, Curso};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    public function index()
    {
        $docentes = Docente::with(['user', 'departamento'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cursos = Curso::pluck('nome', 'id'); // Certifique-se de que existe um Model Curso

        return view('admin.docentes.index', compact('docentes', 'cursos'));
    }


    public function create()
    {
        $departamentos = Departamento::pluck('nome', 'id');
        return view('admin.docentes.create', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'departamento_id' => 'required|exists:departamentos,id',
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'nullable|integer',
            'status' => 'required|in:Ativo,Inativo',
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
                'tipo' => 'docente',
            ]);
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('perfil', 'public');
                $user->foto_perfil = $path;
                $user->save();
            }
            // Create docente
            $docente = Docente::create([
                'user_id' => $user->id,
                'departamento_id' => $validated['departamento_id'],
                'formacao' => $validated['formacao'],
                'anos_experiencia' => $validated['anos_experiencia'],
                'status' => $validated['status']
            ]);
            DB::commit();
            return redirect()->route('admin.docentes.show', $docente->id)
                ->with('success', 'Docente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao cadastrar docente. Por favor, tente novamente.')
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $docente = Docente::with(['user', 'departamento'])->findOrFail($id);
        return view('admin.docentes.show', compact('docente'));
    }

    public function edit(string $id)
    {
        $docente = Docente::with('user')->findOrFail($id);
        $cursos = Curso::pluck('nome', 'id'); // Certifique-se de que existe um Model Curso
        $departamentos = Departamento::pluck('nome', 'id');
        return view('admin.docentes.edit', compact('docente', 'departamentos', 'cursos'));
    }

    public function update(Request $request, string $id)
    {
        $docente = Docente::with('user')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($docente->user_id)],
            'telefone' => 'required|string|max:20',
            'departamento_id' => 'required|exists:departamentos,id',
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'nullable|integer',
            'status' => 'required|in:Ativo,Inativo',
            'foto_perfil' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $docente->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
            ]);
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('perfil', 'public');
                $docente->user->foto_perfil = $path;
                $docente->user->save();
            }
            // Update docente
            $docente->update([
                'departamento_id' => $validated['departamento_id'],
                'formacao' => $validated['formacao'],
                'anos_experiencia' => $validated['anos_experiencia'],
                'status' => $validated['status']
            ]);
            DB::commit();
            return redirect()->route('admin.docentes.show', $docente->id)
                ->with('success', 'Dados do docente atualizados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao atualizar dados do docente. Por favor, tente novamente.')
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $docente = Docente::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete associated user
            $docente->user->delete();
            // The docente record will be deleted automatically due to cascade

            DB::commit();
            return redirect()->route('admin.docentes.index')
                ->with('success', 'Docente removido com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao remover docente. Por favor, tente novamente.');
        }
    }
}