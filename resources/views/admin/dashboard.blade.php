@extends('adminlte::page')
@section('title', 'Dashboard - Escola dos Visionários')

@section('css')
    <style>
        .small-box { transition: transform 0.3s; border-radius: 8px; }
        .small-box:hover { transform: translateY(-5px); }
        .card { border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .info-box { border-radius: 8px; }
    </style>
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Dashboard - Escola dos Visionários</h1>
        <span class="text-muted">{{ now()->format('d/m/Y') }}</span>
    </div>
@endsection

@section('content')
    <!-- Linha 1: Métricas Principais -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalEstudantes ?? 0 }}</h3>
                    <p>Estudantes Ativos</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                <a href="{{ route('admin.estudantes.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalDocentes ?? 0 }}</h3>
                    <p>Docentes Ativos</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <a href="{{ route('admin.docentes.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalClasses ?? 0 }}</h3>
                    <p>Classes</p>
                </div>
                <div class="icon"><i class="fas fa-school"></i></div>
                <a href="{{ route('admin.classes.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalPagamentos ?? 0, 2, ',', '.') }}</h3>
                    <p>Propinas Mês (MZN)</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <a href="{{ route('admin.pagamentos.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Linha 2: Info Boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-chalkboard"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Turmas</span>
                    <span class="info-box-number">{{ $totalTurmas ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-teal"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Disciplinas</span>
                    <span class="info-box-number">{{ $totalDisciplinas ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fas fa-graduation-cap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Matrículas</span>
                    <span class="info-box-number">{{ $totalMatriculas ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Matrículas Pendentes</span>
                    <span class="info-box-number">{{ $matriculasPendentes ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Linha 3: Gráficos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Estudantes por Turma</h3>
                </div>
                <div class="card-body">
                    <canvas id="estudantesPorTurma" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Pagamentos (Últimos 6 Meses)</h3>
                </div>
                <div class="card-body">
                    <canvas id="historicoPagamentos" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Linha 4: Turmas e Atividades -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Turmas com Mais Estudantes</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($turmasMaisPovoadas ?? [] as $turma)
                        <li class="item">
                            <div class="product-info">
                                <a href="{{ route('admin.turmas.show', $turma) }}" class="product-title">
                                    {{ $turma->classe->nome ?? '' }} {{ $turma->nome }}
                                    <span class="badge badge-info float-right">{{ $turma->estudantes_count }} estudantes</span>
                                </a>
                                <span class="product-description">
                                    {{ $turma->anoLectivo->ano ?? '' }} &middot; {{ $turma->ano_serie }}º Ano
                                </span>
                            </div>
                        </li>
                        @endforeach
                        @if(empty($turmasMaisPovoadas) || count($turmasMaisPovoadas) == 0)
                        <li class="item text-center py-3 text-muted">Nenhuma turma com estudantes.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-1"></i> Atividades Recentes</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($atividadesRecentes ?? [] as $atividade)
                        <li class="item">
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    {{ $atividade->tipo }}
                                    <span class="float-right text-muted text-sm">{{ $atividade->created_at->diffForHumans() }}</span>
                                </a>
                                <span class="product-description">{{ $atividade->descricao }}</span>
                            </div>
                        </li>
                        @endforeach
                        @if(empty($atividadesRecentes) || count($atividadesRecentes) == 0)
                        <li class="item text-center py-3 text-muted">Nenhuma atividade recente.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxEstudantes = document.getElementById('estudantesPorTurma').getContext('2d');
        new Chart(ctxEstudantes, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($turmasLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($turmasData ?? []) !!},
                    backgroundColor: ['#17a2b8', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        const ctxPagamentos = document.getElementById('historicoPagamentos').getContext('2d');
        new Chart(ctxPagamentos, {
            type: 'line',
            data: {
                labels: {!! json_encode($pagamentosLabels ?? []) !!},
                datasets: [{
                    label: 'Pagamentos (MZN)',
                    data: {!! json_encode($pagamentosData ?? []) !!},
                    fill: true,
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderColor: '#28a745',
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#28a745'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endsection