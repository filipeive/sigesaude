{{-- resources/views/components/disciplina-card.blade.php --}}
@props([
    'disciplina', 
    'route', 
    'buttonText' => 'Ver Detalhes', 
    'buttonIcon' => 'fas fa-eye',
    'statusLancamento' => null
])

<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm card-disciplina">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-primary">{{ $disciplina->nome }}</h5>
            @if($statusLancamento)
                <x-status-badge :status="$statusLancamento" />
            @endif
        </div>
        <div class="card-body">
            <div class="mb-3">
                <p class="card-text">
                    <strong><i class="fas fa-graduation-cap mr-2"></i>Curso:</strong> {{ $disciplina->curso->nome }}<br>
                    <strong><i class="fas fa-layer-group mr-2"></i>Nível:</strong> {{ $disciplina->nivel->nome }}
                </p>
                
                <div class="progress mb-2" style="height: 5px;">
                    @php 
                        $progressPercent = isset($disciplina->progresso_lancamento) ? $disciplina->progresso_lancamento : 0;
                        $progressColor = $progressPercent < 30 ? 'danger' : ($progressPercent < 70 ? 'warning' : 'success');
                    @endphp
                    <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" 
                         style="width: {{ $progressPercent }}%" 
                         aria-valuenow="{{ $progressPercent }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
                
                <small class="text-muted">
                    <i class="fas fa-users mr-1"></i> {{ $disciplina->estudantes_count ?? 'N/A' }} estudantes
                </small>
            </div>
            <a href="{{ $route }}" class="btn btn-primary btn-block">
                <i class="{{ $buttonIcon }} mr-2"></i> {{ $buttonText }}
            </a>
        </div>
    </div>
</div>