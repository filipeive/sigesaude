{{-- resources/views/estudante/relatorios.blade.php --}}
@extends('adminlte::page')

@section('title', 'Meus Relatórios')

@section('content_header')
    <h1><i class="fas fa-chart-bar mr-2"></i>Meus Relatórios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('estudante.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active">Relatórios</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <!-- Notas por Período -->
        <div class="col-md-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Boletim de Notas</h3>
                </div>
                <div class="card-body">
                    <p>Consulte o seu boletim com todas as notas de frequência e de exame por ano letivo.</p>
                    <a href="{{ route('estudante.notas_frequencia.notas') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-eye mr-1"></i> Ver Notas de Frequência
                    </a>
                    <a href="{{ route('estudante.notas_exame.notas') }}" class="btn btn-success btn-block">
                        <i class="fas fa-file-signature mr-1"></i> Ver Notas de Exame
                    </a>
                </div>
            </div>
        </div>

        <!-- Histórico de Matrículas -->
        <div class="col-md-4">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>Histórico de Matrículas</h3>
                </div>
                <div class="card-body">
                    <p>Veja todas as suas matrículas anteriores e o histórico de inscrições semestrais.</p>
                    <a href="{{ route('estudante.matriculas') }}" class="btn btn-success btn-block">
                        <i class="fas fa-list mr-1"></i> Ver Histórico de Matrículas
                    </a>
                    <a href="{{ route('estudante.inscricoes.index') }}" class="btn btn-outline-success btn-block mt-1">
                        <i class="fas fa-plus-circle mr-1"></i> Ver Inscrições Semestrais
                    </a>
                </div>
            </div>
        </div>

        <!-- Situação Financeira -->
        <div class="col-md-4">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-wallet mr-2"></i>Situação Financeira</h3>
                </div>
                <div class="card-body">
                    <p>Consulte o histórico de pagamentos de matrícula e propinas mensais.</p>
                    <a href="{{ route('estudante.pagamentos') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-money-bill-wave mr-1"></i> Ver Pagamentos
                    </a>
                    <a href="{{ route('estudante.matriculas') }}" class="btn btn-outline-warning btn-block mt-1">
                        <i class="fas fa-receipt mr-1"></i> Ver Guia de Matrícula
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Informações Académicas -->
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Informações Académicas</h3>
                </div>
                <div class="card-body">
                    @php
                        $estudante = auth()->user()->estudante;
                    @endphp
                    @if($estudante && $estudante->turma)
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="40%">Estudante</th>
                                <td>{{ $estudante->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Número de Matrícula</th>
                                <td>{{ $estudante->matricula }}</td>
                            </tr>
                            <tr>
                                <th>Turma</th>
                                <td>{{ $estudante->turma->nome }}</td>
                            </tr>
                            <tr>
                                <th>Classe</th>
                                <td>{{ $estudante->turma->classe->nome ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Ano Lectivo</th>
                                <td>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Turno</th>
                                <td>{{ $estudante->turno }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $estudante->status == 'Ativo' ? 'success' : ($estudante->status == 'Inativo' ? 'danger' : 'warning') }}">
                                        {{ $estudante->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Você ainda não está matriculado em nenhuma turma. Dirija-se à secretaria para concluir o processo.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Resumo de Pagamentos -->
        <div class="col-md-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Resumo Financeiro</h3>
                </div>
                <div class="card-body">
                    @php
                        $estudante = auth()->user()->estudante;
                        $pagamentos = $estudante ? $estudante->pagamentos()->latest()->take(5)->get() : collect();
                        $totalPago = $estudante ? $estudante->pagamentos()->where('status', 'pago')->sum('valor') : 0;
                        $totalPendente = $estudante ? $estudante->pagamentos()->where('status', 'pendente')->sum('valor') : 0;
                    @endphp
                    @if($estudante)
                        <table class="table table-bordered">
                            <tr>
                                <th>Total Pago</th>
                                <td class="text-success font-weight-bold">{{ number_format($totalPago, 2, ',', '.') }} MZN</td>
                            </tr>
                            <tr>
                                <th>Total Pendente</th>
                                <td class="text-warning font-weight-bold">{{ number_format($totalPendente, 2, ',', '.') }} MZN</td>
                            </tr>
                        </table>
                        <h5 class="mt-3">Últimos Pagamentos</h5>
                        @if($pagamentos->count() > 0)
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Referência</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagamentos as $pag)
                                        <tr>
                                            <td>{{ $pag->referencia }}</td>
                                            <td>{{ $pag->tipo }}</td>
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
                            <p class="text-muted mb-0">Nenhum pagamento registado.</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">Sem dados financeiros disponíveis.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
