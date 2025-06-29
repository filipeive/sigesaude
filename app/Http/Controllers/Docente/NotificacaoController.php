<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Estatísticas
        $totalNotificacoes = Notificacao::where('user_id', $user->id)->count();
        $naoLidas = Notificacao::where('user_id', $user->id)->where('lida', false)->count();
        $notificacoesEnviadas = Notificacao::where('origem_id', $user->docente->id)
            ->where('origem_type', 'App\Models\Docente')
            ->count();
        $notificacoesHoje = Notificacao::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        // Notificações recebidas
        $notificacoes = Notificacao::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Notificações enviadas
        $notificacoesEnviadasLista = Notificacao::where('origem_id', $user->docente->id)
            ->where('origem_type', 'App\Models\Docente')
            ->withCount(['destinatarios', 'destinatariosLidos as lidas_count'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Contagem por tipo
        $porTipo = Notificacao::where('user_id', $user->id)
            ->selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->get();

        return view('docente.notificacoes.index', compact(
            'notificacoes',
            'naoLidas',
            'porTipo',
            'totalNotificacoes',
            'notificacoesEnviadas',
            'notificacoesHoje',
            'notificacoesEnviadasLista'
        ));
    }
        
    public function create()
    {
        $disciplinas = auth()->user()->docente->disciplinas;
        return view('docente.notificacoes.create', compact('disciplinas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'destinatarios' => 'required|array',
            'destinatarios.*' => 'required|exists:users,id',
            'tipo' => 'required|in:academico,avaliacao,exame,presenca,geral',
            'titulo' => 'required|string|max:255',
            'mensagem' => 'required|string',
            'link' => 'nullable|url',
            'agendada_para' => 'nullable|date|after:now'
        ]);
    
        $notificacoes = [];
        foreach ($request->destinatarios as $userId) {
            $notificacoes[] = Notificacao::create([
                'user_id' => $userId,
                'titulo' => $request->titulo,
                'mensagem' => $request->mensagem,
                'tipo' => $request->tipo,
                'link' => $request->link,
                'agendada_para' => $request->agendada_para,
                'origem_id' => auth()->user()->docente->id,
                'origem_type' => 'App\Models\Docente'
            ]);
        }
    
        return redirect()
            ->route('docente.notificacoes.index')
            ->with('success', 'Notificação enviada com sucesso para ' . count($notificacoes) . ' destinatário(s)');
    }
    public function marcarComoLida($id)
    {
        $notificacao = Notificacao::where('user_id', Auth::id())
            ->findOrFail($id);

        $notificacao->marcarComoLida();

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida'
        ]);
    }

    public function marcarTodasComoLidas()
    {
        Notificacao::where('user_id', Auth::id())
            ->where('lida', false)
            ->update(['lida' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas'
        ]);
    }

    public function excluir($id)
    {
        $notificacao = Notificacao::where('user_id', Auth::id())
            ->findOrFail($id);

        $notificacao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída com sucesso'
        ]);
    }

    public function contadorNaoLidas()
    {
        $count = Notificacao::where('user_id', Auth::id())
            ->where('lida', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function filtrar(Request $request)
    {
        $query = Notificacao::where('user_id', Auth::id());

        if ($request->tipo) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->lida === '0' || $request->lida === '1') {
            $query->where('lida', $request->lida);
        }

        if ($request->data_inicio) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->data_fim) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $notificacoes = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->ajax()) {
            return view('docente.notificacoes.partials.lista', compact('notificacoes'));
        }

        return view('docente.notificacoes.index', compact('notificacoes'));
    }

    
}