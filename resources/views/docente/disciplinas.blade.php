@extends('adminlte::page')
@section('title', 'Minhas Disciplinas')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Chart', true)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                <i class="fas fa-book-reader text-primary mr-2"></i>Minhas Disciplinas
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Disciplinas</li>
                </ol>
            </nav>
        </div>
        <div class="col-sm-6">
            <div class="float-sm-right">
                <div class="btn-group">
                    <button class="btn btn-primary" onclick="exportarDisciplinas()">
                        <i class="fas fa-download mr-1"></i> Exportar Dados
                    </button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="$('.buttons-excel').click(); return false;">
                            <i class="fas fa-file-excel text-success mr-2"></i> Excel
                        </a>
                        <a class="dropdown-item" href="#" onclick="$('.buttons-pdf').click(); return false;">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> PDF
                        </a>
                        <a class="dropdown-item" href="#" onclick="$('.buttons-print').click(); return false;">
                            <i class="fas fa-print text-primary mr-2"></i> Imprimir
                        </a>
                    </div>
                </div>
                <button class="btn btn-outline-secondary ml-2" data-toggle="modal" data-target="#filtroModal">
                    <i class="fas fa-filter mr-1"></i> Filtros
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Cards de Resumo -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3>{{ $disciplinas->count() }}</h3>
                    <p>Total de Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#disciplinasModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{ $disciplinas->sum(function($d) { 
                        return $d->inscricaoDisciplinas()->count(); 
                    }) }}</h3>
                    <p>Total de Estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#estudantesModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{ $disciplinas->pluck('curso_id')->unique()->count() }}</h3>
                    <p>Cursos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#cursosModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{ $disciplinas->pluck('nivel_id')->unique()->count() }}</h3>
                    <p>Níveis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#niveisModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Gráficos e Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Distribuição por Curso
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
                    <canvas id="cursoChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-success card-outline">
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
                    <canvas id="estudantesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Disciplinas -->
    <div class="card card-outline card-primary elevation-1">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Lista de Disciplinas
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="#" class="dropdown-item" id="toggleColumns">Mostrar/Ocultar Colunas</a>
                        <a href="#" class="dropdown-item" id="refreshTable">Atualizar Dados</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" id="resetFilters">Limpar Filtros</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="disciplinasTable">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">#</th>
                            <th>Disciplina</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Estudantes</th>
                            <th>Progresso</th>
                            <th>Status</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinas as $index => $disciplina)
                            @php
                                $totalEstudantes = $disciplina->inscricaoDisciplinas()->count();
                                $avaliadosCount = $disciplina->inscricaoDisciplinas()
                                    ->whereHas('notasFrequencia', function($q) {
                                        $q->whereNotNull('nota');
                                    })->count();
                                
                                $progressoPct = $totalEstudantes > 0 ? 
                                    round(($avaliadosCount / $totalEstudantes) * 100) : 0;
                                
                                $pendentes = $disciplina->inscricaoDisciplinas()
                                    ->whereHas('notasFrequencia', function($q) {
                                        $q->where('status', 'Pendente');
                                    })->count();
                                
                                $statusClass = $pendentes > 0 ? 'warning' : 'success';
                                $statusText = $pendentes > 0 ? 'Avaliações Pendentes' : 'Em Dia';
                                
                                $progressoClass = $progressoPct < 30 ? 'danger' : 
                                    ($progressoPct < 70 ? 'warning' : 'success');
                            @endphp
                            <tr class="fade-in-row" style="animation-delay: {{ $index * 0.05 }}s">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary mr-3">
                                            <span class="initials">{{ substr($disciplina->nome, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $disciplina->nome }}</h5>
                                            <small class="text-muted">
                                                <i class="fas fa-clock mr-1"></i> 
                                                {{ $disciplina->carga_horaria ?? 'N/A' }} horas
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light">
                                        <i class="fas fa-university mr-1"></i>
                                        {{ $disciplina->curso->nome ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-layer-group mr-1"></i>
                                        {{ $disciplina->nivel->nome ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $totalEstudantes }} estudantes
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 mr-2" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $progressoClass }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $progressoPct }}%" 
                                                 aria-valuenow="{{ $progressoPct }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="text-{{ $progressoClass }} font-weight-bold">
                                            {{ $progressoPct }}%
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $avaliadosCount }}/{{ $totalEstudantes }} avaliados
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusClass }} badge-pill">
                                        <i class="fas fa-{{ $statusClass == 'success' ? 'check-circle' : 'exclamation-circle' }} mr-1"></i>
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <style>
                                        .btn-group .btn {
                                            position: relative;
                                        }
                                        .btn-group .btn span {
                                            display: none;
                                            position: absolute;
                                            top: 50%;
                                            left: 100%;
                                            transform: translateY(-50%);
                                            background: rgba(0, 0, 0, 0.7);
                                            color: #fff;
                                            padding: 5px 10px;
                                            border-radius: 5px;
                                            white-space: nowrap;
                                            font-size: 12px;
                                        }
                                        .btn-group .btn:hover span {
                                            display: inline-block;
                                        }
                                    </style>
                                    
                                    <div class="btn-group" style="z-index: 1000;">
                                        <a href="{{ route('docente.disciplina', $disciplina->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                            <span>Ver Detalhes</span>
                                        </a>
                                        <a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-list-alt"></i>
                                            <span>Notas & Frequência</span>
                                        </a>
                                        <a href="{{ route('docente.notas_exames.show', $disciplina->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Exames</span>
                                        </a>
                                        <a href="#" onclick="verEstudantes({{ $disciplina->id }})" class="btn btn-success btn-sm">
                                            <i class="fas fa-users"></i>
                                            <span>Lista de Estudantes</span>
                                        </a>
                                        <a href="#" onclick="gerarRelatorio({{ $disciplina->id }})" class="btn btn-danger btn-sm">
                                            <i class="fas fa-chart-line"></i>
                                            <span>Relatório de Desempenho</span>
                                        </a>
                                    </div>                                                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-info-circle text-primary mr-1"></i>
                    <span class="text-muted">Mostrando {{ $disciplinas->count() }} disciplinas</span>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary" id="btnRefresh">
                        <i class="fas fa-sync-alt mr-1"></i> Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="filtroModal" tabindex="-1" role="dialog" aria-labelledby="filtroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="filtroModalLabel">
                    <i class="fas fa-filter mr-2"></i>Filtrar Disciplinas
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="filtroForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Curso</label>
                                <select class="form-control select2" id="filtroCurso" multiple>
                                    @foreach($disciplinas->pluck('curso.nome', 'curso_id')->unique() as $id => $nome)
                                        <option value="{{ $id }}">{{ $nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nível</label>
                                <select class="form-control select2" id="filtroNivel" multiple>
                                    @foreach($disciplinas->pluck('nivel.nome', 'nivel_id')->unique() as $id => $nome)
                                        <option value="{{ $id }}">{{ $nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="filtroStatus">
                                    <option value="">Todos</option>
                                    <option value="Em Dia">Em Dia</option>
                                    <option value="Avaliações Pendentes">Avaliações Pendentes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Progresso</label>
                                <select class="form-control" id="filtroProgresso">
                                    <option value="">Todos</option>
                                    <option value="0-30">Menos de 30%</option>
                                    <option value="30-70">Entre 30% e 70%</option>
                                    <option value="70-100">Mais de 70%</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" id="limparFiltros">
                    <i class="fas fa-eraser mr-1"></i> Limpar Filtros
                </button>
                <button type="button" class="btn btn-primary" id="aplicarFiltros">
                    <i class="fas fa-filter mr-1"></i> Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Estudantes -->
<div class="modal fade" id="estudantesListaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-users mr-2"></i>Lista de Estudantes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center py-5" id="estudantesLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando lista de estudantes...</p>
                </div>
                <div id="estudantesContent" style="display: none;">
                    <!-- Conteúdo será carregado via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="exportarEstudantes">
                    <i class="fas fa-download mr-1"></i> Exportar Lista
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    /* Cards e Boxes */
    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
        margin-bottom: 1.5rem;
        transition: transform 0.2s ease-in-out;
        overflow: hidden;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .small-box .icon {
        transition: all 0.3s linear;
        opacity: 0.8;
        font-size: 70px;
        right: 15px;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
        opacity: 1;
    }
    .small-box-footer {
        background: rgba(0,0,0,.1);
        color: rgba(255,255,255,.8);
        padding: 5px 0;
        display: block;
        text-align: center;
        transition: all 0.3s;
    }
    .small-box-footer:hover {
        background: rgba(0,0,0,.15);
        color: #fff;
        text-decoration: none;
    }

    /* Tabela */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,.05) !important;
    }

    /* Badges */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    .badge-pill {
        border-radius: 50rem;
        padding-right: 0.8em;
        padding-left: 0.8em;
    }
    .badge-light {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #e9ecef;
    }

    /* Botões */
    .btn {
        border-radius: 0.25rem;
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }
    .btn-group .btn {
        margin: 0 2px;
    }
    
    /* Avatar Circle */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .initials {
        font-size: 20px;
    }

    /* Progress Bar */
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
        overflow: hidden;
    }

    /* Animações */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .fade-in-row {
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .avatar-circle {
            width: 30px;
            height: 30px;
        }
        .initials {
            font-size: 16px;
        }
        .table td h5 {
            font-size: 0.9rem;
        }
    }
    
    /* DataTables Customização */
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    .dataTables_wrapper .dataTables_info {
        padding-top: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.25rem;
        margin: 0 2px;
    }
    
    /* Select2 Customização */
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #0069d9;
        color: #fff;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecione as opções',
        allowClear: true
    });
    
    // Inicializar DataTable com recursos avançados
    var table = $('#disciplinasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        },
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success',
                text: '<i class="fas fa-file-excel"></i> Excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 6]
                },
                title: 'Minhas Disciplinas - Exportação'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                exportOptions: {
                    columns: [1, 2, 3, 4, 6]
                },
                title: 'Minhas Disciplinas - Exportação'
            },
            {
                extend: 'print',
                className: 'btn btn-info',
                text: '<i class="fas fa-print"></i> Imprimir',
                exportOptions: {
                    columns: [1, 2, 3, 4, 6]
                },
                title: 'Minhas Disciplinas'
            }
        ],
        columnDefs: [
            { orderable: false, targets: [7] },
            { searchable: false, targets: [0, 5, 7] }
        ],
        order: [[1, 'asc']]
    });
    
    // Adicionar botões ao DOM
    new $.fn.dataTable.Buttons(table, {
        buttons: ['excel', 'pdf', 'print']
    });
    
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Botão de atualizar
    $('#btnRefresh, #refreshTable').on('click', function() {
        Swal.fire({
            title: 'Atualizando...',
            text: 'Atualizando dados da tabela',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                setTimeout(() => {
                    table.ajax.reload(null, false);
                    Swal.close();
                    
                    // Mostrar toast de sucesso
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Dados atualizados com sucesso!'
                    });
                }, 1000);
            }
        });
    });
    
    // Filtros avançados
    $('#aplicarFiltros').on('click', function() {
        // Obter valores dos filtros
        const cursos = $('#filtroCurso').val();
        const niveis = $('#filtroNivel').val();
        const status = $('#filtroStatus').val();
        const progresso = $('#filtroProgresso').val();
        
        // Limpar filtros existentes
        table.search('').columns().search('').draw();
        
        // Aplicar filtros
        if (cursos && cursos.length > 0) {
            const cursosRegex = cursos.join('|');
            table.column(2).search(cursosRegex, true, false);
        }
        
        if (niveis && niveis.length > 0) {
            const niveisRegex = niveis.join('|');
            table.column(3).search(niveisRegex, true, false);
        }
        
        if (status) {
            table.column(6).search(status);
        }
        
        if (progresso) {
            // Implementar lógica para filtrar por progresso
            // Esta é uma implementação simplificada
            const [min, max] = progresso.split('-');
            
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const progressText = $(table.row(dataIndex).node()).find('td:eq(5) .font-weight-bold').text();
                    const progressValue = parseInt(progressText);
                    
                    if (min && max) {
                        return progressValue >= parseInt(min) && progressValue <= parseInt(max);
                    }
                    return true;
                }
            );
        }
        
        // Aplicar todos os filtros
        table.draw();
        
        // Fechar modal
        $('#filtroModal').modal('hide');
        
        // Mostrar toast com filtros aplicados
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        
        Toast.fire({
            icon: 'info',
            title: 'Filtros aplicados com sucesso!'
        });
    });
    
    // Limpar filtros
    $('#limparFiltros, #resetFilters').on('click', function() {
        // Resetar formulário
        $('#filtroForm')[0].reset();
        $('.select2').val(null).trigger('change');
        
        // Limpar filtros da tabela
        table.search('').columns().search('').draw();
        
        // Remover filtros personalizados
        $.fn.dataTable.ext.search.pop();
        table.draw();
        
        // Fechar modal se estiver aberto
        $('#filtroModal').modal('hide');
        
        // Mostrar toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        
        Toast.fire({
            icon: 'success',
            title: 'Filtros removidos com sucesso!'
        });
    });
    
    // Toggle de colunas
    $('#toggleColumns').on('click', function() {
        // Criar modal dinâmico para seleção de colunas
        let columnsHtml = '<div class="list-group">';
        
        table.columns().every(function(index) {
            const columnTitle = $(this.header()).text();
            const isVisible = this.visible();
            
            if (index !== 7) { // Não permitir esconder a coluna de ações
                columnsHtml += `
                    <div class="list-group-item">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="column${index}" 
                                   ${isVisible ? 'checked' : ''} data-column="${index}">
                            <label class="custom-control-label" for="column${index}">${columnTitle}</label>
                        </div>
                    </div>
                `;
            }
        });
        
        columnsHtml += '</div>';
        
        Swal.fire({
            title: 'Gerenciar Colunas',
            html: columnsHtml,
            showCancelButton: true,
            confirmButtonText: 'Aplicar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                // Aplicar visibilidade das colunas
                table.columns().every(function(index) {
                    const checkbox = document.getElementById(`column${index}`);
                    if (checkbox) {
                        const isChecked = checkbox.checked;
                        this.visible(isChecked);
                    }
                });
                
                return true;
            }
        });
    });
    
    // Inicializar gráficos
    initCharts();
    
    // Função para inicializar gráficos
    function initCharts() {
        // Dados para gráfico de cursos
        const cursos = {!! json_encode($disciplinas->groupBy('curso.nome')->map->count()) !!};
        const cursosLabels = Object.keys(cursos);
        const cursosData = Object.values(cursos);
        const cursosColors = generateColors(cursosLabels.length);
        
        // Gráfico de pizza - Distribuição por Curso
        const ctxCurso = document.getElementById('cursoChart').getContext('2d');
        new Chart(ctxCurso, {
            type: 'doughnut',
            data: {
                labels: cursosLabels,
                datasets: [{
                    data: cursosData,
                    backgroundColor: cursosColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 15,
                        padding: 15
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const total = dataset.data.reduce((acc, val) => acc + val, 0);
                            const currentValue = dataset.data[tooltipItem.index];
                            const percentage = Math.round((currentValue / total) * 100);
                            return `${data.labels[tooltipItem.index]}: ${currentValue} (${percentage}%)`;
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
        
        // Dados para gráfico de estudantes por disciplina
        const disciplinasNomes = {!! json_encode($disciplinas->pluck('nome')) !!};
        const estudantesCount = {!! json_encode($disciplinas->map(function($d) { 
            return $d->inscricaoDisciplinas()->count(); 
        })) !!};
        
        // Gráfico de barras - Estudantes por Disciplina
        const ctxEstudantes = document.getElementById('estudantesChart').getContext('2d');
        new Chart(ctxEstudantes, {
            type: 'bar',
            data: {
                labels: disciplinasNomes,
                datasets: [{
                    label: 'Estudantes',
                    data: estudantesCount,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return `${tooltipItem.value} estudantes`;
                        }
                    }
                }
            }
        });
    }
    
    // Função para gerar cores aleatórias
    function generateColors(count) {
        const colors = [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 199, 199, 0.7)',
            'rgba(83, 102, 255, 0.7)',
            'rgba(40, 159, 64, 0.7)',
            'rgba(210, 199, 199, 0.7)'
        ];
        
        // Se precisarmos de mais cores do que temos predefinidas
        if (count > colors.length) {
            for (let i = colors.length; i < count; i++) {
                const r = Math.floor(Math.random() * 255);
                const g = Math.floor(Math.random() * 255);
                const b = Math.floor(Math.random() * 255);
                colors.push(`rgba(${r}, ${g}, ${b}, 0.7)`);
            }
        }
        
        return colors.slice(0, count);
    }
});

// Função para exportar dados
window.exportarDisciplinas = function() {
    Swal.fire({
        title: 'Exportar Dados',
        text: 'Escolha o formato de exportação',
        icon: 'question',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-file-excel mr-1"></i> Excel',
        denyButtonText: '<i class="fas fa-file-pdf mr-1"></i> PDF',
        cancelButtonText: '<i class="fas fa-print mr-1"></i> Imprimir',
        confirmButtonColor: '#28a745',
        denyButtonColor: '#dc3545',
        cancelButtonColor: '#17a2b8'
    }).then((result) => {
        if (result.isConfirmed) {
            $('.buttons-excel').click();
        } else if (result.isDenied) {
            $('.buttons-pdf').click();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            $('.buttons-print').click();
        }
    });
}

// Função para ver estudantes de uma disciplina
window.verEstudantes = function(disciplinaId) {
    // Mostrar modal
    $('#estudantesListaModal').modal('show');
    $('#estudantesLoading').show();
    $('#estudantesContent').hide();
    
    // Simular carregamento de dados (em produção, substituir por AJAX real)
    setTimeout(() => {
        $('#estudantesLoading').hide();
        
        // Conteúdo de exemplo - substituir por dados reais via AJAX
        const html = `
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Estudantes Matriculados</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0" id="tabelaEstudantes">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Matrícula</th>
                                <th>Nota Atual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>João Silva</td>
                                <td>2023001</td>
                                <td>15.5</td>
                                <td><span class="badge badge-success">Admitido</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Maria Santos</td>
                                <td>2023002</td>
                                <td>12.8</td>
                                <td><span class="badge badge-success">Admitido</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pedro Alves</td>
                                <td>2023003</td>
                                <td>8.5</td>
                                <td><span class="badge badge-danger">Excluído</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Ana Ferreira</td>
                                <td>2023004</td>
                                <td>14.2</td>
                                <td><span class="badge badge-success">Admitido</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Carlos Mendes</td>
                                <td>2023005</td>
                                <td>-</td>
                                <td><span class="badge badge-secondary">Não avaliado</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle mr-2"></i>
                Esta disciplina possui 5 estudantes matriculados. 3 estão admitidos, 1 excluído e 1 não avaliado.
            </div>
        `;
        
        $('#estudantesContent').html(html).fadeIn();
        
        // Inicializar DataTable na tabela de estudantes
        $('#tabelaEstudantes').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
            },
            responsive: true,
            paging: false,
            info: false
        });
    }, 1500);
}

// Função para gerar relatório de desempenho
window.gerarRelatorio = function(disciplinaId) {
    Swal.fire({
        title: 'Gerando Relatório',
        text: 'Aguarde enquanto geramos o relatório de desempenho...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                Swal.fire({
                    title: 'Relatório Gerado',
                    text: 'O relatório de desempenho foi gerado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'Visualizar',
                    showCancelButton: true,
                    cancelButtonText: 'Fechar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aqui você pode redirecionar para a página do relatório
                        // window.location.href = `/docente/disciplinas/${disciplinaId}/relatorio`;
                        
                        // Simulação - mostrar modal com relatório
                        Swal.fire({
                            title: 'Relatório de Desempenho',
                            html: `
                                <div class="text-left">
                                    <h5>Estatísticas da Disciplina</h5>
                                    <ul>
                                        <li>Total de Estudantes: 25</li>
                                        <li>Média da Turma: 14.2</li>
                                        <li>Taxa de Aprovação: 80%</li>
                                    </ul>
                                    <div class="text-center">
                                        <img src="https://via.placeholder.com/500x300?text=Gráfico+de+Desempenho" 
                                             class="img-fluid rounded" alt="Gráfico de Desempenho">
                                    </div>
                                </div>
                            `,
                            width: 600,
                            confirmButtonText: 'Fechar'
                        });
                    }
                });
            }, 2000);
        }
    });
}
</script>
@stop