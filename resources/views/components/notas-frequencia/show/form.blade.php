{{-- resources/views/components/notas-frequencia/show/form.blade.php --}}
<form action="{{ route('docente.notas_frequencia.salvar') }}" method="POST" id="form-notas-frequencia">
    @csrf
    <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
    <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivoAtual->id ?? 1 }}">

    <div class="card-body">
        @if(count($estudantes) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabela-notas">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">Nome</th>
                            <th colspan="2" class="text-center">Testes</th>
                            <th colspan="2" class="text-center">Trabalhos</th>
                            <th rowspan="2" class="text-center align-middle">Média Final</th>
                            <th rowspan="2" class="text-center align-middle">Status</th>
                        </tr>
                        <tr>
                            <th class="text-center">Teste 1</th>
                            <th class="text-center">Teste 2</th>
                            <th class="text-center">Trabalho 1</th>
                            <th class="text-center">Trabalho 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudantes as $estudante)
                            <tr>
                                <td>
                                    {{ $estudante['estudante']->user->name }}
                                    <input type="hidden" name="estudante_id[]" value="{{ $estudante['estudante']->id }}">
                                </td>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        class="form-control nota-input" 
                                        name="notas[{{ $estudante['estudante']->id }}][Teste 1]" 
                                        value="{{ optional($estudante['notas_detalhadas']->where('tipo', 'Teste 1')->first())->nota ?? '' }}" 
                                        min="0" 
                                        max="20" 
                                        step="0.1"
                                    >
                                </td>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        class="form-control nota-input" 
                                        name="notas[{{ $estudante['estudante']->id }}][Teste 2]" 
                                        value="{{ optional($estudante['notas_detalhadas']->where('tipo', 'Teste 2')->first())->nota ?? '' }}" 
                                        min="0" 
                                        max="20" 
                                        step="0.1"
                                    >
                                </td>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        class="form-control nota-input" 
                                        name="notas[{{ $estudante['estudante']->id }}][Trabalho 1]" 
                                        value="{{ optional($estudante['notas_detalhadas']->where('tipo', 'Trabalho 1')->first())->nota ?? '' }}" 
                                        min="0" 
                                        max="20" 
                                        step="0.1"
                                    >
                                </td>
                                
                                <td>
                                    <input 
                                        type="number" 
                                        class="form-control nota-input" 
                                        name="notas[{{ $estudante['estudante']->id }}][Trabalho 2]" 
                                        value="{{ optional($estudante['notas_detalhadas']->where('tipo', 'Trabalho 2')->first())->nota ?? '' }}" 
                                        min="0" 
                                        max="20" 
                                        step="0.1"
                                    >
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ optional($estudante['notas_frequencia'])->nota >= 10 ? 'badge-success' : 'badge-danger' }}">
                                        {{ optional($estudante['notas_frequencia'])->nota ?? '-' }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <span class="badge {{ optional($estudante['notas_frequencia'])->status == 'Admitido' ? 'badge-success' : 'badge-danger' }}">
                                        {{ optional($estudante['notas_frequencia'])->status ?? 'Pendente' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                Nenhum estudante encontrado matriculado nesta disciplina.
            </div>
        @endif
    </div>

    <div class="card-footer">
        @if(count($estudantes) > 0)
            <button type="submit" class="btn btn-primary" id="btn-salvar-notas">
                <i class="fas fa-save"></i> Salvar Notas
            </button>
            <button type="button" class="btn btn-info" id="btn-calcular-preview">
                <i class="fas fa-calculator"></i> Pré-visualizar Cálculos
            </button>
        @endif
    </div>
</form>
