<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guia de Matrícula</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #222; font-size: 11px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 3px solid #0056b3; padding-bottom: 8px; margin-bottom: 12px; }
        .logo { font-size: 20px; font-weight: bold; color: #0056b3; }
        .subtitle { font-size: 13px; color: #444; margin-top: 3px; }
        .ref-num { font-size: 15px; font-weight: bold; color: #0056b3; }
        .meta-right { text-align: right; font-size: 10px; margin-bottom: 8px; }
        .row-info { display: flex; gap: 20px; margin-bottom: 8px; }
        .col-info { flex: 1; }
        .section { margin-bottom: 8px; border: 1px solid #e0e0e0; border-radius: 5px; padding: 8px 10px; }
        .section-title { font-size: 10px; font-weight: bold; color: #0056b3; text-transform: uppercase; border-bottom: 1px solid #cce; padding-bottom: 3px; margin-bottom: 6px; }
        .info-row { display: flex; margin-bottom: 3px; }
        .info-label { font-weight: bold; width: 130px; flex-shrink: 0; }
        .info-value { flex: 1; }
        .amount-box { background: #0056b3; color: white; padding: 10px 15px; border-radius: 5px; text-align: center; margin: 10px 0; }
        .amount-box .label { font-size: 10px; text-transform: uppercase; opacity: .8; }
        .amount-box .value { font-size: 20px; font-weight: bold; }
        .alert-info { background: #e3f2fd; border-left: 4px solid #1976d2; padding: 8px 10px; border-radius: 4px; margin-bottom: 8px; font-size: 10px; }
        .alert-warn { background: #fff8e1; border-left: 4px solid #fbc02d; padding: 8px 10px; border-radius: 4px; margin-bottom: 8px; font-size: 10px; }
        .assinaturas { margin-top: 25px; display: flex; justify-content: space-around; }
        .assinatura { width: 200px; border-top: 1px solid #333; text-align: center; padding-top: 4px; font-size: 10px; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: bold; color: white; font-size: 10px; }
        .status-ativo    { background: #28a745; }
        .status-pendente { background: #ffc107; color: #333; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 6px; }
        code { font-size: 1.1em; }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <div class="logo">ESCOLA DOS VISIONÁRIOS</div>
        <div class="subtitle">Guia de Matrícula #<span class="ref-num">{{ $matricula->referencia }}</span></div>
    </div>
    <div class="meta-right">Documento emitido em {{ date('d/m/Y') }}</div>

    <!-- Linha 1: Aluno |
        <div class="col-info"><strong>Nº Matrícula:</strong> {{ $matricula->estudante?->matricula ?? 'N/A' }}</div>
        @if($matricula->estudante?->user?->genero)
        <div class="col-info"><strong>Gênero:</strong> {{ $matricula->estudante?->user?->genero }}</div>
        @endif
    </div>

    <!-- Dados Académicos -->
    <div class="section">
        <div class="section-title">Dados Académicos</div>
        <div class="row-info">
            <div class="col-info"><span class="info-label">Ano Lectivo:</span> {{ $matricula->anoLectivo?->ano ?? 'N/A' }}</div>
            <div class="col-info"><span class="info-label">Classe:</span> {{ $matricula->turma?->classe?->nome ?? 'N/A' }}</div>
            <div class="col-info"><span class="info-label">Data Matrícula:</span>
                {{ $matricula->data_matricula ? \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') : 'N/A' }}
            </div>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="status-badge {{ $matricula->status == 'Ativo' ? 'status-ativo' : 'status-pendente' }}">
                {{ strtoupper($matricula->status) }}
            </span>
        </div>
    </div>

    <!-- Valor -->
    <div class="amount-box">
        <div class="label">Valor da Matrícula Anual</div>
        <div class="value">{{ number_format($matricula->valor, 2, ',', '.') }} MZN</div>
    </div>

    <!-- Instruções de Pagamento -->
    <div class="alert-info">
        <strong><i class="fas fa-info-circle"></i> Instruções de Pagamento da Matrícula</strong>
        <div style="margin-top:4px;">
            <strong>Entidade:</strong> <code>11151</code>
            &nbsp;&nbsp;
            <strong>Referência:</strong> <code style="font-size:1.1em;">{{ $matricula->referencia }}</code>
        </div>
        <div style="margin-top:3px;">Para pagar a matrícula anual, dirija-se a qualquer ATM ou Internet Banking e use os dados acima.</div>
    </div>

    <!-- Aviso Propinas -->
    <div class="alert-warn">
        <strong><i class="fas fa-exclamation-triangle"></i> Aviso — Propinas Mensais</strong>
        <div style="margin-top:3px;">As propinas mensais são geradas automaticamente e devem ser pagas até ao dia 10 de cada mês. Cada mensalidade tem a sua própria referência, disponível na área do estudante.</div>
    </div>

    <!-- Comprovativo -->
    @if($matricula->comprovativo)
    <div class="section">
        <div class="section-title">Comprovativo de Pagamento</div>
        <div class="info-row"><span class="info-label">Arquivo:</span>
            <a href="{{ Storage::url($matricula->comprovativo) }}" target="_blank" style="color:#0056b3;">Ver / Baixar Comprovativo</a>
        </div>
    </div>
    @endif

    <!-- Assinaturas -->
    <div class="assinaturas">
        <div class="assinatura">Assinatura do Estudante</div>
        <div class="assinatura">Assinatura da Secretaria</div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Escola dos Visionários — Sistema de Gestão Escolar</p>
        <p>Este guia comprova a vinculação do estudante à instituição para o ano lectivo indicado.</p>
    </div>
</body>
</html>
