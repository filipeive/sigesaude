{{-- resources/views/components/empty-state.blade.php --}}
@props(['icon' => 'fas fa-info-circle', 'message' => 'Não há dados disponíveis'])

<div class="text-center py-5">
    <i class="{{ $icon }}" style="font-size: 64px; color: #d0d0d0;"></i>
    <h4 class="mt-3 text-muted">{{ $message }}</h4>
    {{ $slot }}
</div>