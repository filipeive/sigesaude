<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Guia de Pagamento {{ $pagamento->referencia }}</title>
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
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: bold; color: white; font-size: 10px; }
        .status-ativo    { background: #28a745; }
        .status-pendente { background: #ffc107; color: #333; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 6px; }
        code { font-size: 1.1em; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ESCOLA DOS VISIONÁRIOS</div>
        <div class="subtitle">Guia de Pagamento #<span class="ref-num">{{ $pagamento->referencia }}</span></div>
    </div>
    <div class="meta-right">Documento emitido em {{ date('d/m/Y') }}</div>

    <div class="section">
        <div class="section-title">Dados do Estudante e Pagamento</div>
        <div class="info-row"><span class="info-label">Nome:</span> <span class="info-value">{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">Matrícula:</span> <span class="info-value">{{ $pagamento->estudante?->matricula ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">Turma:</span> <span class="info-value">{{ $pagamento->estudante?->turma?->nome ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">Ano Lectivo:</span> <span class="info-value">{{ $pagamento->anoLectivo?->ano ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">Data de Vencimento:</span> <span class="info-value">{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</span></div>
        <div class="info-row"><span class="info-label">Tipo:</span> <span class="info-value">{{ ucfirst($pagamento->tipo) }}</span></div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="status-badge {{ $pagamento->status == 'pago' ? 'status-ativo' : 'status-pendente' }}">
                {{ strtoupper($pagamento->status) }}
            </span>
        </div>
    </div>

    <div class="amount-box">
        <div class="label">Valor a Pagar</div>
        <div class="value">{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</div>
    </div>

    <div class="alert-info">
        <strong><i class="fas fa-info-circle"></i> Dados para Pagamento</strong>
        <div style="margin-top:4px;">
            <strong>Entidade:</strong> <code>{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code>
            &nbsp;&nbsp;
            <strong>Referência:</strong> <code style="font-size:1.1em;">{{ $pagamento->referencia }}</code>
            &nbsp;&nbsp;
            <strong>Valor:</strong> <code>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</code>
        </div>
        <div style="margin-top:3px;">Dirija-se a qualquer ATM ou utilize o Internet Banking, escolha "Pagamento de Serviços", insira a Entidade, Referência e Valor.</div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Escola dos Visionários — Sistema de Gestão Escolar</p>
        <p>Este guia não serve como comprovativo de pagamento.</p>
    </div>
</body>
</html>
