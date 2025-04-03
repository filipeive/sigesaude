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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Tipo</th>
                            <th>Média Freq</th>
                            <th>Exame Normal</th>
                            <th>Exame Recorrência</th>
                            <th>Média Final</th>
                            <th>Resultado Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disciplinasComNotas as $disciplina)
                            <tr>
                                <td>{{ $disciplina['disciplina']->nome }}</td>
                                <td>
                                    <span class="badge badge-{{ $disciplina['tipo_inscricao'] == 'Normal' ? 'info' : 'warning' }}">
                                        {{ $disciplina['tipo_inscricao'] }}
                                    </span>
                                </td>
                                <td>
                                    @if($disciplina['media_freq'])
                                        <span class="badge badge-{{ $disciplina['media_freq'] >= 10 ? 'success' : 'danger' }}">
                                            {{ $disciplina['media_freq'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina['exame_normal'])
                                        <span class="badge badge-{{ $disciplina['exame_normal'] >= 10 ? 'success' : 'danger' }}">
                                            {{ $disciplina['exame_normal'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina['exame_recorrencia'])
                                        <span class="badge badge-{{ $disciplina['exame_recorrencia'] >= 10 ? 'success' : 'danger' }}">
                                            {{ $disciplina['exame_recorrencia'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina['media_final'])
                                        <span class="badge badge-{{ $disciplina['media_final'] >= 10 ? 'success' : 'danger' }}">
                                            {{ $disciplina['media_final'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina['tem_nota'])
                                        <span class="badge badge-{{ 
                                            $disciplina['resultado_final'] == 'Aprovado' || $disciplina['resultado_final'] == 'Dispensado' ? 'success' : 
                                            ($disciplina['resultado_final'] == 'Pendente' ? 'warning' : 'danger') 
                                        }}">
                                            {{ $disciplina['resultado_final'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma disciplina inscrita neste ano letivo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Legenda -->
            <div class="mt-4">
                <h5>Legenda:</h5>
                <div class="d-flex flex-wrap">
                    <span class="badge badge-success mr-2 mb-2">Aprovado/Dispensado: Nota ≥ 10</span>
                    <span class="badge badge-danger mr-2 mb-2">Reprovado: Nota < 10</span>
                    <span class="badge badge-warning mr-2 mb-2">Pendente: Avaliação em andamento</span>
                    <span class="badge badge-info mr-2 mb-2">Normal: Primeira inscrição</span>
                    <span class="badge badge-warning mr-2 mb-2">Em Atraso: Repetição da disciplina</span>
                    <span class="badge badge-secondary mr-2 mb-2">(-): Nota não registrada</span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .badge {
        font-size: 100%;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop