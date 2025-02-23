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
    
    public function updateProfile(Request $request){
        // Salvar as alterações na página do perfil do istrador
        $loggedId = intval(Auth::id());
        $user = User::find($loggedId);

        if($user){
            $data = $request->all();

            if($request->filled('password')){
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.perfil.update')->with('success', 'Perfil salvo com sucesso!');
        } else {
            return redirect('');
        }
    }
}
