@extends('adminlte::page')

@section('title', 'Secretaria - Estudantes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-graduate mr-2"></i>Estudantes</h1>
        <div>
            <a href="{{ route('secretaria.estudantes.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-1"></i> Novo Estudante
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
            <h3 class="card-title">Consulta de Estudantes</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('secretaria.estudantes.index') }}" class="form-row align-items-end mb-3">
                <div class="col-md-4">
                    <label>Nome, email ou matrícula</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Pesquisar...">
                </div>
                <div class="col-md-3">
                    <label>Turma</label>
                    <select name="turma" class="form-control">
                        <option value="">Todas</option>
                        @foreach($turmas as $id => $nome)
                            <option value="{{ $id }}" {{ request('turma') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        @foreach(['Ativo', 'Inativo', 'Concluído', 'Desistente'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Estudante</th>
                            <th>Matrícula</th>
                            <th>Turma</th>
                            <th>Ano Lectivo</th>
                            <th>Status</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudantes as $estudante)
                            <tr>
                                <td>
                                    <strong>{{ $estudante->user?->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $estudante->user?->email ?? '' }}</small>
                                </td>
                                <td>{{ $estudante->matricula }}</td>
                                <td>{{ $estudante->turma?->nome ?? 'N/A' }}</td>
                                <td>{{ $estudante->anoLectivo?->ano ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $estudante->status === 'Ativo' ? 'success' : 'secondary' }}">
                                        {{ $estudante->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('secretaria.estudantes.show', $estudante) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('secretaria.estudantes.edit', $estudante) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Nenhum estudante encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $estudantes->links() }}
        </div>
    </div>
@stop
