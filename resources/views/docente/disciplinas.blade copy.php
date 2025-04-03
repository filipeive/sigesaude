@extends('adminlte::page')
@section('title', 'Minhas Disciplinas')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content')
<div class="container-fluid">
    <!-- Header com breadcrumb -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-book-reader text-primary mr-2"></i>Minhas Disciplinas
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Disciplinas</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary" onclick="exportarDisciplinas()">
                <i class="fas fa-download mr-1"></i> Exportar Dados
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $disciplinas->count() }}</h3>
                    <p>Total de Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $disciplinas->sum(function($d) { 
                        return $d->inscricaoDisciplinas()->count(); 
                    }) }}</h3>
                    <p>Total de Estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $disciplinas->pluck('curso_id')->unique()->count() }}</h3>
                    <p>Cursos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $disciplinas->pluck('nivel_id')->unique()->count() }}</h3>
                    <p>Níveis</p>
                </div>
                <div class="icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Disciplinas -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Lista de Disciplinas
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="disciplinasTable">
                    <thead class="bg-light">
                        <tr>
                            <th>Disciplina</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Estudantes</th>
                            <th>Status</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinas as $disciplina)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fa-stack fa-1x mr-2">
                                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                            <i class="fas fa-book-open fa-stack-1x fa-inverse"></i>
                                        </span>
                                        {{ $disciplina->nome }}
                                    </div>
                                </td>
                                <td>{{ $disciplina->curso->nome ?? 'N/A' }}</td>
                                <td>{{ $disciplina->nivel->nome ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $disciplina->inscricaoDisciplinas()->count() }} estudantes
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $pendentes = $disciplina->inscricaoDisciplinas()
                                            ->whereHas('notasFrequencia', function($q) {
                                                $q->where('status', 'Pendente');
                                            })->count();
                                        
                                        $statusClass = $pendentes > 0 ? 'warning' : 'success';
                                        $statusText = $pendentes > 0 ? 'Avaliações Pendentes' : 'Em Dia';
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('docente.disciplina', $disciplina->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           data-toggle="tooltip" 
                                           title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" 
                                           class="btn btn-sm btn-primary"
                                           data-toggle="tooltip" 
                                           title="Notas & Frequência">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="{{ route('docente.notas_exames.show', $disciplina->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           data-toggle="tooltip" 
                                           title="Exames">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
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

    /* Tabela */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
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

    /* Animações */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Inicializar DataTable
    $('#disciplinasTable').DataTable({
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
                text: '<i class="fas fa-file-excel"></i>'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger',
                text: '<i class="fas fa-file-pdf"></i>'
            }
        ]
    });

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Adicionar animações
    $('.small-box, .card').addClass('fade-in');
});

// Função para exportar dados
window.exportarDisciplinas = function() {
    Swal.fire({
        title: 'Exportar Dados',
        text: 'Escolha o formato de exportação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF',
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('.buttons-excel').click();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            $('.buttons-pdf').click();
        }
    });
}
</script>
@stop