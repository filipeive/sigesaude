{{-- resources/views/admin/users/show.blade.php --}}
@extends('adminlte::page')
@section('title', 'Detalhes do Usuário')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">Detalhes do Usuário</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">Detalhes do Usuário</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-1"></i>Editar Usuário
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->foto_perfil)
                            <img class="profile-user-img img-fluid img-circle" 
                                 src="{{ asset($user->foto_perfil) }}" 
                                 alt="Foto de perfil">
                        @else
                            <div class="profile-user-img img-fluid img-circle bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">
                        <span class="badge badge-{{ 
                            $user->tipo == 'admin' ? 'danger' : 
                            ($user->tipo == 'docente' ? 'success' : 
                            ($user->tipo == 'estudante' ? 'primary' : 
                            ($user->tipo == 'financeiro' ? 'warning' : 'info'))) 
                        }}">
                            {{ ucfirst($user->tipo) }}
                        </span>
                    </p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope mr-1"></i>Email</b>
                            <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-phone mr-1"></i>Telefone</b>
                            <a class="float-right">{{ $user->telefone ?? 'Não informado' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-venus-mars mr-1"></i>Gênero</b>
                            <a class="float-right">{{ $user->genero ?? 'Não informado' }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informações Adicionais</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-calendar mr-1"></i>Criado em</strong>
                    <p class="text-muted">
                        {{ $user->created_at->format('d/m/Y H:i:s') }}
                    </p>
                    <hr>
                    <strong><i class="fas fa-clock mr-1"></i>Última atualização</strong>
                    <p class="text-muted">
                        {{ $user->updated_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#activity" data-toggle="tab">
                                <i class="fas fa-history mr-1"></i>Atividade
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#timeline" data-toggle="tab">
                                <i class="fas fa-stream mr-1"></i>Timeline
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="activity">
                            <div class="text-center text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Histórico de atividades será implementado em breve
                            </div>
                        </div>
                        <div class="tab-pane" id="timeline">
                            <div class="text-center text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Timeline será implementada em breve
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection