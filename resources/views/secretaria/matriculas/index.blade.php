@extends('adminlte::page')

@section('title', 'Secretaria - Matrículas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard-list mr-2"></i>Matrículas</h1>
        <div>
            <a href="{{ route('secretaria.matriculas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Nova Matrícula
            </a>
            <a href="{{ route('secretaria.dashboard') }}" class="btn btn-default">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Acompanhamento de Matrículas</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('secretaria.matriculas.index') }}" class="form-row align-items-end mb-3">
                <div class="col-md-6">
                    <label>Estudante ou referência</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Pesquisar...">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        @foreach(['Pendente', 'Ativo', 'Cancelado'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Estudante</th>
                            <th>Turma</th>
                            <th>Ano Lectivo</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriculas as $matricula)
                            <tr>
                                <td><code>{{ $matricula->referencia ?? 'N/A' }}</code></td>
                                <td>
                                    <strong>{{ $matricula->estudante?->user?->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $matricula->estudante?->matricula ?? '' }}</small>
                                </td>
                                <td>{{ $matricula->turma?->nome ?? 'N/A' }}</td>
                                <td>{{ $matricula->anoLectivo?->ano ?? 'N/A' }}</td>
                                <td>{{ $matricula->valor ? number_format($matricula->valor, 2, ',', '.') . ' MZN' : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $matricula->status === 'Ativo' ? 'success' : ($matricula->status === 'Cancelado' ? 'danger' : 'warning') }}">
                                        {{ $matricula->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('secretaria.matriculas.show', $matricula) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye mr-1"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Nenhuma matrícula encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $matriculas->links() }}
        </div>
    </div>
@stop
