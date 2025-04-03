@extends('adminlte::page')

@section('title', 'Notas e Frequências')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toastr', true)
@section('plugins.Select2', true)
@section('plugins.Inputmask', true)
@section('plugins.Moment', true)
@section('plugins.ChartJS', true)

@section('content')
<div class="container-fluid">
    <!-- Header com breadcrumb -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-clipboard-check text-primary mr-2"></i>Notas e Frequências
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notas e Frequências</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="exportarPautas()">
                    <i class="fas fa-download mr-1"></i> Exportar Pautas
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Disciplinas em Cards -->
    <div class="row">
        @forelse($disciplinas as $disciplina)
            <div class="col-md-4 mb-4">
                <div class="card card-outline card-primary h-100 fade-in">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-book-reader mr-2"></i>{{ $disciplina->nome }}
                        </h3>
                        <div class="card-tools">
                            @php
                                $percentualLancamento = $disciplina->percentual_lancamento ?? 0;
                                $statusClass = match(true) {
                                    $percentualLancamento >= 100 => 'success',
                                    $percentualLancamento >= 50 => 'warning',
                                    default => 'danger'
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">
                                {{ $percentualLancamento }}% Lançado
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1">
                                <i class="fas fa-graduation-cap text-primary mr-2"></i>
                                <strong>Curso:</strong> {{ $disciplina->curso->nome }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-layer-group text-info mr-2"></i>
                                <strong>Nível:</strong> {{ $disciplina->nivel->nome }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-calendar text-success mr-2"></i>
                                <strong>Semestre:</strong> {{ $disciplina->semestre }}º
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-users text-warning mr-2"></i>
                                <strong>Estudantes:</strong> 
                                {{ $disciplina->inscricaoDisciplinas()->count() }}
                            </p>
                        </div>
                        <div class="progress mb-3" style="height: 5px;">
                            <div class="progress-bar bg-{{ $statusClass }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentualLancamento }}%" 
                                 aria-valuenow="{{ $percentualLancamento }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" 
                           class="btn btn-primary btn-block">
                            <i class="fas fa-clipboard-list mr-2"></i>Gerenciar Notas
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Nenhuma disciplina disponível no momento.
                </div>
            </div>
        @endforelse
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

    /* Cards */
    .card {
        box-shadow: 0 0 1rem rgba(0,0,0,.075);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    }

    /* Animações */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Progress Bar */
    .progress {
        background-color: rgba(0,0,0,.05);
        border-radius: 1rem;
    }
    .progress-bar {
        border-radius: 1rem;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Adicionar animações
    $('.card').addClass('fade-in');
    
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

// Função para exportar pautas
window.exportarPautas = function() {
    Swal.fire({
        title: 'Exportar Pautas',
        text: 'Escolha o formato de exportação',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Excel',
        cancelButtonText: 'PDF',
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('docente.notas_frequencia.export', ['type' => 'excel']) }}";
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = "{{ route('docente.notas_frequencia.export', ['type' => 'pdf']) }}";
        }
    });
}
</script>
@stop