<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\PreInscricao;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SecretariaController extends Controller
{
    public function index()
    {
        // Obter a role do usuário logado
        $role = Auth::user()->tipo;

        // Definir o menu com base na role
        $menu = $this->getMenuForRole($role);

        $stats = [
            'estudantes' => Estudante::count(),
            'matriculas_pendentes' => Matricula::where('status', 'Pendente')->count(),
            'matriculas_ativas' => Matricula::where('status', 'Ativo')->count(),
            'pagamentos_pendentes' => Pagamento::where('status', 'pendente')->count(),
        ];

        $matriculasRecentes = Matricula::with(['estudante.user', 'turma', 'anoLectivo'])
            ->latest()
            ->take(5)
            ->get();

        $pagamentosPendentes = Pagamento::with(['estudante.user', 'turma'])
            ->where('status', 'pendente')
            ->orderBy('data_vencimento')
            ->take(5)
            ->get();

        return view('secretaria.dashboard', compact('menu', 'stats', 'matriculasRecentes', 'pagamentosPendentes'));
    }

    public function estudantes(Request $request)
    {
        $turmas = Turma::orderBy('nome')->pluck('nome', 'id');

        $estudantes = Estudante::with(['user', 'turma', 'anoLectivo'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('matricula', 'like', "%{$search}%");
            })
            ->when($request->filled('turma'), fn ($query) => $query->where('turma_id', $request->turma))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('secretaria.estudantes.index', compact('estudantes', 'turmas'));
    }

    public function createEstudante()
    {
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('secretaria.estudantes.create', compact('turmas', 'anosLectivos'));
    }

    public function storeEstudante(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:20',
            'password' => 'required|min:8',
            'matricula' => 'required|unique:estudantes,matricula',
            'turma_id' => 'required|exists:turmas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'contato_emergencia' => 'required|string|max:255',
        ]);

        $estudante = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telefone' => $validated['telefone'],
                'tipo' => 'estudante',
                'genero' => $validated['genero'],
            ]);

            return Estudante::create([
                'user_id' => $user->id,
                'matricula' => $validated['matricula'],
                'turma_id' => $validated['turma_id'],
                'ano_lectivo_id' => $validated['ano_lectivo_id'],
                'data_nascimento' => $validated['data_nascimento'],
                'genero' => $validated['genero'],
                'ano_ingresso' => $validated['ano_ingresso'],
                'turno' => $validated['turno'],
                'contato_emergencia' => $validated['contato_emergencia'],
                'status' => 'Ativo',
            ]);
        });

        return redirect()->route('secretaria.estudantes.show', $estudante)
            ->with('success', 'Estudante cadastrado com sucesso.');
    }

    public function showEstudante(Estudante $estudante)
    {
        $estudante->load(['user', 'turma.classe', 'anoLectivo', 'matriculas.turma', 'pagamentos']);

        return view('secretaria.estudantes.show', compact('estudante'));
    }

    public function editEstudante(Estudante $estudante)
    {
        $estudante->load('user');
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('secretaria.estudantes.edit', compact('estudante', 'turmas', 'anosLectivos'));
    }

    public function updateEstudante(Request $request, Estudante $estudante)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($estudante->user_id)],
            'telefone' => 'required|string|max:20',
            'matricula' => ['required', Rule::unique('estudantes')->ignore($estudante->id)],
            'turma_id' => 'required|exists:turmas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'status' => 'required|in:Ativo,Inativo,Concluído,Desistente',
            'contato_emergencia' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $estudante) {
            $estudante->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
                'genero' => $validated['genero'],
            ]);

            $estudante->update([
                'matricula' => $validated['matricula'],
                'turma_id' => $validated['turma_id'],
                'ano_lectivo_id' => $validated['ano_lectivo_id'],
                'data_nascimento' => $validated['data_nascimento'],
                'genero' => $validated['genero'],
                'ano_ingresso' => $validated['ano_ingresso'],
                'turno' => $validated['turno'],
                'status' => $validated['status'],
                'contato_emergencia' => $validated['contato_emergencia'],
            ]);
        });

        return redirect()->route('secretaria.estudantes.show', $estudante)
            ->with('success', 'Dados do estudante atualizados com sucesso.');
    }

    public function matriculas(Request $request)
    {
        $matriculas = Matricula::with(['estudante.user', 'turma', 'anoLectivo'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->whereHas('estudante.user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhere('referencia', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('secretaria.matriculas.index', compact('matriculas'));
    }

    public function createMatricula()
    {
        $estudantes = Estudante::with('user')->orderByDesc('created_at')->get();
        $turmas = Turma::with(['classe', 'anoLectivo'])->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();

        return view('secretaria.matriculas.create', compact('estudantes', 'turmas', 'anosLectivos'));
    }

    public function storeMatricula(Request $request)
    {
        $validated = $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'turma_id' => 'required|exists:turmas,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'valor' => 'nullable|numeric|min:0',
            'data_matricula' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:Pendente,Ativo,Cancelado',
        ]);

        $matricula = DB::transaction(function () use ($validated) {
            $matricula = Matricula::create([
                'estudante_id' => $validated['estudante_id'],
                'turma_id' => $validated['turma_id'],
                'ano_lectivo_id' => $validated['ano_lectivo_id'],
                'valor' => $validated['valor'] ?? 1500,
                'data_matricula' => $validated['data_matricula'] ?? now()->toDateString(),
                'observacoes' => $validated['observacoes'] ?? null,
                'status' => $validated['status'],
                'referencia' => Matricula::gerarReferencia(),
            ]);

            $estudante = $matricula->estudante;
            if ($estudante) {
                $estudante->update([
                    'turma_id' => $matricula->turma_id,
                    'ano_lectivo_id' => $matricula->ano_lectivo_id,
                    'status' => $matricula->status === 'Ativo' ? 'Ativo' : $estudante->status,
                ]);
            }

            return $matricula;
        });

        return redirect()->route('secretaria.matriculas.show', $matricula)
            ->with('success', 'Matrícula criada com sucesso.');
    }

    public function showMatricula(Matricula $matricula)
    {
        $matricula->load(['estudante.user', 'turma', 'anoLectivo']);

        return view('secretaria.matriculas.show', compact('matricula'));
    }

    public function confirmarMatricula(Matricula $matricula)
    {
        DB::transaction(function () use ($matricula) {
            $matricula->update([
                'status' => 'Ativo',
                'data_confirmacao' => now(),
            ]);

            $estudante = $matricula->estudante;

            if ($estudante) {
                $estudante->update([
                    'turma_id' => $matricula->turma_id,
                    'ano_lectivo_id' => $matricula->ano_lectivo_id,
                    'status' => 'Ativo',
                ]);
            }
        });

        return redirect()->route('secretaria.matriculas.show', $matricula)
            ->with('success', 'Matrícula confirmada com sucesso.');
    }

    public function uploadComprovativo(Request $request, Matricula $matricula)
    {
        $request->validate([
            'comprovativo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($matricula->comprovativo && Storage::disk('public')->exists($matricula->comprovativo)) {
            Storage::disk('public')->delete($matricula->comprovativo);
        }

        $path = $request->file('comprovativo')->store('comprovativos', 'public');
        $matricula->update(['comprovativo' => $path]);

        return redirect()->route('secretaria.matriculas.show', $matricula)
            ->with('success', 'Comprovativo anexado com sucesso.');
    }

    public function pagamentos(Request $request)
    {
        $pagamentos = Pagamento::with(['estudante.user', 'turma'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where('referencia', 'like', "%{$search}%")
                    ->orWhereHas('estudante.user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('tipo'), fn ($query) => $query->where('tipo', $request->tipo))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return view('secretaria.pagamentos.index', compact('pagamentos'));
    }

    public function createPagamento()
    {
        $estudantes = Estudante::with('user')->orderByDesc('created_at')->get();
        $turmas = Turma::with('classe')->orderBy('nome')->get();
        $anosLectivos = AnoLectivo::orderByDesc('ano')->get();
        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        return view('secretaria.pagamentos.create', compact('estudantes', 'turmas', 'anosLectivos', 'anoAtivo'));
    }

    public function storePagamento(Request $request)
    {
        $validated = $request->validate([
            'estudante_id' => 'required|exists:estudantes,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'tipo' => 'required|in:propina,matricula,taxa,inscricao',
            'turma_id' => 'nullable|exists:turmas,id',
            'metodo_pagamento' => 'nullable|in:dinheiro,transferencia,mpesa,emola,mkesh,cheque,outro',
            'descricao' => 'nullable|string|max:1000',
        ]);

        $pagamento = Pagamento::create([
            'estudante_id' => $validated['estudante_id'],
            'ano_lectivo_id' => $validated['ano_lectivo_id'],
            'valor' => $validated['valor'],
            'data_vencimento' => $validated['data_vencimento'],
            'referencia' => Pagamento::gerarReferencia(),
            'status' => 'pendente',
            'tipo' => $validated['tipo'],
            'turma_id' => $validated['turma_id'] ?? null,
            'metodo_pagamento' => $validated['metodo_pagamento'] ?? null,
            'descricao' => $validated['descricao'] ?? null,
        ]);

        return redirect()->route('secretaria.pagamentos.show', $pagamento)
            ->with('success', 'Pagamento criado com sucesso.');
    }

    public function preInscricoes(Request $request)
    {
        $preInscricoes = PreInscricao::with(['classe', 'anoLectivo'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where('nome_completo', 'like', "%{$search}%")
                    ->orWhere('codigo_pre_inscricao', 'like', "%{$search}%")
                    ->orWhere('referencia', 'like', "%{$search}%");
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('secretaria.pre_inscricoes.index', compact('preInscricoes'));
    }

    public function updatePreInscricaoStatus(Request $request, PreInscricao $preInscricao)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pendente,Confirmada,Expirada',
        ]);

        $preInscricao->update(['status' => $validated['status']]);

        return redirect()->route('secretaria.pre-inscricoes.index')
            ->with('success', 'Status da pré-inscrição atualizado com sucesso.');
    }

    public function showPagamento(Pagamento $pagamento)
    {
        $pagamento->load(['estudante.user', 'estudante.turma', 'anoLectivo', 'turma']);

        return view('secretaria.pagamentos.show', compact('pagamento'));
    }

    public function updatePagamentoStatus(Request $request, Pagamento $pagamento)
    {
        $request->validate([
            'status' => 'required|in:pago,pendente,cancelado',
            'observacao' => 'nullable|string|max:1000',
        ]);

        $descricao = $pagamento->descricao;
        if ($request->filled('observacao')) {
            $descricao = trim(($descricao ? $descricao."\n" : '').now()->format('d/m/Y H:i').' - '.$request->observacao);
        }

        $pagamento->update([
            'status' => $request->status,
            'data_pagamento' => $request->status === 'pago' ? now() : null,
            'descricao' => $descricao,
        ]);

        return redirect()->route('secretaria.pagamentos.show', $pagamento)
            ->with('success', 'Status do pagamento atualizado com sucesso.');
    }

    protected function getMenuForRole($role)
    {
        $menu = [
            // Itens de menu comuns (ex.: profile, logout)
            [
                'text' => 'Logout',
                'url' => 'logout',
                'icon' => 'fas fa-fw fa-sign-out-alt',
                'can' => 'auth',
            ],
            // Itens específicos para secretaria
            [
                'text' => 'Dashboard',
                'url' => 'secretaria/dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            [
                'header' => 'Gestão de Estudantes',
            ],
            [
                'text' => 'Estudantes',
                'url' => 'secretaria/estudantes',
                'icon' => 'fas fa-fw fa-users',
            ],
            [
                'text' => 'Novo Estudante',
                'url' => 'secretaria/estudantes/create',
                'icon' => 'fas fa-fw fa-user-plus',
            ],
            [
                'text' => 'Pré-Inscrições',
                'url' => 'secretaria/pre-inscricoes',
                'icon' => 'fas fa-fw fa-clipboard-list',
            ],
            [
                'header' => 'Gestão de Matrículas',
            ],
            [
                'text' => 'Matrículas',
                'url' => 'secretaria/matriculas',
                'icon' => 'fas fa-fw fa-list-ul',
            ],
            [
                'text' => 'Nova Matrícula',
                'url' => 'secretaria/matriculas/create',
                'icon' => 'fas fa-fw fa-plus-circle',
            ],
            [
                'header' => 'Financeiro',
            ],
            [
                'text' => 'Gerenciar Pagamentos',
                'url' => 'secretaria/pagamentos',
                'icon' => 'fas fa-fw fa-money-bill',
            ],
            [
                'text' => 'Novo Pagamento',
                'url' => 'secretaria/pagamentos/create',
                'icon' => 'fas fa-fw fa-file-invoice-dollar',
            ],
        ];

        return $menu;
    }
}
