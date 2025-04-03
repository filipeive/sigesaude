@extends('adminlte::page')
@section('title', 'Detalhes da Disciplina')
@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)

@section('content')
<div class="container-fluid">
    <!-- Header com breadcrumb e ações principais -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-book-open text-primary mr-2"></i>{{ $disciplina->nome }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('docente.disciplinas') }}">Disciplinas</a></li>
                    <li class="breadcrumb-item active">{{ $disciplina->nome }}</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="imprimirPauta()">
                    <i class="fas fa-print mr-1"></i> Imprimir Pauta
                </button>
                <button type="button" class="btn btn-success" onclick="exportarLista()">
                    <i class="fas fa-file-excel mr-1"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $disciplina->inscricaoDisciplinas()->count() }}</h3>
                    <p>Estudantes Inscritos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $disciplina->inscricaoDisciplinas()->whereHas('notasFrequencia', function($q) {
                        $q->where('status', 'Admitido');
                    })->count() }}</h3>
                    <p>Estudantes Admitidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $disciplina->inscricaoDisciplinas()->whereHas('notasFrequencia', function($q) {
                        $q->where('status', 'Pendente');
                    })->count() }}</h3>
                    <p>Avaliações Pendentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $disciplina->inscricaoDisciplinas()->whereHas('notasFrequencia', function($q) {
                        $q->where('status', 'Excluído');
                    })->count() }}</h3>
                    <p>Estudantes Excluídos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Disciplina e Gráficos -->
    {{-- <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Análise de Desempenho
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="desempenhoChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Detalhes da Disciplina
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tr>
                            <td><i class="fas fa-graduation-cap text-primary mr-2"></i>Curso</td>
                            <td>{{ $disciplina->curso->nome ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-layer-group text-info mr-2"></i>Nível</td>
                            <td>{{ $disciplina->nivel->nome ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-clock text-warning mr-2"></i>Carga Horária</td>
                            <td>{{ $disciplina->carga_horaria ?? 'N/A' }} horas</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-calendar text-success mr-2"></i>Semestre</td>
                            <td>{{ $disciplina->semestre ?? 'N/A' }}º</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-success mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Distribuição por Gênero
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="generoChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Tabela de Estudantes -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate mr-1"></i>
                Lista de Estudantes
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="estudantesTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Gênero</th>
                            <th>Tipo de Inscrição</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disciplina->inscricaoDisciplinas as $inscricao)
                            @if($inscricao->inscricao && $inscricao->inscricao->estudante)
                                <tr>
                                    <td>{{ $inscricao->inscricao->estudante->matricula }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $inscricao->inscricao->estudante->user->avatar ?? asset('img/default-avatar.png') }}" 
                                                 class="img-circle mr-2" 
                                                 alt="Avatar" 
                                                 style="width: 32px; height: 32px;">
                                            {{ $inscricao->inscricao->estudante->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-{{ $inscricao->inscricao->estudante->genero == 'Masculino' ? 'male text-primary' : 'female text-danger' }} mr-1"></i>
                                        {{ $inscricao->inscricao->estudante->genero }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $inscricao->tipo == 'Normal' ? 'info' : 'warning' }}">
                                            {{ $inscricao->tipo }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $notaFrequencia = \App\Models\NotaFrequencia::where([
                                                'estudante_id' => $inscricao->inscricao->estudante_id,
                                                'disciplina_id' => $disciplina->id
                                            ])->first();
                                            
                                            $statusClass = [
                                                'Admitido' => 'success',
                                                'Excluído' => 'danger',
                                                'Pendente' => 'warning'
                                            ];
                                        @endphp
                                        
                                        <span class="badge badge-{{ $notaFrequencia ? $statusClass[$notaFrequencia->status] ?? 'secondary' : 'secondary' }}">
                                            {{ $notaFrequencia ? $notaFrequencia->status : 'Não avaliado' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" 
                                                    class="btn btn-sm btn-info" 
                                                    onclick="verDetalhes({{ $inscricao->inscricao->estudante_id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary" 
                                                    onclick="editarNotas({{ $inscricao->inscricao->estudante_id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
<style>
    /* Cards e Boxes */
    .small-box {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
        margin-bottom: 1.5rem;
        transition: transform 0.2s ease-in-out;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .small-box .icon {
        transition: all 0.3s linear;
        opacity: 0.8;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
        opacity: 1;
    }

    /* Tabelas */
    .table-hover tbody tr {
        transition: background-color 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,.05) !important;
    }
    .table td {
        vertical-align: middle;
    }
    
    /* Badges e Status */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
    
    /* Cards */
    .card {
        box-shadow: 0 0 1rem rgba(0,0,0,.075);
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0 1.5rem rgba(0,0,0,.1);
    }
    
    /* Botões */
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }
    .btn-group .btn {
        border-radius: 0.375rem;
    }
    
    /* Avatar e imagens */
    .img-circle {
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        transition: transform 0.2s ease;
    }
    .img-circle:hover {
        transform: scale(1.1);
    }
    
    /* Charts */
    canvas {
        max-width: 100%;
        height: auto !important;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        .btn-group .btn {
            margin-bottom: 0.5rem;
            width: 100%;
        }
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Inicializar DataTables com configurações melhoradas
    const table = $('#estudantesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-sm btn-success',
                text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'pdf',
                className: 'btn btn-sm btn-danger',
                text: '<i class="fas fa-file-pdf mr-1"></i> PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            },
            {
                extend: 'print',
                className: 'btn btn-sm btn-secondary',
                text: '<i class="fas fa-print mr-1"></i> Imprimir',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            }
        ]
    });

    // Adicionar classe de animação aos elementos
    $('.small-box, .card').addClass('fade-in');

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Melhorar interação com botões
    $('.btn').on('mousedown', function() {
        $(this).addClass('active');
    }).on('mouseup mouseleave', function() {
        $(this).removeClass('active');
    });
});
</script>
@stop