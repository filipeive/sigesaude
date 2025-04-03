@if ($estudante && $estudante->mediaFinal)
    <span class="badge {{ $estudante->mediaFinal->status == 'Aprovado' ? 'badge-success' : 'badge-danger' }}">
        {{ $estudante->mediaFinal->status }}
    </span>
@else
    <span class="badge badge-warning">Pendente</span>
@endif
