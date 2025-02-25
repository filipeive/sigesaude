@extends('adminlte::page')

@section('title', 'Gestão de Docentes')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-chalkboard-teacher"></i> Gestão de Docentes</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Docentes</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Lista de Docentes</h3>
                <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Novo Docente
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-outline card-info collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Filtros</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <form action="{{ route('admin.docentes.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="name" class="form-control" placeholder="Nome do docente" value="{{ request('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Departamento</label>
                                            <select name="departamento_id" class="form-control select2">
                                                <option value="">Todos</option>
                                                @foreach($departamentos ?? [] as $id => $nome)
                                                    <option value="{{ $id }}" {{ request('departamento_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Todos</option>
                                                <option value="Ativo" {{ request('status') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="Inativo" {{ request('status') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Curso</label>
                                            <select name="curso_id" class="form-control select2">
                                                <option value="">Todos</option>
                                                @foreach($cursos ?? [] as $id => $nome)
                                                    <option value="{{ $id }}" {{ request('curso_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fas fa-search"></i> Filtrar
                                            </button>
                                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-eraser"></i> Limpar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Departamento</th>
                            <th>Formação</th>
                            <th>Status</th>
                            <th width="150">Ações</th>
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
                                <td>
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
                                <td colspan="9" class="text-center">Nenhum docente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $docentes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endsection