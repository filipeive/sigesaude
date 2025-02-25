@extends('adminlte::page')
@section('title', 'Dashboard - Instituto de Saúde')

@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/morris/morris.css') }}">
    <style>
        .small-box { transition: transform 0.3s; }
        .small-box:hover { transform: translateY(-5px); }
        .description-block { transition: all 0.3s; }
        .description-block:hover { background: rgba(0,0,0,0.05); }
        .card { border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
@endsection

@section('content_header')
    <h1>Dashboard</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt"></i> Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <!-- Estatísticas Principais -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalEstudantes ?? 0 }}</h3>
                    <p>Estudantes Matriculados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
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
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('admin.docentes.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalCursos ?? 0 }}</h3>
                    <p>Cursos Disponíveis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-medical"></i>
                </div>
                <a href="{{ route('admin.cursos.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalPagamentos ?? 0, 2, ',', '.') }}</h3>
                    <p>Total Pagamentos (MZN)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('admin.pagamentos.index') }}" class="small-box-footer">
                    Ver Detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Gráficos e Informações Detalhadas -->
    <div class="row">
        <!-- Distribuição de Estudantes por Curso -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Distribuição de Estudantes por Curso
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="estudantesPorCurso" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Histórico de Pagamentos -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Histórico de Pagamentos
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="historicoPagamentos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="row">
        <!-- Lista de Cursos Populares -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cursos Mais Procurados</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($cursosMaisProcurados ?? [] as $curso)
                        <li class="item">
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    {{ $curso->nome }}
                                    <span class="badge badge-info float-right">{{ $curso->total_estudantes }} estudantes</span>
                                </a>
                                <span class="product-description">
                                    {{ $curso->descricao }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Atividades Recentes</h3>
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
                                <span class="product-description">
                                    {{ $atividade->descricao }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Distribuição de Estudantes por Curso
        const ctxEstudantes = document.getElementById('estudantesPorCurso').getContext('2d');
        new Chart(ctxEstudantes, {
            type: 'pie',
            data: {
                labels: {!! json_encode($cursosLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($cursosData ?? []) !!},
                    backgroundColor: [
                        '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Gráfico de Histórico de Pagamentos
        const ctxPagamentos = document.getElementById('historicoPagamentos').getContext('2d');
        new Chart(ctxPagamentos, {
            type: 'line',
            data: {
                labels: {!! json_encode($pagamentosLabels ?? []) !!},
                datasets: [{
                    label: 'Total de Pagamentos (MZN)',
                    data: {!! json_encode($pagamentosData ?? []) !!},
                    fill: false,
                    borderColor: '#00a65a',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection