<?php

namespace App\Http\Controllers;

use App\Models\PreInscricao;
use App\Models\Classe;
use App\Models\AnoLectivo;
use App\Models\Estudante;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PreInscricaoController extends Controller
{
    /**
     * Store a newly created pre-registration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'required|string|max:20',
            'classe_id' => 'required|exists:classes,id',
        ]);

        $anoAtivo = AnoLectivo::where('status', 'Ativo')->first();

        if (!$anoAtivo) {
            return back()->with('error', 'Não há um ano lectivo ativo para inscrições.');
        }

        $codigo = 'PRE-' . strtoupper(Str::random(6));
        $dataLimite = Carbon::now()->addDays(5); // 5 dias para confirmar

        $preInscricao = PreInscricao::create([
            'nome_completo' => $request->nome_completo,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'classe_id' => $request->classe_id,
            'ano_lectivo_id' => $anoAtivo->id,
            'codigo_pre_inscricao' => $codigo,
            'referencia' => PreInscricao::gerarReferencia(),
            'valor' => 1500, // Valor padrão de pré-inscrição
            'data_limite' => $dataLimite,
            'status' => 'Pendente',
        ]);

        return redirect()->route('pre-inscricao.sucesso', $preInscricao->codigo_pre_inscricao)
            ->with('success', 'Pré-inscrição realizada com sucesso! Descarregue o seu comprovativo.');
    }

    public function sucesso($codigo)
    {
        $preInscricao = PreInscricao::where('codigo_pre_inscricao', $codigo)->firstOrFail();
        return view('pre_inscricao_sucesso', compact('preInscricao'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $preInscricoes = PreInscricao::with(['classe', 'anoLectivo'])
            ->when($search, function ($query, $search) {
                return $query->where('nome_completo', 'like', "%{$search}%")
                             ->orWhere('codigo_pre_inscricao', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pre_inscricoes.index', compact('preInscricoes'));
    }

    public function downloadPdf($codigo)
    {
        $preInscricao = PreInscricao::with(['classe', 'anoLectivo'])->where('codigo_pre_inscricao', $codigo)->firstOrFail();
        
        $pdf = Pdf::loadView('pdf.pre_inscricao', compact('preInscricao'));
        
        return $pdf->download('Comprovativo_Pre_Inscricao_' . $codigo . '.pdf');
    }
}
