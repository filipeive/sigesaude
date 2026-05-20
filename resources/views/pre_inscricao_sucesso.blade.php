<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pré-Inscrição Realizada - Escola dos Visionários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
    <style>
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .btn-download {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn-download:hover {
            background: #0056b3;
        }
    </style>
</head>
<body style="background-color: #f4f7f6;">
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Pré-Inscrição Realizada!</h2>
        <p>Olá, <strong>{{ $preInscricao->nome_completo }}</strong>. Sua pré-inscrição para a <strong>{{ $preInscricao->classe->nome }}</strong> foi registada com sucesso.</p>
        
        <div style="background: #e9ecef; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: left;">
            <p><strong>Código de Inscrição:</strong> {{ $preInscricao->codigo_pre_inscricao }}</p>
            <p><strong>Referência de Pagamento:</strong> {{ $preInscricao->referencia }}</p>
            <p><strong>Valor da Taxa de Inscrição:</strong> {{ number_format($preInscricao->valor, 2, ',', '.') }} MZN</p>
            <p><strong>Data Limite para Confirmação:</strong> {{ $preInscricao->data_limite->format('d/m/Y H:i') }}</p>
            <p style="font-size: 0.9em; color: #666;">* Após esta data, a sua vaga poderá ser disponibilizada para outros interessados.</p>
        </div>

        <div style="background: #e3f2fd; border: 2px solid #90caf9; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: left;">
            <h4 style="color: #1565c0; margin-top: 0; margin-bottom: 15px;">
                <i class="fas fa-credit-card"></i> Como Realizar o Pagamento
            </h4>
            <ol style="padding-left: 20px; margin-bottom: 10px;">
                <li>Dirija-se a qualquer <strong>ATM</strong> dos bancos parceiros (<strong>BCI</strong> ou <strong>Ponto24</strong>)</li>
                <li>Selecione <strong>Pagamentos &gt; Pagamento de Serviços</strong></li>
                <li>Insira a <strong>Entidade: 11151</strong></li>
                <li>Insira a <strong>Referência: {{ $preInscricao->referencia }}</strong></li>
                <li>Digite o <strong>valor</strong> de {{ number_format($preInscricao->valor, 2, ',', '.') }} MZN e confirme</li>
            </ol>
            <p style="margin-bottom: 10px;"><strong>Métodos Alternativos:</strong></p>
            <ul style="padding-left: 20px;">
                <li><strong>Internet Banking</strong> — Acesse sua conta online e efectue o pagamento por Pagamento de Serviços.</li>
                <li><strong>Depósito Bancário</strong> — Efectue o depósito ou transferência na conta da escola, mencionando o seu nome completo.</li>
            </ul>
            <p style="margin-bottom: 0;">
                <i class="fas fa-exclamation-triangle mr-1" style="color: #f57f17;"></i>
                Após efectuar o pagamento, <strong>envie o comprovante pela plataforma</strong> ou na secretaria até 
                <strong>{{ $preInscricao->data_limite->format('d/m/Y H:i') }}</strong> para confirmar a sua vaga.
            </p>
        </div>

        <a href="{{ route('pre-inscricao.pdf', $preInscricao->codigo_pre_inscricao) }}" class="btn-download">
            <i class="fas fa-file-pdf mr-2"></i> Descarregar Comprovativo (PDF)
        </a>
        <br><br>
        <a href="/" style="color: #666; text-decoration: none;">Voltar para a página inicial</a>
    </div>
</body>
</html>
