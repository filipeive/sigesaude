@extends('adminlte::page')

@section('title', 'Progresso Acadêmico')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-bar mr-2"></i> Progresso Acadêmico</h1>
    </div>
@stop

@section('content')
    <!-- Filtros Gerais -->
    <div class="card card-info mb-3">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.progresso_academico.index') }}" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Ano Lectivo --</option>
                        @foreach($anosLectivos as $ano)
                            <option value="{{ $ano->id }}" {{ $anoId == $ano->id ? 'selected' : '' }}>
                                {{ $ano->ano }} {{ $ano->status == 'Ativo' ? '(Ativo)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="classe_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Todas as Classes --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $classeId == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Cards Estatísticos -->
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalAlunos }}</h3>
                    <p>Alunos Matriculados</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalAprovados }}</h3>
                    <p>Aprovados</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalReprovados }}</h3>
                    <p>Reprovados</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $taxaAprovacao }}%</h3>
                    <p>Taxa de Aprovação</p>
                </div>
                <div class="icon"><i class="fas fa-percentage"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Médias por Disciplina -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book mr-1"></i> Média Geral por Disciplina</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Disciplina</th>
                                <th style="text-align:center;width:100px;">Média</th>
                                <th style="text-align:center;width:80px;">Aprov.</th>
                                <th style="text-align:center;width:80px;">Reprov.</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($mediasPorDisciplina as $m)
                            <tr>
                                <td>{{ $m['disciplina'] }}</td>
                                <td style="text-align:center;">
                                    <span class="badge badge-{{ $m['media'] >= 10 ? 'success' : 'danger' }}">
                                        {{ number_format($m['media'], 1) }}
                                    </span>
                                </td>
                                <td style="text-align:center;color:#28a745;"><strong>+{{ $m['aprovados'] }}</strong></td>
                                <td style="text-align:center;color:#dc3545;"><strong>-{{ $m['total'] - $m['aprovados'] }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Nenhuma nota registrada.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Alunos -->
        <div class="col-md-5">
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-trophy mr-1"></i> Top 10 Alunos</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Média Geral</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($topAlunos as $i => $a)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $a->name }}<br><small class="text-muted">{{ $a->matricula }}</small></td>
                                <td>
                                    <strong class="{{ $a->media_geral >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($a->media_geral, 1) }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Sem dados.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alunos em Baixo Desempenho -->
    <div class="mt-3">
        <div class="card card-warning">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Alunos com Baixo Desempenho</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Pior Disciplina</th>
                            <th style="text-align:center;width:100px;">Nota</th>
                            <th style="text-align:center;width:100px;">Reprov.</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($alunosBaixoDesempenho as $i => $a)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $a->aluno }}</strong></td>
                            <td>{{ $a->turma }}</td>
                            <td>{{ $a->pior_disc }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-danger">{{ number_format($a->pior_media, 1) }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-warning">{{ $a->reprovacoes }}x</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhum aluno em baixo desempenho registrado.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Desempenho por Turma -->
    <div class="mt-3">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Desempenho por Turma</h3></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Turma</th>
                            <th style="text-align:center;width:120px;">Alunos</th>
                            <th style="text-align:center;width:120px;">Média Geral</th>
                            <th style="text-align:center;width:80px;">Aprov.</th>
                            <th style="text-align:center;width:80px;">Reprov.</th>
                            <th style="width:120px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($desempenhoPorTurma as $dt)
                        <tr>
                            <td>{{ $dt['turma'] }}</td>
                            <td style="text-align:center;">{{ $dt['total_alunos'] }}</td>
                            <td style="text-align:center;">
                                <span class="badge badge-{{ $dt['media_geral'] >= 10 ? 'success' : 'danger' }}">
                                    {{ number_format($dt['media_geral'], 1) }}
                                </span>
                            </td>
                            <td style="text-align:center;color:#28a745;"><strong>{{ $dt['aprovados'] }}</strong></td>
                            <td style="text-align:center;color:#dc3545;"><strong>{{ $dt['reprovados'] }}</strong></td>
                            <td>
                                <a href="{{ route('admin.progresso_academico.turma', ['turma' => collect($desempenhoPorTurma)->search($dt)+1]) }}"
                                   class="btn btn-xs btn-info" title="Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhuma turma disponível.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
