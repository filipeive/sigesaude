@extends('adminlte::page')

@section('title', 'Pauta Final Geral')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard-list mr-2"></i> Pauta Final Geral (Anual)</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Filtros -->
    <div class="card card-primary card-outline mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros de Seleção</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.pauta_final') }}" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="ano_lectivo_id" class="mr-2">Ano Lectivo:</label>
                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Selecione o Ano --</option>
                        @foreach($anosLectivos as $a)
                            <option value="{{ $a->id }}" {{ $ano && $ano->id == $a->id ? 'selected' : '' }}>{{ $a->ano }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="turma_id" class="mr-2">Turma:</label>
                    <select name="turma_id" id="turma_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Selecione a Turma --</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}" {{ $turma && $turma->id == $t->id ? 'selected' : '' }}>{{ $t->classe?->nome }} — {{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search mr-1"></i> Filtrar</button>
            </form>
        </div>
    </div>

    @if($turma && $ano && $alunos->isNotEmpty())
    <div class="card card-success card-outline">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-graduation-cap mr-1"></i>
                <strong>PAUTA DE AVALIAÇÃO GERAL</strong> — {{ $turma->classe?->nome }} ({{ $turma->nome }}) — {{ $ano->ano }}
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped table-hover mb-0" style="font-size:0.8em; min-width: 1000px;">
                    <thead>
                        <tr class="bg-dark text-white text-center">
                            <th rowspan="2" style="vertical-align:middle; width:40px;">Nº</th>
                            <th rowspan="2" style="vertical-align:middle; min-width:200px; text-align:left;">Nome Completo</th>
                            @foreach($disciplinas as $d)
                                <th colspan="4" class="text-center" style="border-left: 2px solid #555; background-color: #3f51b5; color: white;">
                                    {{ $d->nome }}
                                </th>
                            @endforeach
                            <th colspan="3" class="bg-success text-white" style="vertical-align:middle;">Resumo</th>
                            <th rowspan="2" class="bg-success text-white" style="vertical-align:middle;">Decisão Final</th>
                        </tr>
                        <tr class="text-center bg-secondary text-white" style="font-size:0.9em;">
                            @foreach($disciplinas as $d)
                                <th style="border-left: 2px solid #555;">T1</th>
                                <th>T2</th>
                                <th>T3</th>
                                <th style="background-color: #e8eaf6; color: #1a237e; font-weight: bold;">CF</th>
                            @endforeach
                            <th>Aprov.</th>
                            <th>Reprov.</th>
                            <th>Pend.</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($alunos as $idx => $aluno)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="text-left font-weight-bold">{{ $aluno->user->name ?? 'N/A' }}</td>
                            @foreach($disciplinas as $d)
                                @php
                                    $res = $aluno->resultados->get($d->id);
                                    
                                    $cfVal = $res?->media_final;
                                    $cfClass = $res?->classificacao_final;
                                    
                                    $cfStyle = 'background-color: #f5f5f5; font-weight: bold;';
                                    if ($cfClass) {
                                        if (in_array($cfClass, ['Dispensado', 'Aprovado'])) {
                                            $cfStyle = 'background-color: #e8f5e9; color: #2e7d32; font-weight: bold;';
                                        } else {
                                            $cfStyle = 'background-color: #ffebee; color: #c62828; font-weight: bold;';
                                        }
                                    }
                                @endphp
                                <td class="text-center" style="border-left: 2px solid #ddd;">
                                    {{ $res && $res->mt1 !== null ? number_format($res->mt1, 1) : '—' }}
                                </td>
                                <td class="text-center">
                                    {{ $res && $res->mt2 !== null ? number_format($res->mt2, 1) : '—' }}
                                </td>
                                <td class="text-center">
                                    {{ $res && $res->mt3 !== null ? number_format($res->mt3, 1) : '—' }}
                                </td>
                                <td class="text-center" style="{{ $cfStyle }}">
                                    {{ $cfVal !== null ? number_format($cfVal, 1) : '—' }}
                                    @if($cfClass)
                                        <br><small style="font-size:0.75em;">{{ $cfClass }}</small>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center font-weight-bold text-success">{{ $aluno->total_aprovadas }}</td>
                            <td class="text-center font-weight-bold text-danger">{{ $aluno->total_reprovadas }}</td>
                            <td class="text-center font-weight-bold text-warning">{{ $aluno->total_pendentes }}</td>
                            <td class="text-center">
                                @if($aluno->decisao_final == 'Transitou')
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Transitou</span>
                                @elseif($aluno->decisao_final == 'Não Transitou')
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Não Transitou</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> Pendente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer small text-muted">
            <strong>Legenda de Resultados por Disciplina:</strong>
            <span class="badge badge-success">Dispensado</span> / <span class="badge badge-success">Aprovado</span> — Passou |
            <span class="badge badge-danger">Excluído</span> / <span class="badge badge-danger">Reprovado</span> — Falhou |
            <span class="badge badge-warning">Admitido</span> — Aguarda Nota de Exame.
            <br>
            <strong>Regra de Decisão Final:</strong>
            <strong>Transitou</strong> (Passou em todas as disciplinas) |
            <strong>Não Transitou</strong> (Possui 1 ou mais disciplinas reprovadas/excluídas) |
            <strong>Pendente</strong> (Possui disciplinas com notas não lançadas ou classificação 'Admitido' sem nota de exame).
        </div>
    </div>
    @elseif($turma && $ano)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i> Não foram encontrados estudantes ou dados de avaliação para esta turma neste ano lectivo.
        </div>
    @else
        <div class="callout callout-info">
            <h5><i class="fas fa-info-circle mr-2"></i> Seleção Requerida</h5>
            <p>Selecione uma turma e o ano lectivo ativo acima para visualizar a pauta anual consolidada da turma.</p>
        </div>
    @endif
@stop
