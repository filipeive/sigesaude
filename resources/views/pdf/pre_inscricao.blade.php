<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovativo de Pré-Inscrição</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #007bff; }
        .title { font-size: 18px; margin-top: 10px; }
        .info-section { margin-bottom: 20px; }
        .info-label { font-weight: bold; width: 150px; display: inline-block; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        .code-box { background: #f4f4f4; padding: 15px; border: 1px dashed #007bff; text-align: center; margin: 20px 0; }
        .deadline { color: #d9534f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ESCOLA DOS VISIONÁRIOS</div>
        <div class="title">Comprovativo de Pré-Inscrição Online</div>
    </div>

    <div class="info-section">
        <p><span class="info-label">Nome Completo:</span> {{ $preInscricao->nome_completo }}</p>
        <p><span class="info-label">Telefone:</span> {{ $preInscricao->telefone }}</p>
        <p><span class="info-label">Email:</span> {{ $preInscricao->email ?? 'N/A' }}</p>
        <p><span class="info-label">Classe:</span> {{ $preInscricao->classe->nome }}</p>
        <p><span class="info-label">Ano Lectivo:</span> {{ $preInscricao->anoLectivo->ano }}</p>
    </div>

        <div class="code-box">
            <strong>CÓDIGO DE PRÉ-INSCRIÇÃO:</strong>
            <h2 style="margin: 10px 0;">{{ $preInscricao->codigo_pre_inscricao }}</h2>
        </div>

        <div class="code-box" style="border-color: #28a745; background: #f0fff4;">
            <strong style="color: #28a745;">REFERÊNCIA DE PAGAMENTO (ENTIDADE 11151):</strong>
            <h2 style="margin: 10px 0; font-size: 28px; letter-spacing: 2px;">{{ $preInscricao->referencia }}</h2>
            <p style="margin: 0; font-weight: bold;">Valor: {{ number_format($preInscricao->valor, 2, ',', '.') }} MZN</p>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #e3f2fd; border: 1px solid #90caf9; border-radius: 8px;">
            <strong>Instruções de Pagamento:</strong>
            <ol style="padding-left: 20px; margin-top: 8px; margin-bottom: 8px;">
                <li>Efectue o pagamento em qualquer ATM dos bancos parceiros (BCI ou Ponto24) ou via Internet Banking.</li>
                <li>Escolha <strong>Pagamentos &gt; Pagamento de Serviços</strong>.</li>
                <li>Insira a <strong>Entidade: 11151</strong> e a <strong>Referência: {{ $preInscricao->referencia }}</strong>.</li>
                <li>Digite o valor <strong>{{ number_format($preInscricao->valor, 2, ',', '.') }} MZN</strong> e confirme.</li>
                <li>Guarde o comprovante e envie-o pela plataforma <strong>antes da data limite</strong>.</li>
            </ol>
            <p style="margin: 0; font-size: 0.85em; color: #666;">
                <i class="fas fa-exclamation-circle mr-1"></i>
                A pré-inscrição só é confirmada após o pagamento e envio do comprovativo.
            </p>
        </div>

        <div class="info-section">
            <p>Apresente este documento na secretaria da escola para confirmar a sua matrícula, ou envie o comprovativo pela plataforma antes da data limite.</p>
            <p class="deadline">DATA LIMITE PARA CONFIRMAÇÃO: {{ $preInscricao->data_limite->format('d/m/Y H:i') }}</p>
        </div>

        <div style="margin-top: 30px;">
            <p>__________________________________________</p>
            <p>Assinatura do Candidato/Responsável</p>
        </div>


    <div class="footer">
        <p>&copy; {{ date('Y') }} Escola dos Visionários - Sistema de Gestão Escolar</p>
        <p>Cidade de Maputo, Moçambique | contato@escolavisionarios.edu</p>
    </div>
</body>
</html>
