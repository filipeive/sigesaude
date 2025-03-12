<!DOCTYPE html>
<html>
<head>
    <title>Notificação de Pagamento</title>
</head>
<body>
    <h1>Olá, {{ $pagamento->estudante->user->name }}!</h1>
    <p>Você tem um pagamento pendente com os seguintes detalhes:</p>
    <ul>
        <li><strong>Referência:</strong> {{ $pagamento->referencia }}</li>
        <li><strong>Valor:</strong> {{ number_format($pagamento->valor, 2, ',', '.') }} MZN</li>
        <li><strong>Data de Vencimento:</strong> {{ $pagamento->data_vencimento->format('d/m/Y') }}</li>
    </ul>
    <p>Por favor, efetue o pagamento até a data de vencimento.</p>
    <p>Atenciosamente,<br>Equipe de Administração</p>
</body>
</html>