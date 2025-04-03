<tr>
    <td>
        @if ($estudante['estudante'] && $estudante['estudante']->user)
            {{ $estudante['estudante']->user->name }}
            <input type="hidden" name="estudante_id[]" value="{{ $estudante['estudante']->id }}">
        @else
            <span class="text-muted">Nome não disponível</span>
        @endif
    </td>

    <td class="text-center">
        {{ number_format($estudante['nota_frequencia'] ?? 0, 1) }}
    </td>
    
    <td class="text-center">
        <div class="input-group">
            <input 
                type="number" 
                class="form-control nota-input" 
                name="notas[{{ $estudante['estudante']->id }}][Normal]" 
                value="{{ $estudante['nota_exame_normal'] }}" 
                min="0" 
                max="20" 
                step="0.1"
                aria-label="Nota de Exame Normal"
                data-tipo-exame="Normal"
                data-estudante-id="{{ $estudante['estudante']->id }}"
            >
        </div>
    </td>
    
    <td class="text-center">
        <div class="input-group">
            <input 
                type="number" 
                class="form-control nota-input" 
                name="notas[{{ $estudante['estudante']->id }}][Recorrência]" 
                value="{{ $estudante['nota_exame_recorrencia'] }}" 
                min="0" 
                max="20" 
                step="0.1"
                aria-label="Nota de Exame Recorrência"
                data-tipo-exame="Recorrência"
                data-estudante-id="{{ $estudante['estudante']->id }}"
            >
        </div>
    </td>
    
    <td class="text-center">
        {{ number_format($estudante['media_final'] ?? 0, 1) }}
    </td>
    
    <td class="text-center">
        @if ($estudante['status'] == 'Aprovado')
            <span class="badge bg-success">Aprovado</span>
        @elseif ($estudante['status'] == 'Reprovado')
            <span class="badge bg-danger">Reprovado</span>
        @else
            <span class="badge bg-warning">Pendente</span>
        @endif
    </td>
</tr>