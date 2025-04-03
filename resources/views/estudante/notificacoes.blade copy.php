{{-- resources/views/estudante/notificacoes.blade.php --}}
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
                            <a href="{{ route('estudante.notificacoes') }}" class="dropdown-item">Todas</a>
                            <a href="{{ route('estudante.notificacoes', ['lida' => 'false']) }}" class="dropdown-item">Não lidas</a>
                            <a href="{{ route('estudante.notificacoes', ['lida' => 'true']) }}" class="dropdown-item">Lidas</a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'academico']) }}" class="dropdown-item">Acadêmicas</a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'financeiro']) }}" class="dropdown-item">Financeiras</a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'administrativo']) }}" class="dropdown-item">Administrativas</a>
                            <a href="{{ route('estudante.notificacoes', ['tipo' => 'geral']) }}" class="dropdown-item">Gerais</a>
                        </div>
                    </div>
                    @if($notificacoes->flatten()->where('lida', false)->count() > 0)
                        <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Marcar todas como lidas">
                            <i class="fas fa-check-double"></i>
                        </button>
                    @endif
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
                            <i class="fas {{ $notificacao->icone_class }} {{ $notificacao->cor_class }}"></i>

                            <div class="timeline-item {{ $notificacao->lida ? 'opacity-75' : '' }}">
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
                                        <a href="{{ $notificacao->link }}" class="btn btn-primary btn-sm">
                                            Mais detalhes
                                        </a>
                                    @endif
                                    
                                    @if(!$notificacao->lida)
                                        <button class="btn btn-default btn-sm marcar-lida" data-id="{{ $notificacao->id }}">
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
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* ... (mesmo CSS anterior) ... */
</style>
@stop

@section('js')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Marcar notificação como lida
    $('.marcar-lida').on('click', function() {
        const notificacaoId = $(this).data('id');
        const $button = $(this);
        const $timelineItem = $button.closest('.timeline-item');
        
        $.ajax({
            url: '{{ route("estudante.notificacoes.marcar-lida", "") }}/' + notificacaoId,
            method: 'POST',
            success: function(response) {
                $timelineItem.addClass('opacity-75');
                $button.remove();
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Erro ao marcar notificação como lida');
            }
        });
    });

    // Marcar todas como lidas
    $('.card-tools .fa-check-double').parent().on('click', function() {
        $.ajax({
            url: '{{ route("estudante.notificacoes.marcar-todas-lidas") }}',
            method: 'POST',
            success: function(response) {
                $('.timeline-item').addClass('opacity-75');
                $('.marcar-lida').remove();
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Erro ao marcar notificações como lidas');
            }
        });
    });
});
</script>
@stop