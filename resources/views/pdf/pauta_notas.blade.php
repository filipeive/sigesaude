<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pauta Anual de Notas</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 11px;
            margin: 0;
            color: #666;
            font-weight: normal;
        }
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            font-size: 9px;
        }
        .info-label {
            font-weight: bold;
            color: #111;
        }
        .pauta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .pauta-table th, .pauta-table td {
            border: 1px solid #666;
            padding: 4px 3px;
            text-align: center;
        }
        .pauta-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
        }
        .align-left {
            text-align: left !important;
            padding-left: 5px !important;
        }
        .text-danger {
            color: #c00;
            font-weight: bold;
        }
        .text-success {
            color: #080;
            font-weight: bold;
        }
        .bg-trim1 {
            background-color: #e6f2ff;
        }
        .bg-trim2 {
            background-color: #e6fff2;
        }
        .bg-trim3 {
            background-color: #fffde6;
        }
        .bg-final {
            background-color: #fce6ff;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
        }
        .signatures td {
            text-align: center;
            width: 33%;
            font-size: 9px;
        }
        .signature-line {
            width: 80%;
            border-bottom: 1px solid #333;
            margin: 0 auto 5px auto;
            height: 30px;
        }
        .legend {
            margin-top: 15px;
            font-size: 7.5px;
            color: #555;
            border: 1px solid #ccc;
            padding: 5px;
            background-color: #fafafa;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>República de Moçambique</h1>
        <h2>Sistema Nacional de Educação (SNE)</h2>
        <h2 style="font-weight: bold; margin-top: 5px; font-size: 13px;">PAUTA DE AVALIAÇÃO ANUAL</h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="25%"><span class="info-label">Ano Lectivo:</span> {{ $ano->ano }}</td>
            <td width="25%"><span class="info-label">Classe:</span> {{ $turma->classe?->nome }}</td>
            <td width="25%"><span class="info-label">Turma:</span> {{ $turma->nome }}</td>
            <td width="25%"><span class="info-label">Disciplina:</span> {{ $disc->nome }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="info-label">Docente:</span> {{ $disc->docente?->user?->name ?? 'Não alocado' }}</td>
            <td colspan="2" style="text-align: right;"><span class="info-label">Data de Emissão:</span> {{ date('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="pauta-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px; vertical-align: middle;">Nº</th>
                <th rowspan="2" style="vertical-align: middle; text-align: left; width: 140px;">Nome Completo do Aluno</th>
                <th colspan="6" class="bg-trim1">1º Trimestre</th>
                <th colspan="6" class="bg-trim2">2º Trimestre</th>
                <th colspan="6" class="bg-trim3">3º Trimestre</th>
                <th colspan="4" class="bg-final">Resultado Anual</th>
            </tr>
            <tr>
                <!-- T1 -->
                <th class="bg-trim1">ACS1</th>
                <th class="bg-trim1">ACS2</th>
                <th class="bg-trim1">ACS3</th>
                <th class="bg-trim1">ACP</th>
                <th class="bg-trim1">ACF</th>
                <th class="bg-trim1" style="font-weight: bold;">MT1</th>
                <!-- T2 -->
                <th class="bg-trim2">ACS1</th>
                <th class="bg-trim2">ACS2</th>
                <th class="bg-trim2">ACS3</th>
                <th class="bg-trim2">ACP</th>
                <th class="bg-trim2">ACF</th>
                <th class="bg-trim2" style="font-weight: bold;">MT2</th>
                <!-- T3 -->
                <th class="bg-trim3">ACS1</th>
                <th class="bg-trim3">ACS2</th>
                <th class="bg-trim3">ACS3</th>
                <th class="bg-trim3">ACP</th>
                <th class="bg-trim3">ACF</th>
                <th class="bg-trim3" style="font-weight: bold;">MT3</th>
                <!-- Final -->
                <th class="bg-final">MF</th>
                <th class="bg-final">Exame</th>
                <th class="bg-final">CF</th>
                <th class="bg-final">Classif.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alunos as $idx => $aluno)
                @php
                    $t1 = $aluno->t1;
                    $t2 = $aluno->t2;
                    $t3 = $aluno->t3;
                    $res = $aluno->resultado;
                    $exame = $aluno->exame;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="align-left" style="font-weight: bold;">{{ $aluno->user->name ?? 'N/A' }}</td>
                    <!-- T1 -->
                    <td>{{ $t1?->acs1 !== null ? number_format($t1->acs1, 1) : '—' }}</td>
                    <td>{{ $t1?->acs2 !== null ? number_format($t1->acs2, 1) : '—' }}</td>
                    <td>{{ $t1?->acs3 !== null ? number_format($t1->acs3, 1) : '—' }}</td>
                    <td>{{ $t1?->acp !== null ? number_format($t1->acp, 1) : '—' }}</td>
                    <td>{{ $t1?->acf !== null ? number_format($t1->acf, 1) : '—' }}</td>
                    <td style="font-weight: bold;" class="{{ $t1?->media_trimestral !== null && $t1->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                        {{ $t1?->media_trimestral !== null ? number_format($t1->media_trimestral, 1) : '—' }}
                    </td>
                    <!-- T2 -->
                    <td>{{ $t2?->acs1 !== null ? number_format($t2->acs1, 1) : '—' }}</td>
                    <td>{{ $t2?->acs2 !== null ? number_format($t2->acs2, 1) : '—' }}</td>
                    <td>{{ $t2?->acs3 !== null ? number_format($t2->acs3, 1) : '—' }}</td>
                    <td>{{ $t2?->acp !== null ? number_format($t2->acp, 1) : '—' }}</td>
                    <td>{{ $t2?->acf !== null ? number_format($t2->acf, 1) : '—' }}</td>
                    <td style="font-weight: bold;" class="{{ $t2?->media_trimestral !== null && $t2->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                        {{ $t2?->media_trimestral !== null ? number_format($t2->media_trimestral, 1) : '—' }}
                    </td>
                    <!-- T3 -->
                    <td>{{ $t3?->acs1 !== null ? number_format($t3->acs1, 1) : '—' }}</td>
                    <td>{{ $t3?->acs2 !== null ? number_format($t3->acs2, 1) : '—' }}</td>
                    <td>{{ $t3?->acs3 !== null ? number_format($t3->acs3, 1) : '—' }}</td>
                    <td>{{ $t3?->acp !== null ? number_format($t3->acp, 1) : '—' }}</td>
                    <td>{{ $t3?->acf !== null ? number_format($t3->acf, 1) : '—' }}</td>
                    <td style="font-weight: bold;" class="{{ $t3?->media_trimestral !== null && $t3->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                        {{ $t3?->media_trimestral !== null ? number_format($t3->media_trimestral, 1) : '—' }}
                    </td>
                    <!-- Final -->
                    <td style="font-weight: bold;" class="{{ $res?->media_frequencia !== null && $res->media_frequencia < 10 ? 'text-danger' : 'text-success' }}">
                        {{ $res?->media_frequencia !== null ? number_format($res->media_frequencia, 1) : '—' }}
                    </td>
                    <td>{{ $exame?->nota !== null ? number_format($exame->nota, 1) : '—' }}</td>
                    <td style="font-weight: bold;" class="{{ $res?->media_final !== null && $res->media_final < 10 ? 'text-danger' : 'text-success' }}">
                        {{ $res?->media_final !== null ? number_format($res->media_final, 1) : '—' }}
                    </td>
                    <td style="font-weight: bold;">
                        {{ $res?->classificacao_final ?? '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <strong>LEGENDA:</strong> ACS - Avaliação Contínua Sistemática | ACP - Avaliação Contínua Parcial (Teste) | ACF - Avaliação Contínua Final (Exame Trimestral) | MT - Média Trimestral | MF - Média de Frequência | CF - Classificação Final <br>
        <strong>Critérios de Classificação Final:</strong> Dispensado: MF &ge; 14 | Admitido: 10 &le; MF < 14 (faz exame; Final = MF*0.6 + Exame*0.4) | Excluído: MF < 10 (reprova directamente).
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-line"></div>
                O Docente<br>
                _________________________________
            </td>
            <td>
                <div class="signature-line"></div>
                Pedagógico<br>
                _________________________________
            </td>
            <td>
                <div class="signature-line"></div>
                O Director da Escola<br>
                _________________________________
            </td>
        </tr>
    </table>

</body>
</html>
