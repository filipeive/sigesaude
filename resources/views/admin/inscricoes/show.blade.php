@extends('adminlte::page')

@section('title', 'Detalhes da Inscrição')

@section('content_header')
    <h1><i class="fas fa-user-circle mr-2"></i>Detalhes da Inscrição</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.inscricoes.index') }}">Inscrições Semestrais</a></li>
        <li class="breadcrumb-item active">#{{ $inscricao->id }}</li>
    </ol>
@stop

@section('content')
    <div class="card card-outline card-{{ $inscricao->status == 'Confirmada' ? 'success' : ($inscricao->status == 'Pendente' ? 'warning' : 'danger') }}">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-1"></i>
                Inscrição #{{ $inscricao->id }} —
                <span class="badge {{ $inscricao->status == 'Confirmada' ? 'badge-success' : ($inscricao->status == 'Pendente' ? 'badge-warning' : 'badge-danger') }}">
                    {{ $inscricao->status }}
                </span>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5><strong><i class="fas fa-user mr-1"></i> Estudante:</strong> {{ $inscricao->estudante->user->name }}</h5>
                    <h5><strong><i class="fas fa-users mr-1"></i> Turma:</strong> {{ $inscricao->estudante->turma?->classe?->nome ?? 'N/A' }} ({{ $inscricao->estudante->turma?->nome ?? 'N/T' }})</h5>
                    <h5><strong><i class="fas fa-calendar-alt mr-1"></i> Ano Lectivo:</strong> {{ $inscricao->anoLectivo->ano }}</h5>
                    <h5><strong><i class="fas fa-calendar-week mr-1"></i> Semestre:</strong> {{ $inscricao->semestre }}º</h5>
                    <h5><strong><i class="fas fa-clock mr-1"></i> Data:</strong> {{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y H:i') }}</h5>
                    @if($inscricao->referencia)
                        <h5><strong><i class="fas fa-hashtag mr-1"></i> Referência ATM:</strong> <code>{{ $inscricao->referencia }}</code></h5>
                    @endif
                    @if($inscricao->observacoes)
                        <h5><strong><i class="fas fa-sticky-note mr-1"></i> Observações:</strong></h5>
                        <p class="text-muted">{{ $inscricao->observacoes }}</p>
                    @endif
                </div>

                <div class="col-md-6">
                    <h5><strong><i class="fas fa-file-invoice mr-1"></i> Pagamentos associados ({{ $inscricao->anoLectivo->ano }}):</strong></h5>
                    @php
                        $pagamentos = \App\Models\Pagamento::where('estudante_id', $inscricao->estudante_id)
                            ->where('ano_lectivo_id', $inscricao->ano_lectivo_id)
                            ->orderBy('data_vencimento', 'asc')
                            ->get();
                    @endphp
                    @if($pagamentos->count() > 0)
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Referência</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagamentos as $pag)
                                    <tr>
                                        <td>{{ $pag->descricao }}</td>
                                        <td><code>{{ $pag->referencia }}</code></td>
                                        <td>{{ number_format($pag->valor, 2, ',', '.') }} MZN</td>
                                        <td>
                                            <span class="badge badge-{{ $pag->status == 'pago' ? 'success' : 'warning' }}">
                                                {{ ucfirst($pag->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            Nenhum pagamento registado para este ano lectivo.
                        </p>
                    @endif
                    <a href="{{ route('admin.estudantes.show', $inscricao->estudante_id) }}" class="btn btn-sm btn-info mt-2">
                        <i class="fas fa-user-graduate mr-1"></i> Ver Perfil do Estudante
                    </a>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Voltar
                </a>
                <div>
                    <a href="{{ route('admin.inscricoes.edit', $inscricao->id) }}" class="btn btn-warning ml-1">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    @if($inscricao->status == 'Pendente')
                        <form action="{{ route('admin.inscricoes.aprovar', $inscricao->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-success ml-1" onclick="return confirm('Aprovar esta inscrição?')">
                                <i class="fas fa-check mr-1"></i> Aprovar
                            </button>
                        </form>
                        <form action="{{ route('admin.inscricoes.recusar', $inscricao->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-danger ml-1" onclick="return confirm('Recusar esta inscrição?')">
                                <i class="fas fa-times mr-1"></i> Recusar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
