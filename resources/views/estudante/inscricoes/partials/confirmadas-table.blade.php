<!-- resources/views/estudante/inscricoes/partials/confirmadas-table.blade.php -->
@if($inscricoesConfirmadas->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-check-circle fa-4x text-muted mb-3"></i>
        <h3 class="mb-3">Nenhuma Inscrição Confirmada</h3>
        <p class="text-muted mb-4">Você ainda não possui inscrições confirmadas.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Ano Lectivo</th>
                    <th>Semestre</th>
                    <th>Data</th>
                    <th>Referência</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscricoesConfirmadas as $inscricao)
                    <tr>
                        <td>{{ $inscricao->anoLectivo->ano }}</td>
                        <td>{{ $inscricao->semestre }}</td>
                        <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                        <td>
                            <span class="text-monospace">
                                {{ $inscricao->referencia ?? '---' }}
                            </span>
                        </td>
                        <td>
                            <span class="font-weight-bold">
                                {{ $inscricao->valor ? number_format($inscricao->valor, 2) . ' MT' : '---' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-success">Confirmada</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('estudante.inscricoes.show', $inscricao->id) }}" 
                               class="btn btn-sm btn-info" 
                               data-toggle="tooltip" 
                               title="Ver Detalhes">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif