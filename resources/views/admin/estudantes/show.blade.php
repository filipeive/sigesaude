{{-- resources/views/admin/estudantes/show.blade.php --}}
@extends('adminlte::page')

@section('title', 'Detalhes do Estudante')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detalhes do Estudante</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-sm-right">
                <a href="{{ route('admin.estudantes.edit', $estudante->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <form action="{{ route('admin.estudantes.destroy', $estudante->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir este estudante?')">
                        <i class="fas fa-trash mr-1"></i> Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <!-- Notificações -->
    {{--  @include('admin.partials.notifications') --}}
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
            <!-- Perfil do Estudante -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $estudante->user->foto_perfil_url ?? '/img/default-profile.png' }}"
                            alt="Foto de perfil do estudante">
                    </div>
                    <h3 class="profile-username text-center">{{ $estudante->user->name }}</h3>
                    <p class="text-muted text-center">Matrícula: {{ $estudante->matricula }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Status</b>
                            <span class="float-right">
                                @switch($estudante->status)
                                    @case('Ativo')
                                        <span class="badge badge-success">Ativo</span>
                                    @break

                                    @case('Inativo')
                                        <span class="badge badge-danger">Inativo</span>
                                    @break

                                    @case('Concluído')
                                        <span class="badge badge-info">Concluído</span>
                                    @break

                                    @case('Desistente')
                                        <span class="badge badge-warning">Desistente</span>
                                    @break
                                @endswitch
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Curso</b> <span class="float-right">{{ $estudante->curso->nome }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Turno</b> <span class="float-right">{{ $estudante->turno }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#personal" data-toggle="tab">
                                <i class="fas fa-user mr-1"></i> Informações Pessoais
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#academic" data-toggle="tab">
                                <i class="fas fa-graduation-cap mr-1"></i> Informações Acadêmicas
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <!-- Informações Pessoais -->
                        <div class="active tab-pane" id="personal">
                            <dl class="row">
                                <dt class="col-sm-4">Nome Completo</dt>
                                <dd class="col-sm-8">{{ $estudante->user->name }}</dd>

                                <dt class="col-sm-4">E-mail</dt>
                                <dd class="col-sm-8">{{ $estudante->user->email }}</dd>

                                <dt class="col-sm-4">Telefone</dt>
                                <dd class="col-sm-8">{{ $estudante->user->telefone }}</dd>

                                <dt class="col-sm-4">Data de Nascimento</dt>
                                <dd class="col-sm-8">{{ date('d/m/Y', strtotime($estudante->data_nascimento)) }}</dd>

                                <dt class="col-sm-4">Gênero</dt>
                                <dd class="col-sm-8">{{ $estudante->genero }}</dd>

                                <dt class="col-sm-4">Contato de Emergência</dt>
                                <dd class="col-sm-8">{{ $estudante->contato_emergencia }}</dd>
                            </dl>
                        </div>

                        <!-- Informações Acadêmicas -->
                        <div class="tab-pane" id="academic">
                            <dl class="row">
                                <dt class="col-sm-4">Matrícula</dt>
                                <dd class="col-sm-8">{{ $estudante->matricula }}</dd>

                                <dt class="col-sm-4">Curso</dt>
                                <dd class="col-sm-8">{{ $estudante->curso->nome }}</dd>

                                <dt class="col-sm-4">Ano de Ingresso</dt>
                                <dd class="col-sm-8">{{ $estudante->ano_ingresso }}</dd>

                                <dt class="col-sm-4">Ano Letivo</dt>
                                <dd class="col-sm-8">{{ $estudante->anoLectivo->ano }}</dd>

                                <dt class="col-sm-4">Turno</dt>
                                <dd class="col-sm-8">{{ $estudante->turno }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    @switch($estudante->status)
                                        @case('Ativo')
                                            <span class="badge badge-success">Ativo</span>
                                        @break

                                        @case('Inativo')
                                            <span class="badge badge-danger">Inativo</span>
                                        @break

                                        @case('Concluído')
                                            <span class="badge badge-info">Concluído</span>
                                        @break

                                        @case('Desistente')
                                            <span class="badge badge-warning">Desistente</span>
                                        @break
                                    @endswitch
                                </dd>
                            </dl>
                        </div>
                    </div>
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
            // Mantém a aba ativa após recarregar a página
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('lastTab', $(e.target).attr('href'));
            });

            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
                $('a[href="' + lastTab + '"]').tab('show');
            }
        });
    </script>
@stop
