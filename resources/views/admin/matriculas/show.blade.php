@extends('adminlte::page')

@section('title', 'Detalhes da Matrícula')

@section('content_header')
    <h1><i class="fas fa-info-circle"></i> Detalhes da Matrícula</h1>
@stop

@section('content')
    <!-- Notificações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <!-- Perfil do Estudante -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $matricula->estudante->user->avatar ?? '/img/default-profile.png' }}"
                            alt="Foto do estudante">
                    </div>
                    <h3 class="profile-username text-center">{{ $matricula->estudante->user->name ?? 'N/A' }}</h3>
                    <p class="text-muted text-center">{{ $matricula->estudante->curso->nome ?? 'N/A' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Número de Estudante</b> <a class="float-right">{{ $matricula->estudante->numero ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $matricula->estudante->user->email ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Telefone</b> <a class="float-right">{{ $matricula->estudante->user->telefone ?? 'N/A' }}</a>
                        </li>
                    </ul>
                    @if($matricula->estudante)
                        <a href="{{ route('admin.estudantes.show', $matricula->estudante->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-graduate mr-1"></i> Ver Perfil Completo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Detalhes da Matrícula -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detalhes da Matrícula</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> Disciplina</strong>
                    <p class="text-muted">{{ $matricula->disciplina->nome ?? 'N/A' }}</p>
                    <hr>
                    <strong><i class="fas fa-calendar-alt mr-1"></i> Data de Matrícula</strong>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($matricula->created_at)->format('d/m/Y') }}</p>
                    <hr>
                    <strong><i class="fas fa-clipboard-check mr-1"></i> Status</strong>
                    <p class="text-muted">
                        <span class="badge badge-success">Ativa</span>
                    </p>
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