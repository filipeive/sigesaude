@forelse($notificacoes as $notificacao)
    <div class="notification-item list-group-item {{ !$notificacao->lida ? 'unread' : '' }}">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <h5 class="mb-1">
                <i class="fas {{ $notificacao->iconeClass }} text-{{ $notificacao->tipo }} mr-2"></i>
                {{ $notificacao->titulo }}
            </h5>
            <small class="notification-time">{{ $notificacao->tempoDecorrido }}</small>
        </div>
        <p class="mb-1">{{ $notificacao->mensagem }}</p>
        <div class="mt-2">
            @if(!$notificacao->lida)
                <button type="button" 
                        class="btn btn-sm btn-outline-primary"
                        onclick="marcarComoLida({{ $notificacao->id }})">
                    <i class="fas fa-check"></i> Marcar como lida
                </button>
            @endif
            @if($notificacao->link)
                <a href="{{ $notificacao->link }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-external-link-alt"></i> Ver detalhes
                </a>
            @endif
            <button type="button" 
                    class="btn btn-sm btn-outline-danger"
                    onclick="excluirNotificacao({{ $notificacao->id }})">
                <i class="fas fa-trash"></i> Excluir
            </button>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-3x text-muted"></i>
        <p class="mt-3">Nenhuma notificação encontrada.</p>
    </div>
@endforelse

@if($notificacoes->hasPages())
    <div class="px-3 py-2">
        {{ $notificacoes->links(
            'pagination::bootstrap-5'  // Use Bootstrap 5 pagination
        ) }}
    </div>
@endif