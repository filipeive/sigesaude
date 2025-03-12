@extends('adminlte::page')


@section('content')
<div class="container">
    <h1>Detalhes da Transação</h1>
    <table class="table table-bordered">
        <tr>
            <th>Descrição</th>
            <td>{{ $transacao->descricao }}</td>
        </tr>
        <tr>
            <th>Valor</th>
            <td>{{ number_format($transacao->valor, 2, ',', '.') }} MZN</td>
        </tr>
        <tr>
            <th>Data</th>
            <td>{{ date('d-m-Y', strtotime($transacao->data)) }}</td>
        </tr>
        <tr>
            <th>Tipo</th>
            <td>{{ ucfirst($transacao->tipo) }}</td>
        </tr>
    </table>
    <a href="{{ route('admin.financeiro.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection