<?php

namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PerfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // Exibir a página do perfil do istrador
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);

        if($user){
            return view('estudante.profile.index', compact('user'));
        } else {
            return redirect()->route('');
        }
    }
    
    public function updateProfile(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'genero' => ['nullable', 'string', 'in:Masculino,Feminino,Outro'],
            'foto_perfil' => ['nullable', 'image', 'max:1024'], // max 1MB
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        try {
            $user = User::findOrFail(Auth::id());
    
            $data = $request->except(['password', 'password_confirmation']);
    
            // Atualizar senha apenas se fornecida
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
    
            // Upload da nova foto de perfil se fornecida
            if ($request->hasFile('foto_perfil')) {
                // Deletar foto antiga se existir
                if ($user->foto_perfil) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->foto_perfil));
                }
    
                $path = $request->file('foto_perfil')->store('users/profile', 'public');
                $data['foto_perfil'] = 'storage/' . $path;
            }
    
            $user->update($data);
    
            return redirect()->route('admin.perfil.index')
                ->with('success', 'Perfil atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
