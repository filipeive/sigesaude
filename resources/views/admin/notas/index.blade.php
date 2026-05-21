@extends('adminlte::page')

@section('title', 'Gestão de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-line mr-2"></i> Gestão de Notas</h1>
        <a href="{{ route('admin.notas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Novo Lançamento
        </a>
    </div>
@stop

@section('content')
    <!-- Filtros -->
    <div class="card card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.index') }}" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Buscar turma..." value="{{ request()->get('search') }}">
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="classe_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Todas as Classes --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request()->get('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Todos os Anos --</option>
                        @foreach($anosLectivos as $ano)
                            <option value="{{ $ano->id }}" {{ request()->get('ano_lectivo_id') == $ano->id ? 'selected' : '' }}>{{ $ano->ano }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-primary mb-2"><i class="fas fa-search mr-1"></i> Filtrar</button>
                <a href="{{ route('admin.notas.index') }}" class="btn btn-default mb-2 ml-1">Limpar</a>
            </form>
        </div>
    </div>

    <!-- Lista de Turmas -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Turmas ({{ $turmas->total() }})</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Turma</th>
                        <th>Classe</th>
                        <th>Ano Lectivo</th>
                        <th style="text-align:center;">Alunos</th>
                        <th style="text-align:center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($turmas as $i => $t)
                    <tr>
                        <td>{{ $turmas->firstItem() + $i }}</td>
                        <td>
                            <strong>{{ $t->classe->nome ?? 'N/A' }} - {{ $t->nome }}</strong>
                            @if($t->descricao)
                                <br><small class="text-muted">{{ $t->descricao }}</small>
                            @endif
                        </td>
                        <td>{{ $t->classe->nome ?? '—' }}</td>
                        <td><span class="badge badge-secondary">{{ $t->anoLectivo->ano ?? '—' }}</span></td>
                        <td style="text-align:center;"><span class="badge badge-info">{{ $t->estudantes_count }}</span></td>
                        <td style="text-align:center;">
                            <a href="{{ route('admin.notas.show', array_merge(request()->query(), ['turma_id' => $t->id, 'ano_lectivo_id' => $t->ano_lectivo_id])) }}"
                               class="btn btn-xs btn-success" title="Ver Notas">
                                <i class="fas fa-chart-line"></i> Notas
                            </a>
                            <a href="{{ route('admin.notas.create', ['turma_id' => $t->id, 'ano_lectivo_id' => $t->ano_lectivo_id]) }}"
                               class="btn btn-xs btn-primary" title="Lançar Notas">
                                <i class="fas fa-plus"></i> Lançar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Nenhuma turma encontrada.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $turmas->appends(request()->query())->links() }}
        </div>
    </div>
@stop
