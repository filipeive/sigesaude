<!-- resources/views/estudante/inscricoes/partials/pendentes-table.blade.php -->
@if($inscricoesPendentes->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-info-circle fa-4x text-muted mb-3"></i>
        <h3 class="mb-3">Nenhuma Inscrição Pendente</h3>
        <p class="text-muted mb-4">Você não possui inscrições pendentes no momento.</p>
        <a href="{{ route('estudante.inscricoes.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle mr-2"></i> Criar Nova Inscrição
        </a>
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
                @foreach($inscricoesPendentes as $inscricao)
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
                            <span class="badge badge-warning">Pendente</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('estudante.inscricoes.show', $inscricao->id) }}" 
                                   class="btn btn-sm btn-info" 
                                   data-toggle="tooltip" 
                                   title="Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('estudante.inscricoes.cancelar', $inscricao->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger btn-cancel-inscricao" 
                                            data-toggle="tooltip" 
                                            title="Cancelar Inscrição">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif