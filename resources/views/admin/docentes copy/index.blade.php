{{-- resources/views/admin/docentes/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Gestão de Docentes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Gestão de Docentes</h1>
        <ol class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active">Docentes</li>
        </ol>
    </div>
@endsection

@section('content')
    <!-- Mensagens de Sucesso ou Erro -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i>Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i>Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <!-- Botão para Adicionar Novo Docente -->
    <div class="mb-3">
        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i>Novo Docente
        </a>
    </div>

    <!-- Filtros -->
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.docentes.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="curso_id">Curso</label>
                            <select class="form-control" id="curso_id" name="curso_id">
                                <option value="">Todos os Cursos</option>
                                @foreach($cursos as $id => $nome)
                                    <option value="{{ $id }}"
                                        {{ request('curso_id') == $id ? 'selected' : '' }}>
                                        {{ $nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Todos os Status</option>
                                <option value="Ativo"
                                    {{ request('status') == 'Ativo' ? 'selected' : '' }}>
                                    Ativo
                                </option>
                                <option value="Inativo"
                                    {{ request('status') == 'Inativo' ? 'selected' : '' }}>
                                    Inativo
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-4">
                                <i class="fas fa-filter mr-1"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Docentes -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Lista de Docentes</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($docentes as $docente)
                        <tr>
                            <td>
                                @if ($docente->user->foto_perfil)
                                    <img src="{{ asset($docente->user->foto_perfil) }}" alt="Foto do Docente"
                                        class="img-circle img-fluid" style="max-height: 50px;">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar Padrão"
                                        class="img-circle img-fluid" style="max-height: 50px;">
                                @endif
                            </td>
                            <td>{{ $docente->user->name }}</td>
                            <td>{{ $docente->user->email }}</td>
                            <td>{{ $docente->departamento->nome ?? 'Não especificado' }}</td>
                            <td>
                                <span class="badge {{ $docente->status === 'Ativo' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $docente->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.docentes.show', $docente->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                <a href="{{ route('admin.docentes.edit', $docente->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteModal{{ $docente->id }}">
                                    <i class="fas fa-trash-alt mr-1"></i>Excluir
                                </button>
                            </td>
                        </tr>

                        <!-- Modal de Confirmação de Exclusão -->
                        <div class="modal fade" id="deleteModal{{ $docente->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel{{ $docente->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $docente->id }}">Confirmar Exclusão</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza que deseja excluir este docente?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.docentes.destroy', $docente->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum docente encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginação -->
            {{ $docentes->links() }}
        </div>
    </div>
@endsection

@section('js')
<script>
    // Função para fechar o modal ao clicar fora dele
    $(document).on('click', function(event) {
        if ($(event.target).is('.modal')) {
            $('.modal').modal('hide');
        }
    });
</script>
@endsection