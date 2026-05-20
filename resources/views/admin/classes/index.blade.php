@extends('adminlte::page')
@section('title', 'Classes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-school mr-2"></i>Gestão de Classes</h1>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nova Classe
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
            <h3 class="card-title">Níveis Escolares</h3>
            <div class="card-tools">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Pesquisar..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th width="60">#</th>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Turmas</th>
                        <th>Disciplinas</th>
                        <th>Descrição</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $classe)
                        <tr>
                            <td>{{ $classe->id }}</td>
                            <td><strong>{{ $classe->nome }}</strong></td>
                            <td><span class="badge badge-info">{{ $classe->nivel }}º</span></td>
                            <td><span class="badge badge-primary">{{ $classe->turmas_count }}</span></td>
                            <td><span class="badge badge-success">{{ $classe->disciplinas_count }}</span></td>
                            <td>{{ Str::limit($classe->descricao, 40) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.classes.show', $classe) }}" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja remover esta classe?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle fa-2x d-block mb-2"></i>
                                Nenhuma classe cadastrada. Comece criando as classes (8ª, 9ª, 10ª, etc.)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($classes->hasPages())
        <div class="card-footer">
            {{ $classes->links() }}
        </div>
        @endif
    </div>
@endsection
