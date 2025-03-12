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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Nota de Frequência</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notasFrequencia as $notaFrequencia)
                        <tr>
                            <td>{{ $notaFrequencia->disciplina->nome }}</td>
                            <td>{{ $notaFrequencia->nota_frequencia }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Nenhuma nota de frequência registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop