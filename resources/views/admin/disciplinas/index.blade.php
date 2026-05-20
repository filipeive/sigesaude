@extends('adminlte::page')
@section('title', 'Disciplinas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-book mr-2"></i>Gestão de Disciplinas</h1>
        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nova Disciplina
        </a>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Lista de Disciplinas</h3>
            <div class="card-tools">
                <form action="{{ route('admin.disciplinas.index') }}" method="GET" class="form-inline">
                    <select name="classe_id" class="form-control form-control-sm mr-1">
                        <option value="">Todas Classes</option>
                        @foreach($classes as $id => $nome)
                            <option value="{{ $id }}" {{ request('classe_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm mr-1" placeholder="Pesquisar..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-default"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Disciplina</th>
                        <th>Classe</th>
                        <th>Docente</th>
                        <th>Carga Horária</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disciplinas as $disciplina)
                        <tr>
                            <td>{{ $disciplina->id }}</td>
                            <td><strong>{{ $disciplina->nome }}</strong></td>
                            <td>
                                @if($disciplina->classe)
                                    <a href="{{ route('admin.classes.show', $disciplina->classe) }}">
                                        <span class="badge badge-info">{{ $disciplina->classe->nome }}</span>
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $disciplina->docente->user->name ?? 'Não atribuído' }}</td>
                            <td>{{ $disciplina->carga_horaria ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.disciplinas.show', $disciplina->id) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.disciplinas.edit', $disciplina->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.disciplinas.destroy', $disciplina->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Remover esta disciplina?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle fa-2x d-block mb-2"></i>
                                Nenhuma disciplina cadastrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($disciplinas->hasPages())
        <div class="card-footer">{{ $disciplinas->links() }}</div>
        @endif
    </div>
@endsection