@extends('adminlte::page')
@section('title', 'Notas de Exames')
@section('plugins.Sweetalert2', true)

@section('content')
<div class="container-fluid">
    <!-- Header com breadcrumb -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-file-alt text-warning mr-2"></i>Notas de Exames
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notas de Exames</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-warning">
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
            <div class="small-box bg-info">
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
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $disciplinas->sum(function($d) {
                        return $d->inscricaoDisciplinas()
                            ->whereHas('notasFrequencia', function($q) {
                                $q->where('status', 'Admitido');
                            })->count();
                    }) }}</h3>
                    <p>Admitidos a Exame</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $disciplinas->sum(function($d) {
                        return $d->inscricaoDisciplinas()
                            ->whereHas('notasFrequencia', function($q) {
                                $q->where('status', 'Excluído');
                            })->count();
                    }) }}</h3>
                    <p>Excluídos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Disciplinas -->
    <div class="row">
        @foreach($disciplinas as $disciplina)
        <div class="col-md-4">
            <div class="card card-outline card-warning h-100 fade-in">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book-reader mr-2"></i>{{ $disciplina->nome }}
                    </h3>
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
                            <i class="fas fa-users text-success mr-2"></i>
                            <strong>Estudantes:</strong> 
                            {{ $disciplina->inscricaoDisciplinas()->count() }}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-check-circle text-warning mr-2"></i>
                            <strong>Admitidos:</strong>
                            {{ $disciplina->inscricaoDisciplinas()
                                ->whereHas('notasFrequencia', function($q) {
                                    $q->where('status', 'Admitido');
                                })->count() }}
                        </p>
                    </div>
                    <a href="{{ route('docente.notas_exames.show', $disciplina->id) }}" 
                       class="btn btn-warning btn-block">
                        <i class="fas fa-file-alt mr-2"></i>Lançar Exames
                    </a>
                </div>
            </div>
        </div>
        @endforeach
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
    // Adicionar animações
    $('.card').addClass('fade-in');

    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@stop