<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        $estudante = Estudante::where('user_id', Auth::id())
            ->with(['user'])
            ->first();

        return view('estudante.perfil.index', compact('estudante'));
    }

    public function edit()
    {
        $estudante = Estudante::where('user_id', Auth::id())
            ->with(['user'])
            ->first();

        return view('estudante.perfil.edit', compact('estudante'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'telefone' => 'nullable|string|max:255',
            'genero' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefone = $request->telefone;
        $user->genero = $request->genero;

        if ($request->hasFile('foto_perfil')) {
            // Excluir foto antiga se existir
            if ($user->foto_perfil) {
                Storage::delete('public/fotos_perfil/' . $user->foto_perfil);
            }

            $foto = $request->file('foto_perfil');
            $nomeArquivo = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/fotos_perfil', $nomeArquivo);
            $user->foto_perfil = $nomeArquivo;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('estudante.perfil.index')->with('success', 'Perfil atualizado com sucesso!');
    }
}