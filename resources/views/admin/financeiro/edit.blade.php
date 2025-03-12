@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Editar Transação</h1>
    <form action="{{ route('admin.financeiro.update', $transacao->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="{{ $transacao->descricao }}" required>
        </div>
        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="number" name="valor" id="valor" class="form-control" value="{{ $transacao->valor }}" required>
        </div>
        <div class="form-group">
            <label for="data">Data</label>
            <input type="date" name="data" id="data" class="form-control" value="{{ $transacao->data }}" required>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select name="tipo" id="tipo" class="form-control" required>
                <option value="entrada" {{ $transacao->tipo == 'entrada' ? 'selected' : '' }}>Entrada</option>
                <option value="saida" {{ $transacao->tipo == 'saida' ? 'selected' : '' }}>Saída</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection