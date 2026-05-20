<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Pagamento {{ $pagamento->referencia }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px solid #0056b3; padding-bottom: 8px; margin-bottom: 15px; }
        .header .logo { font-size: 22px; font-weight: bold; color: #0056b3; }
        .header .subtitle { font-size: 13px; color: #555; }
        .recibo-num { color: #0056b3; font-weight: bold; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.info-table td { padding: 5px 8px; }
        table.info-table td:first-child { font-weight: bold; width: 160px; background: #f0f4f8; }
        .valor-box { margin: 15px 0; border: 2px solid #0056b3; border-radius: 6px; padding: 12px 20px; display: inline-block; }
        .valor-box .label { font-size: 11px; color: #555; text-transform: uppercase; }
        .valor-box .valor { font-size: 22px; font-weight: bold; color: #0056b3; }
        .assinaturas { margin-top: 40px; display: flex; justify-content: space-around; }
        .assinatura { width: 200px; border-top: 1px solid #333; text-align: center; padding-top: 5px; font-size: 11px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #888; }
        .section { margin-bottom: 15px; }
        .section-title { font-size: 11px; color: #0056b3; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; border-bottom: 1px solid #0056b3; padding-bottom: 2px; }
        .status-pago    { background: #28a745; color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; }
        .status-pendente{ background: #ffc107; color: #333; padding: 3px 10px; border-radius: 4px; font-size: 11px; }
        .status-cancelado{ background: #dc3545; color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ESCOLA DOS VISIONÁRIOS</div>
        <div class="subtitle">Sistema de Gestão Escolar — Recibo de Pagamento</div>
        <div class="subtitle" style="margin-top:4px;">Recibo Nº: <span class="recibo-num">{{ $pagamento->referencia }}</span></div>
    </div>

    <!-- Dados do Estudante -->
    <div class="section">
        <div class="section-title">Dados do Estudante</div>
        <table class="info-table">
            <tr>
                <td>Nome:</td>
                <td>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</td>
                <td>Matrícula:</td>
                <td>{{ $pagamento->estudante?->matricula ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Turma:</td>
                <td>{{ $pagamento->estudante?->turma?->nome ?? 'N/A' }} ({{ $pagamento->estudante?->turma?->classe?->nome ?? '' }})</td>
                <td>Ano Lectivo:</td>
                <td>{{ $pagamento->anoLectivo?->ano ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Dados do Pagamento -->
    <div class="section">
        <div class="section-title">Dados do Pagamento</div>
        <table class="info-table">
            <tr>
                <td>Data de Vencimento:</td>
                <td>{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                <td>Status:</td>
                <td>
                    <span class="status-{{ $pagamento->status }}">
                        {{ ucfirst($pagamento->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Data de Pagamento:</td>
                <td>{{ $pagamento->data_pagamento ? \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') : '—' }}</td>
                <td>Valor:</td>
                <td rowspan="2" colspan="3">
                    <div class="valor-box">
                        <div class="label">Valor Pago</div>
                        <div class="valor">{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Referência:</td>
                <td><code>{{ $pagamento->referencia }}</code></td>
            </tr>
            @if($pagamento->descricao)
            <tr>
                <td>Descrição:</td>
                <td colspan="3">{{ $pagamento->descricao }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Aviso -->
    <div style="margin-top:15px;padding:10px 15px;background:#e8f4fd;border-left:4px solid #0056b3;border-radius:4px;font-size:11px;">
        <strong><i class="fas fa-info-circle"></i> Observações:</strong>
        @if($pagamento->status == 'pago')
            Este recibo confirma o pagamento da propina referenciada acima.
        @else
            Este pagamento encontra-se em estado <strong>{{ ucfirst($pagamento->status) }}</strong>.
            As propinas mensais devem ser saldadas até ao dia 10 de cada mês.
        @endif
    </div>

    <!-- Assinaturas -->
    <div class="assinaturas">
        <div class="assinatura">Assinatura do Responsável / Encarregado</div>
        <div class="assinatura">Assinatura do Admin / Tesoureiro</div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Escola dos Visionários — Sistema de Gestão Escolar</p>
        <p>Recibo gerado em {{ date('d/m/Y H:i') }} — Para dúvidas contacte a secretaria.</p>
    </div>
</body>
</html>
