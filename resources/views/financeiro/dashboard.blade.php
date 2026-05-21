@extends('adminlte::page')

@section('title', 'Dashboard Financeiro')

@section('content_header')
    <h1>Dashboard Financeiro</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['pagos'] ?? 0 }}</h3>
                    <p>Pagamentos Confirmados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('financeiro.pagamentos.index', ['status' => 'pago']) }}" class="small-box-footer">
                    Ver Pagamentos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['total_pago'] ?? 0, 2, ',', '.') }}</h3>
                    <p>Total Pago (MZN)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ route('financeiro.relatorios.index') }}" class="small-box-footer">
                    Gerar Relatórios <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['pendentes'] ?? 0 }}</h3>
                    <p>Pagamentos Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('financeiro.pagamentos.index', ['status' => 'pendente']) }}" class="small-box-footer">
                    Ver pendentes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Resumo Financeiro</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Referência</th>
                                    <th>Estudante</th>
                                    <th>Categoria</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagamentosRecentes as $pagamento)
                                    <tr>
                                        <td><code>{{ $pagamento->referencia }}</code></td>
                                        <td>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($pagamento->tipo ?? 'N/A') }}</td>
                                        <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                        <td>
                                            <span class="badge badge-{{ $pagamento->status === 'pago' ? 'success' : ($pagamento->status === 'cancelado' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($pagamento->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Sem pagamentos recentes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script></script>
@stop
