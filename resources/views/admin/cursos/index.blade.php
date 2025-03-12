@extends('adminlte::page')

@section('title', 'Gestão de Cursos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"> 
                <i class="fas fa-book mr-2"></i> Gestão de Cursos
            </h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Cursos</li>
            </ol>
        </div>
        <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Criar Curso
        </a>
    </div>
@stop

@section('content')
    {{-- Alertas de Sistema --}}
    <div id="alerts-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @foreach (['error', 'updated', 'deleted'] as $msg)
            @if (session($msg))
                <div class="alert alert-{{ $msg == 'error' ? 'danger' : ($msg == 'deleted' ? 'warning' : 'info') }} alert-dismissible fade show"
                    role="alert">
                    <i
                        class="fas fa-{{ $msg == 'error' ? 'times' : ($msg == 'deleted' ? 'trash' : 'info') }}-circle mr-2"></i>
                    {{ session($msg) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Card Principal --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-book mr-2"></i> Lista de Cursos
                </h3>
                <div class="card-tools">
                    <form action="{{ route('admin.cursos.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" id="search" class="form-control float-right"
                                placeholder="Pesquisar curso..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Total de Estudantes</th>
                            <th>Total de Disciplinas</th>
                            <th style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cursos as $curso)
                            <tr>
                                <td>{{ $curso->nome }}</td>
                                <td>{{ $curso->descricao ?? 'N/A' }}</td>
                                <td>{{ $curso->estudantes_count }}</td>
                                <td>{{ $curso->disciplinas_count }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.cursos.show', $curso->id) }}" class="btn btn-info btn-sm" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-warning btn-sm" title="Editar curso">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir curso" onclick="return confirm('Tem certeza que deseja excluir?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-search mr-2"></i>Nenhum curso encontrado
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right" id="pagination">
                {{ $cursos->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .badge {
            padding: 6px 10px;
            font-weight: 500;
        }

        #alerts-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }

        .alert {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@stop