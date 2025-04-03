<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacoesController extends Controller
{
    public function index()
    {
        $notificacoes = Notificacao::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.notificacoes.index', compact('notificacoes'));
    }

    // app/Http/Controllers/Admin/NotificacoesController.php

    public function create()
    {
        // Buscar tipos de usuário disponíveis
        $tiposUsuario = [
            'estudante' => 'Estudantes',
            'professor' => 'Professores',
            // Adicione outros tipos conforme necessário
        ];
        
        // Buscar todos os usuários
        $usuarios = User::with('estudante') // Adicione outras relações se necessário
            ->select('id', 'name', 'email', 'tipo')
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                $tipo = ucfirst($user->tipo ?? 'Não definido');
                return [
                    'id' => $user->id,
                    'text' => "{$user->name} ({$user->email}) - {$tipo}"
                ];
            });

        return view('admin.notificacoes.create', compact('tiposUsuario', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'mensagem' => 'required|string',
            'tipo' => 'required|in:academico,financeiro,administrativo,geral',
            'modo_envio' => 'required|in:todos,tipos,especificos',
            'tipos_usuario' => 'required_if:modo_envio,tipos|array',
            'tipos_usuario.*' => 'in:estudante,professor', // Adicione outros tipos conforme necessário
            'usuarios' => 'required_if:modo_envio,especificos|array',
            'usuarios.*' => 'exists:users,id',
            'link' => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Determinar os usuários que receberão a notificação
            $usuarios = collect();

            switch ($request->modo_envio) {
                case 'todos':
                    $usuarios = User::all();
                    break;

                case 'tipos':
                    $usuarios = User::whereIn('tipo', $request->tipos_usuario)->get();
                    break;

                case 'especificos':
                    $usuarios = User::whereIn('id', $request->usuarios)->get();
                    break;
            }

            // Criar notificações para cada usuário
            foreach ($usuarios as $usuario) {
                Notificacao::create([
                    'user_id' => $usuario->id,
                    'titulo' => $request->titulo,
                    'mensagem' => $request->mensagem,
                    'tipo' => $request->tipo,
                    'link' => $request->link,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.notificacoes.index')
                ->with('success', 'Notificação(ões) criada(s) com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao criar notificação(ões): ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $notificacao = Notificacao::findOrFail($id);
        $notificacao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída com sucesso!'
        ]);
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:notificacoes,id'
        ]);

        Notificacao::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificações selecionadas foram excluídas com sucesso!'
        ]);
    }
}