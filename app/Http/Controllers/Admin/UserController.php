<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        //pegue o checkRole
    }
    /**
     * Display a listing of the resource.
     */
    /*public function index()
    {
        // Mostrar a lista com todos os usuários
        $users = User::paginate(8);
        return view('admin.users.index', compact('users'));
    }*/
    public function index(Request $request)
    {
        // Obter o valor da pesquisa
        $search = $request->get('search');
    
        // Filtrar os usuários pelo nome ou email
        $users = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->paginate(8);
        $loggedId = intval(Auth::id());
    
        // Retornar JSON se for uma requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                
                'users' => $users,
                'pagination' => (string) $users->links('pagination::bootstrap-5'),
            ]);
        }
    
        // Retornar a view padrão para requisições normais
        return view('admin.users.index', compact('users', 'search', 'loggedId'));
    }    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mostrar o formulário para criar um novo usuário
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'genero' => ['nullable', 'string', 'in:Masculino,Feminino,Outro'],
            'tipo' => ['required', 'string', 'in:admin,docente,estudante,financeiro,secretaria'],
            'foto_perfil' => ['nullable', 'image', 'max:1024'], // max 1MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Criar novo usuário
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->telefone = $request->telefone;
            $user->genero = $request->genero;
            $user->tipo = $request->tipo;

            // Upload da foto de perfil se fornecida
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('users/profile', 'public');
                $user->foto_perfil = 'storage/' . $path;
            }

            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mostrar os detalhes do usuário
        $user = \App\Models\User::find($id);
        return view('admin.users.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Mostrar o formulário para editar o usuário
        $user = User::find($id);

        // Verificar se o id existe
        if($user){
        return view('admin.users.edit', compact('user'));
        } else {
            return redirect()->route('users.index')->with('deleted', 'Usuário não encontrado.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'telefone' => ['nullable', 'string', 'max:255'],
            'genero' => ['nullable', 'string', 'in:Masculino,Feminino,Outro'],
            'tipo' => ['required', 'string', 'in:admin,docente,estudante,financeiro,secretaria'],
            'foto_perfil' => ['nullable', 'image', 'max:1024'], // max 1MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->telefone = $request->telefone;
            $user->genero = $request->genero;
            $user->tipo = $request->tipo;

            // Atualizar senha apenas se fornecida
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Upload da nova foto de perfil se fornecida
            if ($request->hasFile('foto_perfil')) {
                // Deletar foto antiga se existir
                if ($user->foto_perfil) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->foto_perfil));
                }
                
                $path = $request->file('foto_perfil')->store('users/profile', 'public');
                $user->foto_perfil = 'storage/' . $path;
            }

            $user->save();

            return redirect()->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loggedId = intval(Auth::id()); //

        if($loggedId == $id){
            return redirect()->route('users.index')->with('error', 'Você não pode excluir seu próprio cadastro.');   
        } else {
            $user = User::find($id);
            $user->delete(); 
        }
        // Redirecionar para a lista de usuários
        return redirect()->route('users.index')->with('deleted', 'Usuário excluído com sucesso!');
    }
}
