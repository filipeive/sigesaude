@extends('adminlte::page')

@section('title', 'Matrículas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Minhas Matrículas e Classe</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('estudante.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Matrículas</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Informações da Turma Atual -->
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-users-class fa-4x text-primary"></i>
                    </div>
                </div>
                <h3 class="profile-username text-center">{{ $estudante->turma->nome ?? 'Sem Turma' }}</h3>
                <p class="text-muted text-center">{{ $estudante->turma->ano_serie ?? 'N/A' }}º Ano / Classe</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Ano Lectivo</b> <a class="float-right text-primary font-weight-bold">{{ $estudante->anoLectivo->ano ?? date('Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Turno</b> <a class="float-right">{{ $estudante->turno ?? 'N/A' }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Nº Matrícula</b> <a class="float-right font-italic">{{ $estudante->matricula }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-book mr-1"></i> Disciplinas da Classe</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @php
                        $disciplinas = [];
                        if ($estudante->turma && $estudante->turma->classe_id) {
                            $disciplinas = \App\Models\Disciplina::where('classe_id', $estudante->turma->classe_id)->get();
                        }
                    @endphp
                    @forelse($disciplinas as $disciplina)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $disciplina->nome }}
                            <span class="badge badge-info badge-pill">Obrigatória</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Nenhuma disciplina cadastrada para esta turma.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Histórico de Matrículas e Pagamentos -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Histórico de Matrículas</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Ano Lectivo</th>
                                <th>Classe/Turma</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matriculas as $matricula)
                                <tr>
                                    <td>{{ $matricula->anoLectivo->ano ?? 'N/A' }}</td>
                                    <td>{{ $matricula->turma->nome ?? 'N/A' }}</td>
                                    <td>{{ number_format($matricula->valor, 2, ',', '.') }} MZN</td>
                                    <td>
                                        <span class="badge @if($matricula->status == 'Ativo') badge-success @elseif($matricula->status == 'Pendente') badge-warning @else badge-secondary @endif">
                                            {{ $matricula->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($matricula->status == 'Pendente')
                                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#pagarMatricula{{ $matricula->id }}">
                                                <i class="fas fa-money-bill-wave mr-1"></i> Pagar
                                            </button>
                                        @endif
                                        <button class="btn btn-xs btn-info">
                                            <i class="fas fa-file-invoice mr-1"></i> Recibo
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Pagamento -->
                                <div class="modal fade" id="pagarMatricula{{ $matricula->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Instruções de Pagamento - Matrícula</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="text-center mb-4">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/4b/SIMO_logo.png" alt="SIMO ATM" style="height: 40px;">
                                                    <h6 class="mt-2 text-muted">Pagamento via ATM ou Mobile Banking</h6>
                                                </div>
                                                
                                                <div class="alert alert-light border shadow-sm">
                                                    <div class="row mb-2">
                                                        <div class="col-5 font-weight-bold">Entidade:</div>
                                                        <div class="col-7 text-primary font-weight-bold">11151</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-5 font-weight-bold">Referência:</div>
                                                        <div class="col-7 d-flex justify-content-between">
                                                            <span class="text-dark font-weight-bold">{{ $matricula->referencia ?? 'Não gerada' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-5 font-weight-bold">Valor:</div>
                                                        <div class="col-7 font-weight-bold">{{ number_format($matricula->valor, 2, ',', '.') }} MZN</div>
                                                    </div>
                                                </div>

                                                <div class="bg-light p-3 rounded">
                                                    <small class="text-muted d-block mb-2"><strong>Como pagar:</strong></small>
                                                    <ol class="small text-muted pl-3">
                                                        <li>Seleccione Pagamentos > Pagamento de Serviços</li>
                                                        <li>Introduza a Entidade, Referência e o Valor</li>
                                                        <li>Confirme os dados e guarde o comprovativo</li>
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted mb-2 d-block fa-2x"></i>
                                        Nenhum registro de matrícula encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-default float-right">Ver Tudo</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .list-group-unbordered>.list-group-item {
        border-left: 0;
        border-right: 0;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
    }
    .badge-pill {
        padding-right: .6em;
        padding-left: .6em;
    }
</style>
@stop