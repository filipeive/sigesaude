@extends('adminlte::page')

@section('title', 'Notas de Frequência')

@section('content_header')
    <h1>Notas de Frequência - {{ $estudante->nome }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Seletor de Ano Letivo -->
            <form action="{{ route('estudante.notas.frequencia') }}" method="GET" class="mb-4">
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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Tipo</th>
                            <th>Nota Final</th>
                            <th>Status</th>
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
                                    @if($disciplina['tem_nota'])
                                        <span class="badge badge-{{ $disciplina['nota_frequencia'] >= 10 ? 'success' : 'danger' }}">
                                            {{ $disciplina['nota_frequencia'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Pendente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disciplina['tem_nota'])
                                        <span class="badge badge-{{ 
                                            $disciplina['status'] == 'Aprovado' ? 'success' : 
                                            ($disciplina['status'] == 'Dispensado' ? 'primary' : 'danger') 
                                        }}">
                                            {{ $disciplina['status'] }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Em Avaliação</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Nenhuma disciplina inscrita neste ano letivo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Legenda -->
            <div class="mt-4">
                <h5>Legenda:</h5>
                <div class="d-flex flex-wrap">
                    <span class="badge badge-success mr-2 mb-2">Aprovado: Nota ≥ 10</span>
                    <span class="badge badge-danger mr-2 mb-2">Reprovado: Nota < 10</span>
                    <span class="badge badge-primary mr-2 mb-2">Dispensado: Isento de exame</span>
                    <span class="badge badge-warning mr-2 mb-2">Em Avaliação: Processo em andamento</span>
                    <span class="badge badge-secondary mr-2 mb-2">Pendente: Sem notas registradas</span>
                    <span class="badge badge-info mr-2 mb-2">Normal: Primeira inscrição</span>
                    <span class="badge badge-warning mr-2 mb-2">Em Atraso: Repetição da disciplina</span>
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