@extends('adminlte::page')
@section('title', 'Turmas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chalkboard mr-2"></i>Gestão de Turmas</h1>
        <a href="{{ route('admin.turmas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nova Turma
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
            <h3 class="card-title">Lista de Turmas</h3>
            <div class="card-tools">
                <form action="{{ route('admin.turmas.index') }}" method="GET" class="form-inline">
                    <select name="classe_id" class="form-control form-control-sm mr-1">
                        <option value="">Todas Classes</option>
                        @foreach($classes as $id => $nome)
                            <option value="{{ $id }}" {{ request('classe_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                    <select name="ano_lectivo_id" class="form-control form-control-sm mr-1">
                        <option value="">Todos Anos</option>
                        @foreach($anosLectivos as $id => $ano)
                            <option value="{{ $id }}" {{ request('ano_lectivo_id') == $id ? 'selected' : '' }}>{{ $ano }}</option>
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
                        <th>Turma</th>
                        <th>Classe</th>
                        <th>Ano Lectivo</th>
                        <th>Estudantes</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($turmas as $turma)
                        <tr>
                            <td>{{ $turma->id }}</td>
                            <td><strong>{{ $turma->nome }}</strong></td>
                            <td>
                                @if($turma->classe)
                                    <a href="{{ route('admin.classes.show', $turma->classe) }}">
                                        <span class="badge badge-info">{{ $turma->classe->nome }}</span>
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $turma->anoLectivo->ano ?? 'N/A' }}</td>
                            <td><span class="badge badge-primary">{{ $turma->estudantes_count }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.turmas.show', $turma) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.turmas.edit', $turma) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.turmas.destroy', $turma) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Remover esta turma?')">
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
                                Nenhuma turma cadastrada. Crie primeiro as <a href="{{ route('admin.classes.index') }}">Classes</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($turmas->hasPages())
        <div class="card-footer">{{ $turmas->links() }}</div>
        @endif
    </div>
@endsection