<h1 class="text-primary">
    @if($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $title }}
    @if($subtitle)
        <span class="text-muted">- {{ $subtitle }}</span>
    @endif
</h1>
