<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Docente;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $turmaFiltro = $request->input('turma_id');

        $docentes = Docente::with(['user', 'departamento', 'turma', 'turma.classe'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('departamento', fn ($q) => $q->where('nome', 'like', "%{$search}%"));
            })
            ->when($turmaFiltro, function ($query, $turmaId) {
                // Docentes cuja turma titular é essa, OU que lecionam disciplinas nessa turma
                $query->where(function ($q) use ($turmaId) {
                    $q->where('turma_id', $turmaId)
                        ->orWhereHas('disciplinas', fn ($d) => $d->where('turma_id', $turmaId));
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $turmas = Turma::with('classe')
            ->orderBy('ano_serie')
            ->orderBy('nome')
            ->get()
            ->mapWithKeys(function ($t) {
                $label = ($t->classe?->nome ?? '').' '.$t->nome;

                return [$t->id => $label];
            })
            ->all();

        return view('admin.docentes.index', compact('docentes', 'turmas'));
    }

    public function create()
    {
        $cursos = Curso::pluck('nome', 'id');
        $departamentos = Departamento::pluck('nome', 'id');
        $turmas = Turma::with('classe')->orderBy('ano_serie')->orderBy('nome')->get();

        return view('admin.docentes.create', compact('departamentos', 'cursos', 'turmas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'departamento_id' => 'required|exists:departamentos,id',
            'turma_id' => 'nullable|exists:turmas,id',
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'nullable|integer',
            'status' => 'required|in:Ativo,Inativo',
            'foto_perfil' => 'nullable|image|max:2048',
            'cursos' => 'array',
            'cursos.*' => 'exists:cursos,id',
        ]);

        DB::beginTransaction();
        try {
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

            $docente = Docente::create([
                'user_id' => $user->id,
                'departamento_id' => $validated['departamento_id'],
                'turma_id' => $validated['turma_id'] ?? null,
                'formacao' => $validated['formacao'],
                'anos_experiencia' => $validated['anos_experiencia'],
                'status' => $validated['status'],
            ]);

            if (! empty($validated['cursos'])) {
                $docente->cursos()->attach($validated['cursos']);
            }

            DB::commit();

            return redirect()->route('admin.docentes.show', $docente->id)
                ->with('success', 'Docente cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao cadastrar docente: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $docente = Docente::with([
            'user',
            'departamento',
            'turma',
            'turma.classe',
            'disciplinas',
            'disciplinas.turma',
            'disciplinas.turma.classe',
            'alocacoes.turma',
            'alocacoes.disciplina',
        ])->findOrFail($id);

        return view('admin.docentes.show', compact('docente'));
    }

    public function edit(string $id)
    {
        $docente = Docente::with('user')->findOrFail($id);
        $cursos = Curso::pluck('nome', 'id');
        $departamentos = Departamento::pluck('nome', 'id');
        $turmas = Turma::with('classe')->orderBy('ano_serie')->orderBy('nome')->get();

        return view('admin.docentes.edit', compact('docente', 'departamentos', 'cursos', 'turmas'));
    }

    public function update(Request $request, string $id)
    {
        $docente = Docente::with('user')->findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($docente->user_id)],
            'telefone' => 'required|string|max:20',
            'departamento_id' => 'required|exists:departamentos,id',
            'turma_id' => 'nullable|exists:turmas,id',
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'nullable|integer',
            'status' => 'required|in:Ativo,Inativo',
            'foto_perfil' => 'nullable|image|max:2048',
            'cursos' => 'array',
            'cursos.*' => 'exists:cursos,id',
        ]);

        DB::beginTransaction();
        try {
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

            $docente->update([
                'departamento_id' => $validated['departamento_id'],
                'turma_id' => $validated['turma_id'] ?? null,
                'formacao' => $validated['formacao'],
                'anos_experiencia' => $validated['anos_experiencia'],
                'status' => $validated['status'],
            ]);

            if (array_key_exists('cursos', $validated)) {
                $docente->cursos()->sync($validated['cursos'] ?? []);
            }

            DB::commit();

            return redirect()->route('admin.docentes.show', $docente->id)
                ->with('success', 'Dados do docente atualizados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao atualizar docente: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $docente = Docente::findOrFail($id);

        try {
            DB::beginTransaction();
            $docente->user->delete();
            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('success', 'Docente removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erro ao remover docente.');
        }
    }
}
