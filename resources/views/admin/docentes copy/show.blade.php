{{-- resources/views/admin/docentes/show.blade.php --}}
@extends('adminlte::page')

@section('title', 'Detalhes do Docente')

@section('content_header')
    <h1>Detalhes do Docente</h1>
@stop

@section('content')
    <!-- Notificações -->
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

    <div class="row">
        <div class="col-md-3">
            <!-- Perfil -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $docente->user->foto_perfil_url ?? '/img/default-profile.png' }}"
                            alt="Foto do docente">
                    </div>
                    <h3 class="profile-username text-center">{{ $docente->user->name }}</h3>
                    <p class="text-muted text-center">{{ $docente->formacao }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Departamento</b> <a class="float-right">{{ $docente->departamento->nome }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right badge {{ $docente->status == 'Ativo' ? 'badge-success' : 'badge-danger' }}">
                                {{ $docente->status }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Anos de Experiência</b> <span class="float-right">{{ $docente->anos_experiencia }}</span>
                        </li>
                    </ul>
                    <div class="btn-group w-100">
                        <a href="{{ route('admin.docentes.edit', $docente->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-docente">
                            <i class="fas fa-trash mr-1"></i>Excluir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contatos -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Informações de Contato</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-envelope mr-1"></i> E-mail</strong>
                    <p class="text-muted">{{ $docente->user->email }}</p>
                    <hr>
                    <strong><i class="fas fa-phone mr-1"></i> Telefone</strong>
                    <p class="text-muted">{{ $docente->user->telefone }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Cursos -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cursos Associados</h3>
                </div>
                <div class="card-body">
                    @if($docente->cursos->count() > 0)
                        <div class="row">
                            @foreach($docente->cursos as $curso)
                                <div class="col-md-4">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-graduation-cap"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $curso->nome }}</span>
                                            <span class="info-box-number">{{ $curso->disciplinas->count() }} disciplinas</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="icon fas fa-exclamation-triangle"></i> Este docente não está associado a nenhum curso.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Disciplinas -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Disciplinas Lecionadas</h3>
                </div>
                <div class="card-body">
                    @if($docente->disciplinas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Disciplina</th>
                                        <th>Curso</th>
                                        <th>Carga Horária</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($docente->disciplinas as $disciplina)
                                        <tr>
                                            <td>{{ $disciplina->nome }}</td>
                                            <td>{{ $disciplina->curso->nome }}</td>
                                            <td>{{ $disciplina->carga_horaria }} horas</td>
                                            <td>
                                                <span class="badge {{ $disciplina->status == 'Ativo' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $disciplina->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="icon fas fa-exclamation-triangle"></i> Este docente não leciona nenhuma disciplina.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Exclusão -->
    <div class="modal fade" id="modal-delete-docente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Confirmar Exclusão</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir o docente <strong>{{ $docente->user->name }}</strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Esta ação não pode ser desfeita!</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.docentes.destroy', $docente->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inicializa tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop