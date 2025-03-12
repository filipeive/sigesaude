@extends('adminlte::page')

@section('title', 'Notas Detalhadas')

@section('content_header')
    <h1>Notas Detalhadas - {{ $notaFrequencia->disciplina->nome }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('estudante.notas_detalhadas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="notas_frequencia_id" value="{{ $notaFrequencia->id }}">
                <div class="form-group">
                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="Teste 1">Teste 1</option>
                        <option value="Teste 2">Teste 2</option>
                        <option value="Teste 3">Teste 3</option>
                        <option value="Trabalho 1">Trabalho 1</option>
                        <option value="Trabalho 2">Trabalho 2</option>
                        <option value="Trabalho 3">Trabalho 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nota">Nota:</label>
                    <input type="number" name="nota" class="form-control" required min="0" max="20" step="0.01">
                </div>
                <button type="submit" class="btn btn-primary">Adicionar Nota</button>
            </form>

            <hr>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notaFrequencia->notasDetalhadas as $nota)
                        <tr>
                            <td>{{ $nota->tipo }}</td>
                            <td>{{ $nota->nota }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
