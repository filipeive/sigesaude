@extends('adminlte::page')

@section('title', 'Painel do Docente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard Docente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Disciplinas</span>
                    <span class="info-box-number">{{ $totalDisciplinas }}</span>
                    <a href="{{ route('docente.disciplinas') }}" class="small">Ver detalhes <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-graduate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Estudantes</span>
                    <span class="info-box-number">{{ array_sum($estudantesPorDisciplina) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clipboard-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avaliações Pendentes</span>
                    <span class="info-box-number">{{ isset($avaliacoesPendentes) ? $avaliacoesPendentes : 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ano Letivo Atual</span>
                    <span class="info-box-number">{{ isset($anoLectivoAtual) ? $anoLectivoAtual->ano : date('Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Estudantes por Disciplina
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="estudantesPorDisciplina" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Minhas Disciplinas
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Disciplina</th>
                                <th>Curso</th>
                                <th>Estudantes</th>
                                <th class="text-center">Progresso</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disciplinas as $disciplina)
                            <tr>
                                <td>
                                    <strong>{{ $disciplina->nome }}</strong>
                                </td>
                                <td>{{ $disciplina->curso->nome ?? 'N/A' }}</td>
                                <td>{{ $estudantesPorDisciplina[$disciplina->id] ?? 0 }}</td>
                                <td class="text-center">
                                    @php
                                        // Calcular progresso (exemplo)
                                        $progresso = rand(0, 100); // Substitua por lógica real
                                    @endphp
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-success" style="width: {{ $progresso }}%"></div>
                                    </div>
                                    <span class="badge bg-{{ $progresso < 30 ? 'danger' : ($progresso < 70 ? 'warning' : 'success') }}">
                                        {{ $progresso }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-list-alt"></i> Notas
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}">
                                                <i class="fas fa-clipboard-list"></i> Frequência
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-file-alt"></i> Exames
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-users"></i> Lista de Estudantes
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn mr-1"></i>
                        Avisos Importantes
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <li class="item">
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    Prazo de Entrega de Notas
                                    <span class="badge badge-warning float-right">Urgente</span>
                                </a>
                                <span class="product-description">
                                    Prazo final para entrega das notas de frequência: 15/12/2023
                                </span>
                            </div>
                        </li>
                        <li class="item">
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    Reunião de Docentes
                                    <span class="badge badge-info float-right">Info</span>
                                </a>
                                <span class="product-description">
                                    Próxima reunião: 10/12/2023 às 14:00
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">Ver Todos os Avisos</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar mr-1"></i>
                        Calendário Acadêmico
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar" style="width: 100%"></div>
                </div>
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">Ver Calendário Completo</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-1"></i>
                        Tarefas Pendentes
                    </h3>
                </div>
                <div class="card-body p-0">
                    <ul class="todo-list" data-widget="todo-list">
                        <li>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo1" id="todoCheck1">
                                <label for="todoCheck1"></label>
                            </div>
                            <span class="text">Lançar notas de frequência</span>
                            <small class="badge badge-danger"><i class="far fa-clock"></i> 2 dias</small>
                        </li>
                        <li>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo2" id="todoCheck2">
                                <label for="todoCheck2"></label>
                            </div>
                            <span class="text">Preparar exames finais</span>
                            <small class="badge badge-info"><i class="far fa-clock"></i> 1 semana</small>
                        </li>
                    </ul>
                </div>
                <div class="card-footer clearfix">
                    <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Adicionar Tarefa</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
<style>
    .info-box-content a {
        color: #007bff;
        text-decoration: none;
    }
    .info-box-content a:hover {
        text-decoration: underline;
    }
    .progress-xs {
        height: 5px;
    }
    .todo-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .todo-list > li {
        border-left: 2px solid #e9ecef;
        padding: 10px 15px;
        margin-bottom: 2px;
        background-color: #f8f9fa;
    }
    .todo-list > li:hover {
        background-color: #f1f1f1;
    }
    .handle {
        cursor: move;
        margin-right: 10px;
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script>
$(function () {
    'use strict'
    
    // Gráfico de Estudantes por Disciplina
    var disciplinas = {!! json_encode($disciplinas->pluck('nome')) !!};
    var estudantes = {!! json_encode($disciplinas->map(function($disciplina) use ($estudantesPorDisciplina) {
        return $estudantesPorDisciplina[$disciplina->id] ?? 0;
    })) !!};
    
    var ctx = document.getElementById('estudantesPorDisciplina').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: disciplinas,
            datasets: [{
                label: 'Estudantes',
                backgroundColor: '#36a2eb',
                borderColor: '#36a2eb',
                borderWidth: 1,
                data: estudantes
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    },
                    gridLines: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });
    
    // Inicializar o calendário
    $('#calendar').fullCalendar({
        height: 250,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: moment().format('YYYY-MM-DD'),
        navLinks: false,
        editable: false,
        eventLimit: true,
        events: [
            {
                title: 'Entrega de Notas',
                start: '2023-12-15',
                backgroundColor: '#f56954',
                borderColor: '#f56954'
            },
            {
                title: 'Reunião Docentes',
                start: '2023-12-10',
                backgroundColor: '#00a65a',
                borderColor: '#00a65a'
            },
            {
                title: 'Início Exames',
                start: '2023-12-20',
                backgroundColor: '#f39c12',
                borderColor: '#f39c12'
            }
        ]
    });
    
    // Inicializar widgets AdminLTE
    $('[data-widget="todo-list"]').TodoList();
});
</script>
@endsection