@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>Configurações de Pagamento</h1>
    <form action="{{ route('admin.financeiro.configuracoes.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="metodo_pagamento">Método de Pagamento</label>
            <input type="text" name="metodo_pagamento" id="metodo_pagamento" class="form-control" value="{{ $configuracoes->metodo_pagamento }}" required>
        </div>
        <div class="form-group">
            <label for="detalhes">Detalhes</label>
            <textarea name="detalhes" id="detalhes" class="form-control">{{ $configuracoes->detalhes }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection