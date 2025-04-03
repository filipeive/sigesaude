@extends('adminlte::page')

@section('title', 'Notificações')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Notificações</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('estudante.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Notificações</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell mr-1"></i>
                    Todas as Notificações
                </h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item">Todas</a>
                            <a href="#" class="dropdown-item">Não lidas</a>
                            <a href="#" class="dropdown-item">Lidas</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Acadêmicas</a>
                            <a href="#" class="dropdown-item">Financeiras</a>
                            <a href="#" class="dropdown-item">Administrativas</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Marcar todas como lidas">
                        <i class="fas fa-check-double"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="timeline timeline-inverse px-3">
                    @forelse($notificacoes as $data => $grupoNotificacoes)
                        <div class="time-label">
                            <span class="bg-primary">
                                {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                            </span>
                        </div>

                        @foreach($grupoNotificacoes as $notificacao)
                        <div>
                            <!-- Ícone baseado no tipo de notificação -->
                            <i class="fas {{ $notificacao['icone'] }} {{ $notificacao['cor'] }}"></i>

                            <div class="timeline-item {{ $notificacao['lida'] ? 'opacity-75' : '' }}">
                                <span class="time">
                                    <i class="far fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($notificacao['data'])->format('H:i') }}
                                </span>

                                <h3 class="timeline-header">
                                    @if(!$notificacao['lida'])
                                        <span class="badge badge-primary">Nova</span>
                                    @endif
                                    {{ $notificacao['titulo'] }}
                                </h3>

                                <div class="timeline-body">
                                    {{ $notificacao['mensagem'] }}
                                </div>

                                <div class="timeline-footer">
                                    @if(isset($notificacao['link']))
                                        <a href="{{ $notificacao['link'] }}" class="btn btn-primary btn-sm">
                                            Mais detalhes
                                        </a>
                                    @endif
                                    
                                    @if(!$notificacao['lida'])
                                        <button class="btn btn-default btn-sm marcar-lida" data-id="{{ $notificacao['id'] }}">
                                            Marcar como lida
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhuma notificação encontrada.</p>
                        </div>
                    @endforelse

                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>

            @if($notificacoes->count() > 0)
                <div class="card-footer text-center">
                    {{ $notificacoes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .timeline-item.opacity-75 {
        opacity: 0.75;
    }
    .timeline {
        margin: 0;
        padding: 0;
        position: relative;
    }
    .timeline::before {
        background: #dee2e6;
        bottom: 0;
        content: '';
        left: 31px;
        margin: 0;
        position: absolute;
        top: 0;
        width: 4px;
    }
    .timeline > div {
        margin-bottom: 15px;
        margin-right: 10px;
        position: relative;
    }
    .timeline > div > .timeline-item {
        margin-left: 60px;
        margin-right: 15px;
        margin-top: 0;
        padding: 0;
        position: relative;
    }
    .time-label {
        border-radius: 4px;
        margin-bottom: 15px;
        margin-left: 60px;
    }
    .time-label > span {
        border-radius: 4px;
        display: inline-block;
        padding: 5px 10px;
    }
    .timeline-item {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
        background: #fff;
        padding: 15px;
        position: relative;
    }
</style>
@stop

@section('js')
<script>
$(function () {
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Marcar notificação como lida
    $('.marcar-lida').on('click', function() {
        const notificacaoId = $(this).data('id');
        const $button = $(this);
        const $timelineItem = $button.closest('.timeline-item');
        
        // Aqui você adicionaria a chamada AJAX para marcar como lida no backend
        $.ajax({
            url: '/estudante/notificacoes/' + notificacaoId + '/marcar-lida',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $timelineItem.addClass('opacity-75');
                $button.remove();
                toastr.success('Notificação marcada como lida');
            },
            error: function() {
                toastr.error('Erro ao marcar notificação como lida');
            }
        });
    });

    // Marcar todas como lidas
    $('.card-tools .fa-check-double').parent().on('click', function() {
        // Aqui você adicionaria a chamada AJAX para marcar todas como lidas
        $.ajax({
            url: '/estudante/notificacoes/marcar-todas-lidas',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.timeline-item').addClass('opacity-75');
                $('.marcar-lida').remove();
                toastr.success('Todas as notificações foram marcadas como lidas');
            },
            error: function() {
                toastr.error('Erro ao marcar notificações como lidas');
            }
        });
    });

    // Filtrar notificações
    $('.dropdown-item').on('click', function(e) {
        e.preventDefault();
        const filtro = $(this).text().toLowerCase();
        // Aqui você implementaria a lógica de filtro
        // Pode ser via AJAX ou manipulação do DOM
    });
});
</script>
@stop