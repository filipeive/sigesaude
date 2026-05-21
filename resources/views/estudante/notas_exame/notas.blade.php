@extends('adminlte::page')

@section('title', 'Resultados Finais & Exames')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-file-signature mr-2"></i>Resultados Finais & Exames</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-graduate mr-1"></i>
                {{ $estudante->user->name ?? 'N/A' }}
                @if($estudante->turma)
                    — {{ $estudante->turma->classe->nome ?? '' }} ({{ $estudante->turma->nome }})
                @endif
            </p>
        </div>
        <a href="{{ route('estudante.dashboard') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="card card-primary card-outline mb-3">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Ano Lectivo</h3></div>
        <div class="card-body">
            <form action="{{ route('estudante.notas_exame.notas') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label class="mr-2">Ano Lectivo:</label>
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        @foreach($anosLectivos as $a)
                            <option value="{{ $a->id }}" {{ $anoSelecionado && $anoSelecionado->id == $a->id ? 'selected' : '' }}>
                                {{ $a->ano }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(count($resultados) > 0)
    <div class="card card-danger card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-star-half-alt mr-1"></i>
                <strong>PAUTA DE RESULTADOS FINAIS</strong> — {{ $anoSelecionado->ano }}
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped mb-0" style="font-size:0.9em;">
                    <thead>
                        <tr class="bg-dark text-white text-center">
                            <th style="vertical-align:middle;text-align:left;">Disciplina</th>
                            <th class="bg-primary">Média Frequência (MF)</th>
                            <th class="bg-warning text-dark">Nota de Exame</th>
                            <th class="bg-success">Média Final (CF)</th>
                            <th class="bg-info">Classificação Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados as $item)
                            @php
                                $disc = $item['disciplina'];
                                $res  = $item['resultado'];

                                $classColor = match($res?->classificacao_final) {
                                    'Dispensado' => 'badge-success',
                                    'Aprovado'   => 'badge-primary',
                                    'Admitido'   => 'badge-warning',
                                    'Excluído'   => 'badge-danger',
                                    'Reprovado'  => 'badge-danger',
                                    default      => 'badge-secondary',
                                };
                            @endphp
                            <tr class="text-center">
                                <td class="text-left font-weight-bold">
                                    {{ $disc->nome }}
                                    @if($disc->docente?->user)
                                        <br><small class="text-muted">{{ $disc->docente->user->name }}</small>
                                    @endif
                                </td>
                                <td class="font-weight-bold {{ $res?->media_frequencia !== null && $res->media_frequencia < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $res?->media_frequencia !== null ? number_format($res->media_frequencia, 1) : '—' }}
                                </td>
                                <td>
                                    @if($res?->classificacao_final == 'Dispensado' || $res?->classificacao_final == 'Excluído')
                                        <span class="text-muted">—</span>
                                    @else
                                        {{ $res?->nota_exame !== null ? number_format($res->nota_exame, 1) : '—' }}
                                    @endif
                                </td>
                                <td class="font-weight-bold {{ $res?->media_final !== null && $res->media_final < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $res?->media_final !== null ? number_format($res->media_final, 1) : '—' }}
                                </td>
                                <td>
                                    @if($res?->classificacao_final)
                                        <span class="badge {{ $classColor }}">{{ $res->classificacao_final }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted small">
            <strong>Critérios de Avaliação e Passagem de Ano:</strong><br>
            <span class="badge badge-success">Dispensado</span> Média Frequência ≥ 14 (Não necessita exame)<br>
            <span class="badge badge-warning">Admitido</span> 10 ≤ Média Frequência < 14 (Precisa realizar o Exame Final)<br>
            <span class="badge badge-danger">Excluído</span> Média Frequência < 10 (Sem direito a exame)<br>
            <span class="badge badge-primary">Aprovado</span> Média Final (Ponderada) ≥ 10 após o exame.<br>
            <span class="badge badge-danger">Reprovado</span> Média Final < 10 após o exame.
        </div>
    </div>
    @else
        <div class="callout callout-warning">
            <h5><i class="fas fa-exclamation-triangle mr-1"></i> Sem Dados</h5>
            <p>Nenhuma disciplina encontrada para sua turma neste ano lectivo.</p>
        </div>
    @endif
@stop

@section('css')
<style>
    .table th, .table td { vertical-align: middle !important; }
    .badge { font-weight: 500; padding: 0.35em 0.6em; font-size: 0.9em; }
</style>
@stop