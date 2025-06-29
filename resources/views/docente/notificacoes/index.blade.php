@extends('adminlte::page')

@section('title', 'Notificações')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-bell text-primary"></i> Notificações
            @if($naoLidas > 0)
                <span class="badge badge-primary">{{ $naoLidas }}</span>
            @endif
        </h1>
        <div>
            <a href="{{ route('docente.notificacoes.create') }}" class="btn btn-primary">
                <i class="fas fa-paper-plane mr-1"></i> Nova Notificação
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalNotificacoes }}</h3>
                    <p>Total de Notificações</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $naoLidas }}</h3>
                    <p>Não Lidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $notificacoesEnviadas }}</h3>
                    <p>Enviadas por Você</p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $notificacoesHoje }}</h3>
                    <p>Hoje</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Abas de Navegação -->
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="notificacoesTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="recebidas-tab" data-toggle="pill" href="#recebidas" role="tab">
                        <i class="fas fa-inbox mr-1"></i> Recebidas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="enviadas-tab" data-toggle="pill" href="#enviadas" role="tab">
                        <i class="fas fa-paper-plane mr-1"></i> Enviadas
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="notificacoesTabsContent">
                <div class="tab-pane fade show active" id="recebidas" role="tabpanel">
                    <!-- Filtros -->
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Filtros</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('docente.notificacoes.partials.filtros')
                        </div>
                    </div>

                    <!-- Lista de Notificações -->
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Notificações Recebidas</h3>
                            <div class="card-tools">
                                @if($naoLidas > 0)
                                    <button type="button" class="btn btn-primary btn-sm" onclick="marcarTodasComoLidas()">
                                        <i class="fas fa-check-double"></i> Marcar todas como lidas
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="listaNotificacoes">
                                @include('docente.notificacoes.partials.lista')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="enviadas" role="tabpanel">
                    <!-- Lista de Notificações Enviadas -->
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Notificações Enviadas</h3>
                        </div>
                        <div class="card-body p-0">
                            @include('docente.notificacoes.partials.lista-enviadas')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop