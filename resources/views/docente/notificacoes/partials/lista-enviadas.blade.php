@forelse($notificacoesEnviadasLista as $notificacao)
    <div class="notification-item list-group-item">
        <div class="d-flex w-100 justify-content-between align-items-center">
            <h5 class="mb-1">
                <i class="fas {{ $notificacao->iconeClass }} text-{{ $notificacao->tipo }} mr-2"></i>
                {{ $notificacao->titulo }}
            </h5>
            <small class="notification-time">
                {{ $notificacao->created_at->format('d/m/Y H:i') }}
            </small>
        </div>
        <p class="mb-1">{{ $notificacao->mensagem }}</p>
        <div class="mt-2">
            <span class="badge badge-info">
                <i class="fas fa-users mr-1"></i>
                {{ $notificacao->destinatarios_count }} destinatários
            </span>
            @if($notificacao->lidas_count)
                <span class="badge badge-success">
                    <i class="fas fa-check mr-1"></i>
                    {{ $notificacao->lidas_count }} lidas
                </span>
            @endif
            @if($notificacao->link)
                <a href="{{ $notificacao->link }}" class="badge badge-primary" target="_blank">
                    <i class="fas fa-external-link-alt mr-1"></i>
                    Ver link
                </a>
            @endif
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-paper-plane fa-3x text-muted"></i>
        <p class="mt-3">Você ainda não enviou nenhuma notificação.</p>
        <a href="{{ route('docente.notificacoes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Enviar Nova Notificação
        </a>
    </div>
@endforelse

@if($notificacoesEnviadasLista->hasPages())
    <div class="px-3 py-2">
        {{ $notificacoesEnviadasLista->links(
            'pagination::bootstrap-5'  // Use Bootstrap 5 pagination
        ) }}
    </div>
@endif