@extends('adminlte::page')

@section('title', 'Notas de Exame')

@section('content_header')
    <h1>Notas de Exame - {{ $estudante->nome }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Seletor de Ano Letivo -->
            <form action="{{ route('estudante.notas_exame.notas') }}" method="GET" class="mb-4">
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

            <!-- Tabela de Notas de Exame -->
            <h3>Notas de Exame</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Média Freq</th>
                        <th>Exame Normal</th>
                        <th>Exame Recorrência</th>
                        <th>Média Final</th>
                        <th>Resultado Final</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notasExame as $notaExame)
                        <tr>
                            <td>{{ $notaExame->disciplina->nome }}</td>
                            <td>{{ $notaExame->media_freq }}</td>
                            <td>{{ $notaExame->exame_normal }}</td>
                            <td>{{ $notaExame->exame_recorrencia }}</td>
                            <td>{{ $notaExame->media_final }}</td>
                            <td>{{ $notaExame->resultado_final }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma nota de exame registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- <!-- Tabela de Médias Finais -->
            <h3>Médias Finais</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Média Final</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mediasFinais as $mediaFinal)
                        <tr>
                            <td>{{ $mediaFinal->disciplina->nome }}</td>
                            <td>{{ $mediaFinal->media }}</td>
                            <td>{{ $mediaFinal->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Nenhuma média final registrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table> --}}
        </div>
    </div>
@stop