<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Departamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class PerfilDocenteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $docente = Docente::with('departamento')->where('user_id', $user->id)->firstOrFail();
        $departamentos = Departamento::all();
        
        // Estatísticas do docente
        $stats = [
            'total_disciplinas' => $docente->disciplinas()->count(),
            'total_estudantes' => $docente->disciplinas()
                ->withCount('inscricaoDisciplinas')
                ->get()
                ->sum('inscricao_disciplinas_count'),
            'anos_experiencia' => $docente->anos_experiencia,
            'disciplinas_ativas' => $docente->disciplinas()
                //->where('status', 'Ativo')
                ->count()
        ];

        return view('docente.perfil.index', compact('user', 'docente', 'departamentos', 'stats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'telefone' => 'required|string|max:20',
            'formacao' => 'required|string|max:255',
            'anos_experiencia' => 'required|integer|min:0',
            'departamento_id' => 'required|exists:departamentos,id',
            'foto_perfil' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Atualizar informações do usuário
        $user = Auth::user();
        $userData = $request->only(['name', 'email', 'telefone']);
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil) {
                Storage::delete('public/fotos_perfil/' . $user->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('public/fotos_perfil');
            $userData['foto_perfil'] = basename($path);
        }
        
        $user->update($userData);

        // Atualizar informações do docente
        $docente = $user->docente;
        $docente->update([
            'formacao' => $request->formacao,
            'anos_experiencia' => $request->anos_experiencia,
            'departamento_id' => $request->departamento_id
        ]);

        return redirect()->route('docente.perfil.index')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}