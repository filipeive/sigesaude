{{-- resources/views/estudante/notificacoes.blade.php --}}
@extends('adminlte::page')

@section('title', 'Notificações')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>
            Notificações
            @if(request()->has('tipo'))
                <small class="text-muted">- {{ ucfirst(request()->tipo) }}</small>
            @endif
            @if(request()->has('lida'))
                <small class="text-muted">- {{ request()->lida === 'true' ? 'Lidas' : 'Não lidas' }}</small>
            @endif
        </h1>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-filter mr-1"></i> Filtrar por Status
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('estudante.notificacoes') }}" class="dropdown-item {{ !request()->has('lida') ? 'active' : '' }}">
                                <i class="fas fa-list mr-2"></i> Todas
                            </a>
                            <a href="{{ route('estudante.notificacoes', ['lida' => 'false']) }}" 
                               class="dropdown-item {{ request()->get('lida') === 'false' ? 'active' : '' }}">
                                <i class="far fa-envelope mr-2"></i> Não lidas
                            </a>
                            <a href="{{ route('estudante.notificacoes', ['lida' => 'true']) }}" 
                               class="dropdown-item {{ request()->get('lida') === 'true' ? 'active' : '' }}">
                                <i class="far fa-envelope-open mr-2"></i> Lidas
                            </a>
                        </div>
                    </div>

                    <div class="btn-group ml-2">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-tag mr-1"></i> Filtrar por Tipo
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('estudante.notificacoes') }}" class="dropdown-item {{ !request()->has('tipo') ? 'active' : '' }}">
                                <i class="fas fa-list mr-2"></i> Todas
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'academico']) }}" 
                               class="dropdown-item {{ request()->get('tipo') === 'academico' ? 'active' : '' }}">
                                <i class="fas fa-graduation-cap mr-2"></i> Acadêmicas
                            </a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'financeiro']) }}" 
                               class="dropdown-item {{ request()->get('tipo') === 'financeiro' ? 'active' : '' }}">
                                <i class="fas fa-dollar-sign mr-2"></i> Financeiras
                            </a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'administrativo']) }}" 
                               class="dropdown-item {{ request()->get('tipo') === 'administrativo' ? 'active' : '' }}">
                                <i class="fas fa-cog mr-2"></i> Administrativas
                            </a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'geral']) }}" 
                               class="dropdown-item {{ request()->get('tipo') === 'geral' ? 'active' : '' }}">
                                <i class="fas fa-bell mr-2"></i> Gerais
                            </a>
                        </div>
                    </div>

                    @if($notificacoes->flatten()->where('lida', false)->count() > 0)
                        <button type="button" class="btn btn-primary" id="marcar-todas-lidas">
                            <i class="fas fa-check-double mr-1"></i> Marcar todas como lidas
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <div class="timeline timeline-inverse px-3">
                    @forelse($notificacoes as $data => $grupoNotificacoes)
                        <div class="time-label">
                            <span class="bg-primary">
                                {{ Carbon\Carbon::parse($data)->format('d/m/Y') }}
                            </span>
                        </div>

                        @foreach($grupoNotificacoes as $notificacao)
                        <div>
                            <i class="fas fa-{{ $notificacao->tipo === 'academico' ? 'graduation-cap' : 
                                              ($notificacao->tipo === 'financeiro' ? 'dollar-sign' : 
                                              ($notificacao->tipo === 'administrativo' ? 'cog' : 'bell')) }} 
                               bg-{{ $notificacao->tipo === 'academico' ? 'primary' : 
                                    ($notificacao->tipo === 'financeiro' ? 'success' : 
                                    ($notificacao->tipo === 'administrativo' ? 'info' : 'secondary')) }}"></i>

                            <div class="timeline-item {{ $notificacao->lida ? 'opacity-75' : '' }}" id="notificacao-{{ $notificacao->id }}">
                                <span class="time">
                                    <i class="far fa-clock"></i> 
                                    {{ $notificacao->created_at->format('H:i') }}
                                </span>

                                <h3 class="timeline-header">
                                    @if(!$notificacao->lida)
                                        <span class="badge badge-primary">Nova</span>
                                    @endif
                                    {{ $notificacao->titulo }}
                                </h3>

                                <div class="timeline-body">
                                    {{ $notificacao->mensagem }}
                                </div>

                                <div class="timeline-footer">
                                    @if($notificacao->link)
                                        <a href="{{ $notificacao->link }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-external-link-alt mr-1"></i> Mais detalhes
                                        </a>
                                    @endif
                                    
                                    @if(!$notificacao->lida)
                                        <button class="btn btn-default btn-sm marcar-lida" 
                                                data-id="{{ $notificacao->id }}"
                                                data-url="{{ route('estudante.notificacoes.marcar-lida', $notificacao->id) }}">
                                            <i class="fas fa-check mr-1"></i> Marcar como lida
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
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.timeline-item {
    transition: opacity 0.3s ease;
}
.timeline-item.opacity-75 {
    opacity: 0.75;
}
.dropdown-item.active {
    background-color: #007bff;
    color: white;
}
</style>
@stop

@section('js')
<script>
$(function () {
    // Marcar notificação como lida
    $('.marcar-lida').on('click', function() {
        const $button = $(this);
        const $timelineItem = $button.closest('.timeline-item');
        
        $.ajax({
            url: $button.data('url'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $timelineItem.addClass('opacity-75');
                $button.fadeOut(300, function() {
                    $(this).remove();
                });
                toastr.success(response.message);
                
                // Atualizar contador de notificações no menu
                atualizarContadorNotificacoes();
            },
            error: function() {
                toastr.error('Erro ao marcar notificação como lida');
            }
        });
    });

    // Marcar todas como lidas
    $('#marcar-todas-lidas').on('click', function() {
        if (!confirm('Tem certeza que deseja marcar todas as notificações como lidas?')) {
            return;
        }

        $.ajax({
            url: '{{ route("estudante.notificacoes.marcar-todas-lidas") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.timeline-item').addClass('opacity-75');
                $('.marcar-lida').fadeOut(300, function() {
                    $(this).remove();
                });
                $('#marcar-todas-lidas').fadeOut(300);
                toastr.success(response.message);
                
                // Atualizar contador de notificações no menu
                atualizarContadorNotificacoes();
            },
            error: function() {
                toastr.error('Erro ao marcar notificações como lidas');
            }
        });
    });

    // Função para atualizar o contador de notificações no menu
    function atualizarContadorNotificacoes() {
        // Assumindo que você tem um elemento com a classe .notification-counter no seu menu
        const $counter = $('.notification-counter');
        const currentCount = parseInt($counter.text()) || 0;
        if (currentCount > 0) {
            $counter.text(currentCount - 1);
            if (currentCount - 1 <= 0) {
                $counter.hide();
            }
        }
    }
});
</script>
@stop