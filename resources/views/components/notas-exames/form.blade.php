<form action="{{ route('docente.notas_exames.salvar') }}" method="POST" id="form-notas-exames">
    @csrf
    <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
    <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivoAtual->id ?? 1 }}">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tabela-notas">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="text-center">Nota Frequência</th>
                        <th class="text-center">Exame Normal</th>
                        <th class="text-center">Exame Recorrência</th>
                        <th class="text-center">Média Final</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($estudantes as $estudante)
                        <x-notas-exames.linha-estudante :estudante="$estudante" />
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum estudante encontrado para esta disciplina.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary" id="btn-salvar-notas">
            <i class="fas fa-save"></i> Salvar Notas
        </button>
    </div>
</form>
