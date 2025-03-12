@extends('adminlte::page')

@section('title', 'Notas de Frequência')

@section('content_header')
    <h1>Notas de Frequência - {{ $estudante->nome }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Seletor de Ano Letivo -->
            <form action="{{ route('estudante.notas_frequencia.notas') }}" method="GET" class="mb-4">
                <div class="form-group">
                    <label for="ano_letivo_id">Selecione o Ano Letivo:</label>
                    <select name="ano_letivo_id" id="ano_letivo_id" class="form-control" onchange="this.form.submit()">
                        @foreach($anosLetivos as $anoLetivo)
                            <option value="{{ $anoLetivo->id }}" {{ $anoLetivoAtual->id == $anoLetivo->id ? 'selected' : '' }}>
                                {{ $anoLetivo->ano }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Tabela de Notas de Frequência -->
            <h3>Notas de Frequência</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Teste 1</th>
                        <th>Teste 2</th>
                        <th>Teste 3</th>
                        <th>Trab 1</th>
                        <th>Trab 2</th>
                        <th>Trab 3</th>
                        <th>Média</th>
                        <th>Classificação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notasFrequencia as $notaFrequencia)
                        <tr>
                            <td>{{ $notaFrequencia->disciplina->nome }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Teste 1')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Teste 2')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Teste 3')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Trabalho 1')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Trabalho 2')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->notasDetalhadas->where('tipo', 'Trabalho 3')->first()->nota ?? '-' }}</td>
                            <td>{{ $notaFrequencia->nota }}</td>
                            <td>{{ $notaFrequencia->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Nenhuma nota de frequência registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop