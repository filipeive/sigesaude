<?php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudante;
use App\Models\Matricula;
use App\Models\NotaFrequencia;
use App\Models\Pagamento;
use App\Models\User;
use App\Models\Curso;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;

class EstudanteController extends Controller
{
    /**
     * Check if student exists and return the student or redirect
     */
    private function getEstudanteOrRedirect()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        
        if (!$estudante) {
            // Redirect to a route where the user can create their student profile
            return redirect()->route('estudante.create.profile')
                ->with('error', 'Perfil de estudante não encontrado. Por favor, complete seu cadastro.');
        }
        
        return $estudante;
    }

    /**
     * Dashboard principal do estudante
     */
    public function index()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        // If the function returned a redirect response, return it
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        $totalDisciplinas = Matricula::where('estudante_id', $estudante->id)->count();
        
        $ultimosPagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->take(5)
            ->get();
            
        // Obter a última notificação (você precisará criar esta tabela se não existir)
        $ultimasNotificacoes = []; // Substitua com a consulta apropriada quando criar a tabela de notificações
        
        return view('estudante.dashboard', compact('estudante', 'totalDisciplinas', 'ultimosPagamentos', 'ultimasNotificacoes'));
    }

    /**
     * Exibe as matrículas do estudante
     */
    public function matriculas()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        $matriculas = Matricula::where('estudante_id', $estudante->id)
            ->with(['disciplina'])
            ->get();
            
        return view('estudante.matriculas', compact('estudante', 'matriculas'));
    }

    /**
     * Exibe os pagamentos do estudante
     */
    public function pagamentos()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        $pagamentos = Pagamento::where('estudante_id', $estudante->id)
            ->orderBy('data_pagamento', 'desc')
            ->get();
            
        $totalPago = $pagamentos->sum('valor');
        
        return view('estudante.pagamentos', compact('estudante', 'pagamentos', 'totalPago'));
    }

    /**
     * Exibe os relatórios do estudante
     */
    public function relatorios()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Aqui você pode reunir dados para relatórios acadêmicos
        $dadosAcademicos = [
            'progresso' => 0, // Calcular baseado nas disciplinas concluídas vs. total
            'mediasGerais' => [], // Adicionar médias gerais por semestre ou ano letivo
        ];
        
        return view('estudante.relatorios', compact('estudante', 'dadosAcademicos'));
    }

    /**
     * Exibe as notificações do estudante
     */
    public function notificacoes()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        // Você precisará criar uma tabela de notificações
        $notificacoes = []; // Substitua com a consulta apropriada quando criar a tabela
        
        return view('estudante.notificacoes', compact('estudante', 'notificacoes'));
    }

    /**
     * Exibe as configurações do estudante
     */
    public function configuracoes()
    {
        $estudante = $this->getEstudanteOrRedirect();
        
        if (!($estudante instanceof Estudante)) {
            return $estudante;
        }
        
        return view('estudante.configuracoes', compact('estudante'));
    }
    
    /**
     * Mostra o formulário para criar um perfil de estudante
     * (Você precisará criar esta view e rota)
     */
    public function createProfile()
    {
        // Verifique se o usuário já tem um perfil
        $existingEstudante = Estudante::where('user_id', Auth::id())->first();
        
        if ($existingEstudante) {
            return redirect()->route('estudante.dashboard')
                ->with('info', 'Você já possui um perfil de estudante.');
        }
        
        $cursos = Curso::all();
        $anosLectivos = AnoLectivo::all();
        
        // Generate matricula for the current year
        $matricula = $this->generateMatricula(date('Y'));
        
        return view('estudante.create-profile', compact('cursos', 'anosLectivos', 'matricula'));
    }
    
    /**
     * Salva o novo perfil de estudante
     */
    public function generateMatricula($anoIngresso)
    {
        // Find the last matricula for the given year
        $lastMatricula = Estudante::where('ano_ingresso', $anoIngresso)
            ->orderBy('matricula', 'desc')
            ->first();

        if ($lastMatricula) {
            // Extract the numeric part and increment
            $lastNumber = intval(substr($lastMatricula->matricula, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Start with 001 if no previous matricula exists for this year
            $newNumber = '001';
        }

        // Format: 000.01.YYYY
        return sprintf('%03d.01.%d', $newNumber, $anoIngresso);
    }

    public function storeProfile(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'ano_lectivo_id' => 'required|exists:anos_lectivos,id',
            'data_nascimento' => 'required|date',
            'genero' => 'required|in:Masculino,Feminino,Outro',
            'ano_ingresso' => 'required|digits:4',
            'turno' => 'required|in:Diurno,Noturno',
            'contato_emergencia' => 'nullable|string|max:255',
        ]);

        // Generate unique matricula
        $matricula = $this->generateMatricula($validated['ano_ingresso']);

        // Check if the generated matricula is unique
        while (Estudante::where('matricula', $matricula)->exists()) {
            $matricula = $this->generateMatricula($validated['ano_ingresso']);
        }

        $estudante = new Estudante();
        $estudante->user_id = Auth::id();
        $estudante->matricula = $matricula;
        $estudante->curso_id = $validated['curso_id'];
        $estudante->ano_lectivo_id = $validated['ano_lectivo_id'];
        $estudante->data_nascimento = $validated['data_nascimento'];
        $estudante->genero = $validated['genero'];
        $estudante->ano_ingresso = $validated['ano_ingresso'];
        $estudante->turno = $validated['turno'];
        $estudante->status = 'Ativo';
        $estudante->contato_emergencia = $validated['contato_emergencia'];
        $estudante->save();

        return redirect()->route('estudante.dashboard')
            ->with('success', 'Perfil de estudante criado com sucesso!');
    }
}