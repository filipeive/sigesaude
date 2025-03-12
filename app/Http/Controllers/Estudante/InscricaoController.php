<?php
// app/Http/Controllers/Estudante/InscricaoController.php
namespace App\Http\Controllers\Estudante;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\Disciplina;
use App\Models\Estudante;
use App\Models\Inscricao;
use App\Models\InscricaoDisciplina;
use App\Models\NotaFrequencia;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InscricaoController extends Controller
{   
    private function getEstudanteAutenticado()
    {
        return Estudante::where('user_id', Auth::id())->first();
    }
    private function getAnoLectivoAtual()
    {
        return AnoLectivo::where('status', 'Ativo')->first();
    }
    public function index()
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        $inscricoes = Inscricao::where('estudante_id', $estudante->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $inscricoesPendentes = $inscricoes->where('status', 'Pendente');
        $inscricoesConfirmadas = $inscricoes->where('status', 'Confirmada');

        return view('estudante.inscricoes.index', compact('inscricoesPendentes', 'inscricoesConfirmadas'));
    }

    public function create()
    {
        $estudante = $this->getEstudanteAutenticado();
        if (!$estudante) {
            return redirect()->back()->with('error', 'Estudante não encontrado.');
        }

        $anoAtual = $this->getAnoLectivoAtual();
        if (!$anoAtual) {
            return redirect()->back()->with('error', 'Ano letivo atual não encontrado.');
        }

        // Verificar se já existe uma inscrição pendente para este semestre
        $inscricaoExistente = Inscricao::where('estudante_id', $estudante->id)
            ->where('ano_lectivo_id', $anoAtual->id)
            ->where('status', 'Pendente')
            ->first();

        if ($inscricaoExistente) {
            return redirect()->route('estudante.inscricoes.edit', $inscricaoExistente->id)
                ->with('info', 'Você já possui uma inscrição pendente para este semestre.');
        }

        // Obter o nível atual do estudante
        $nivel = $estudante->nivel; // Sem parênteses, pois é um accessor ou relacionamento
        if (!$nivel) {
            return redirect()->back()->with('error', 'Nível do estudante não encontrado.');
        }

        // Disciplinas normais para o nível atual
        $disciplinasNormais = Disciplina::where('nivel_id', $nivel->id)
            ->where('curso_id', $estudante->curso_id)
            ->get();

        // Disciplinas em atraso (disciplinas de níveis anteriores reprovadas)
        $disciplinasEmAtraso = $this->getDisciplinasEmAtraso($estudante);

        return view('estudante.inscricoes.create', compact(
            'estudante',
            'anoAtual',
            'disciplinasNormais',
            'disciplinasEmAtraso'
        ));
    }

    public function store(Request $request)
    {
        $estudante = Estudante::where('user_id', Auth::id())->first();
        $anoAtual = AnoLectivo::where('status', 'Ativo')->first();
        
        // Criar a inscrição
        $inscricao = new Inscricao();
        $inscricao->estudante_id = $estudante->id;
        $inscricao->ano_lectivo_id = $anoAtual->id;
        $inscricao->semestre = $request->semestre;
        $inscricao->status = 'Pendente';
        $inscricao->referencia = 'INS-' . Str::random(8);
        $inscricao->data_inscricao = now();
        $inscricao->save();
        
        // Salvar as disciplinas selecionadas
        if ($request->has('disciplinas_normais')) {
            foreach ($request->disciplinas_normais as $disciplinaId) {
                $inscricaoDisciplina = new InscricaoDisciplina();
                $inscricaoDisciplina->inscricao_id = $inscricao->id;
                $inscricaoDisciplina->disciplina_id = $disciplinaId;
                $inscricaoDisciplina->tipo = 'Normal';
                $inscricaoDisciplina->save();
            }
        }
        
        if ($request->has('disciplinas_atraso')) {
            foreach ($request->disciplinas_atraso as $disciplinaId) {
                $inscricaoDisciplina = new InscricaoDisciplina();
                $inscricaoDisciplina->inscricao_id = $inscricao->id;
                $inscricaoDisciplina->disciplina_id = $disciplinaId;
                $inscricaoDisciplina->tipo = 'Em Atraso';
                $inscricaoDisciplina->save();
            }
        }
        
        // Calcular valor da inscrição baseado nas disciplinas escolhidas
        // (Implemente sua lógica de cálculo de valores aqui)
        
        return redirect()->route('estudante.inscricoes.index')
            ->with('success', 'Inscrição realizada com sucesso! Aguarde a confirmação.');
    }
    
    private function getDisciplinasEmAtraso($estudante)
    {
        // Recuperar disciplinas de níveis anteriores que o estudante reprovou
        $nivelAtual = $estudante->nivel->id;
        
        // Busca disciplinas de níveis anteriores onde o estudante não teve aprovação
        $disciplinasReprovadas = NotaFrequencia::where('estudante_id', $estudante->id)
            ->whereHas('disciplina', function($query) use ($nivelAtual) {
                $query->where('nivel_id', '<', $nivelAtual);
            })
            ->where(function($query) {
                $query->where('status', 'Excluído')
                    ->orWhereNull('status');
            })
            ->with('disciplina')
            ->get()
            ->pluck('disciplina')
            ->unique('id');
            
        return $disciplinasReprovadas;
    }

    public function edit($id)
    {
        $inscricao = Inscricao::findOrFail($id);
        $estudante = Estudante::where('user_id', Auth::id())->first();
        $anoAtual = AnoLectivo::where('status', 'Ativo')->first();
        $nivel = $estudante->nivel();
        
        // Disciplinas normais para o nível atual
        $disciplinasNormais = Disciplina::where('nivel_id', $nivel->id)
            ->where('curso_id', $estudante->curso_id)
            ->get();
            
        // Disciplinas em atraso (disciplinas de níveis anteriores reprovadas)
        $disciplinasEmAtraso = $this->getDisciplinasEmAtraso($estudante);
        
        // Disciplinas já selecionadas na inscrição
        $disciplinasSelecionadas = $inscricao->disciplinas->pluck('id')->toArray();
        
        return view('estudante.inscricoes.edit', compact(
            'inscricao',
            'estudante', 
            'anoAtual', 
            'disciplinasNormais', 
            'disciplinasEmAtraso',
            'disciplinasSelecionadas'
        ));
    }

    public function update(Request $request, $id)
    {
        $inscricao = Inscricao::findOrFail($id);
        
        // Atualizar o semestre, se necessário
        $inscricao->semestre = $request->semestre;
        $inscricao->save();
        
        // Remover disciplinas anteriores
        InscricaoDisciplina::where('inscricao_id', $inscricao->id)->delete();
        
        // Adicionar novas disciplinas selecionadas
        if ($request->has('disciplinas_normais')) {
            foreach ($request->disciplinas_normais as $disciplinaId) {
                $inscricaoDisciplina = new InscricaoDisciplina();
                $inscricaoDisciplina->inscricao_id = $inscricao->id;
                $inscricaoDisciplina->disciplina_id = $disciplinaId;
                $inscricaoDisciplina->tipo = 'Normal';
                $inscricaoDisciplina->save();
            }
        }
        
        if ($request->has('disciplinas_atraso')) {
            foreach ($request->disciplinas_atraso as $disciplinaId) {
                $inscricaoDisciplina = new InscricaoDisciplina();
                $inscricaoDisciplina->inscricao_id = $inscricao->id;
                $inscricaoDisciplina->disciplina_id = $disciplinaId;
                $inscricaoDisciplina->tipo = 'Em Atraso';
                $inscricaoDisciplina->save();
            }
        }
        
        return redirect()->route('estudante.inscricoes.index')
            ->with('success', 'Inscrição atualizada com sucesso!');
    }
    // app/Http/Controllers/Estudante/InscricaoController.php

    // app/Http/Controllers/Estudante/InscricaoController.php

    public function show($id)
    {
        // Recuperar a inscrição pelo ID
        $inscricao = Inscricao::findOrFail($id);

        // Verificar se a inscrição pertence ao estudante autenticado
        $estudante = Estudante::where('user_id', Auth::id())->first();
        if ($inscricao->estudante_id !== $estudante->id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Carregar as disciplinas associadas à inscrição
        $disciplinas = $inscricao->disciplinas;

        // Retornar a view com os dados da inscrição
        return view('estudante.inscricoes.show', compact('inscricao', 'disciplinas'));
    }
    public function destroy($id)
    {
        $inscricao = Inscricao::findOrFail($id);
        
        // Verificar se a inscrição pode ser excluída (apenas se estiver pendente)
        if ($inscricao->status !== 'Pendente') {
            return redirect()->route('estudante.inscricoes.index')
                ->with('error', 'Apenas inscrições pendentes podem ser excluídas.');
        }
        
        // Excluir as disciplinas associadas
        InscricaoDisciplina::where('inscricao_id', $inscricao->id)->delete();
        
        // Excluir a inscrição
        $inscricao->delete();
        
        return redirect()->route('estudante.inscricoes.index')
            ->with('success', 'Inscrição excluída com sucesso!');
    }
    public function cancelar($id)
    {
        $inscricao = Inscricao::findOrFail($id);

        // Verificar se a inscrição pertence ao estudante autenticado
        $estudante = Estudante::where('user_id', Auth::id())->first();
        if ($inscricao->estudante_id !== $estudante->id) {
            abort(403, 'Acesso não autorizado.');
        }

        // Atualizar o status para "Cancelada"
        $inscricao->status = 'Cancelada';
        $inscricao->save();

        return redirect()->route('estudante.inscricoes.index')
            ->with('success', 'Inscrição cancelada com sucesso!');
    }
}