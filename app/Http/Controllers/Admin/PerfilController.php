<?php

namespace App\Http\Controllers\Admin;

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
            return view('admin.profile.index', compact('user'));
        } else {
            return redirect()->route('');
        }
    }
    
    public function updateProfile(Request $request) {
        // Salvar as alterações na página do perfil do administrador
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);
        
        if($user) {
            $data = $request->only(['name', 'email', 'telefone', 'genero']);
            
            // Upload da foto de perfil se fornecida
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('users/profile', 'public');
                $user->foto_perfil = 'storage/' . $path;
            }
            
            // Verificar se a senha foi fornecida
            if($request->filled('password')) {
                // Verificar se a senha atual está correta
                if (Hash::check($request->current_password, $user->password)) {
                    $user->password = Hash::make($request->password);
                } else {
                    return redirect()->route('admin.perfil.update')
                        ->with('error', 'A senha atual está incorreta.');
                }
            }
            
            // Atualizar os outros campos
            $user->fill($data);
            $user->save();
            
            return redirect()->route('admin.perfil.update')
                ->with('success', 'Perfil salvo com sucesso!');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
}
