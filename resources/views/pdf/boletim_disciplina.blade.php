<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boletim — {{ $disc->nome }} — {{ $turma->nome }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #0056b3; padding-bottom: 8px; margin-bottom: 15px; }
        .header .logo { font-size: 20px; font-weight: bold; color: #0056b3; }
        .header .subtitle { font-size: 13px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #0056b3; color: white; padding: 7px 8px; text-align: center; font-size: 11px; }
        td { padding: 6px 8px; border: 1px solid #ccc; text-align: center; }
        tr:nth-child(even) td { background: #f4f7fb; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #888; }
        .badge-aprovado  { background: #28a745; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; }
        .badge-reprovado { background: #dc3545; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; }
        .info { display: flex; justify-content: space-between; margin-bottom: 12px; }
        .info span { font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ESCOLA DOS VISIONÁRIOS</div>
        <div class="subtitle">Boletim de Notas — {{ $disc->nome }}</div>
        <div class="subtitle">{{ $turma->classe->nome ?? '' }} {{ $turma->nome }} — Ano Lectivo {{ $ano->ano ?? '' }}</div>
    </div>

    <div class="info">
        <span><strong>Docente:</strong> {{ $disc->docente->user->name ?? 'Não alocado' }}</span>
        <span><strong>Data de Emissão:</strong> {{ date('d/m/Y') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th>Nome do Aluno</th>
                <th>Matrícula</th>
                <th>Nota Freq.</th>
                <th>Exame</th>
                <th>Média Final</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
        @php $aprov = 0; $reprov = 0; @endphp
        @forelse($alunos as $i => $aluno)
            @php
                $nf   = $aluno->notasFrequencia->first();
                $ne   = $aluno->notasExame->first();
                $mf   = $aluno->mediaFinais->first();
                $mF   = $mf?->media_final ?? round((($nf?->nota ?? 0) + ($ne?->nota ?? 0)) / 2, 2);
                $res  = $mf?->resultado ?? ($mF >= 10 ? 'Aprovado' : 'Reprovado');
                if($res === 'Aprovado') $aprov++; else $reprov++;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left;">{{ $aluno->user->name ?? 'N/A' }}</td>
                <td><code>{{ $aluno->matricula }}</code></td>
                <td>{{ $nf?->nota ?? '—' }}</td>
                <td>{{ $ne?->nota ?? '—' }}</td>
                <td><strong>{{ number_format($mF, 1) }}</strong></td>
                <td>
                    <span class="{{ $res === 'Aprovado' ? 'badge-aprovado' : 'badge-reprovado' }}">
                        {{ $res }}
                    </span>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;">Nenhum aluno encontrado.</td></tr>
        @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="font-weight:bold;text-align:right;">Total de Alunos:</td>
                <td><strong>{{ $alunos->count() }}</strong></td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight:bold;text-align:right;">Aprovados:</td>
                <td><strong style="color:#28a745;">{{ $aprov }}</strong></td>
            </tr>
            <tr>
                <td colspan="6" style="font-weight:bold;text-align:right;">Reprovados:</td>
                <td><strong style="color:#dc3545;">{{ $reprov }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:30px;display:flex;justify-content:center;gap:60px;">
        <div style="width:200px;border-top:1px solid #333;text-align:center;padding-top:5px;">Assinatura do Docente</div>
        <div style="width:200px;border-top:1px solid #333;text-align:center;padding-top:5px;">Assinatura da Direcção</div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Escola dos Visionários — Sistema de Gestão Escolar — Boletim Gerado em {{ date('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
