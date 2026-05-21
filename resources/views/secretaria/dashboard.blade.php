@extends('adminlte::page')

@section('title', 'Dashboard Secretaria')

@section('content_header')
    <h1>Dashboard da Secretaria</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['matriculas_pendentes'] ?? 0 }}</h3>
                    <p>Matrículas Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('secretaria.matriculas.index', ['status' => 'Pendente']) }}" class="small-box-footer">
                    Ver matrículas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['pagamentos_pendentes'] ?? 0 }}</h3>
                    <p>Pagamentos Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('secretaria.pagamentos.index', ['status' => 'pendente']) }}" class="small-box-footer">
                    Ver Pagamentos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['estudantes'] ?? 0 }}</h3>
                    <p>Estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('secretaria.estudantes.index') }}" class="small-box-footer">
                    Ver Estudantes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['matriculas_ativas'] ?? 0 }}</h3>
                    <p>Matrículas Ativas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="{{ route('secretaria.matriculas.index', ['status' => 'Ativo']) }}" class="small-box-footer">
                    Ver ativas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Matrículas Recentes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>Turma</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matriculasRecentes as $matricula)
                                <tr>
                                    <td>{{ $matricula->estudante?->user?->name ?? 'N/A' }}</td>
                                    <td>{{ $matricula->turma?->nome ?? 'N/A' }}</td>
                                    <td><span class="badge badge-{{ $matricula->status === 'Ativo' ? 'success' : 'warning' }}">{{ $matricula->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Sem matrículas recentes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Pagamentos Pendentes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Referência</th>
                                <th>Estudante</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagamentosPendentes as $pagamento)
                                <tr>
                                    <td><code>{{ $pagamento->referencia }}</code></td>
                                    <td>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Sem pagamentos pendentes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
