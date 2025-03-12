@extends('adminlte::page')

@section('title', 'Gestão de Docentes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-chalkboard-teacher"></i> Gestão de Docentes</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Docentes</li>
            </ol>
        </div>
        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Novo Docente
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Card Principal --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> Lista de Docentes
                </h3>
                <div class="card-tools">
                    <form action="{{ route('admin.docentes.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" id="search" class="form-control float-right"
                                placeholder="Pesquisar docente..." value="{{ request('search') }}">
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
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Departamento</th>
                            <th>Formação</th>
                            <th>Status</th>
                            <th style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($docentes as $docente)
                            <tr>
                                <td>{{ $docente->id }}</td>
                                <td>
                                    @if($docente->user->foto_perfil)
                                        <img src="{{ asset('storage/' . $docente->user->foto_perfil) }}" 
                                             class="img-circle elevation-2" 
                                             alt="Foto de perfil" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('img/default-profile.png') }}" 
                                             class="img-circle elevation-2" 
                                             alt="Foto padrão" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>{{ $docente->user->name }}</td>
                                <td>{{ $docente->user->email }}</td>
                                <td>{{ $docente->user->telefone }}</td>
                                <td>{{ $docente->departamento->nome ?? 'N/A' }}</td>
                                <td>{{ $docente->formacao }}</td>
                                <td>
                                    <span class="badge badge-{{ $docente->status == 'Ativo' ? 'success' : 'danger' }}">
                                        {{ $docente->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.docentes.show', $docente->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.docentes.edit', $docente->id) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteModal{{ $docente->id }}"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal de exclusão -->
                                    <div class="modal fade" id="deleteModal{{ $docente->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Tem certeza que deseja excluir o docente <strong>{{ $docente->user->name }}</strong>?</p>
                                                    <p class="text-danger"><strong>Atenção:</strong> Esta ação não pode ser desfeita e também excluirá o usuário associado.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('admin.docentes.destroy', $docente->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-search mr-2"></i>Nenhum docente encontrado
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
                {{ $docentes->links('pagination::bootstrap-5') }}
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

@section('js')
    <script>
        $(function () {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@stop