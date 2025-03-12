@extends('adminlte::page')

@section('title', 'Criar Pagamento')

@section('content_header')
    <h1>Criar Pagamento</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pagamentos.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="estudante_id">Estudante</label>
                    <select name="estudante_id" id="estudante_id" class="form-control" required>
                        @foreach($estudantes as $estudante)
                            <option value="{{ $estudante->id }}">{{ $estudante->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_vencimento">Data de Vencimento</label>
                    <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Criar Pagamento</button>
            </form>
        </div>
    </div>
@stop