<div class="input-group">
    <input 
        type="number" 
        class="form-control nota-input" 
        name="notas[{{ $estudanteId }}][{{ $tipoExame }}]" 
        value="{{ $valorAtual }}" 
        min="0" 
        max="20" 
        step="0.1"
        aria-label="Nota de {{ $tipoExame }}"
        data-tipo-exame="{{ $tipoExame }}"
        data-estudante-id="{{ $estudanteId }}"
    >
    <input type="hidden" name="tipo_exame[]" value="{{ $tipoExame }}">
</div>
