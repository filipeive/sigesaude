@extends('adminlte::page')
@section('title', 'Minhas Turmas')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                <i class="fas fa-users text-primary mr-2"></i>Minhas Turmas
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Turmas</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Cards de Resumo -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="small-box bg-gradient-info elevation-2">
                <div class="inner">
                    <h3>{{ $turmas->count() }}</h3>
                    <p>Total de Turmas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users-cog"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="small-box bg-gradient-success elevation-2">
                <div class="inner">
                    <h3>{{ $turmas->sum(fn($t) => $t->estudantes()->where('status', 'Ativo')->count()) }}</h3>
                    <p>Total de Estudantes Ativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="small-box bg-gradient-warning elevation-2">
                <div class="inner">
                    <h3>{{ $turmas->pluck('classe_id')->unique()->count() }}</h3>
                    <p>Classes Diferentes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-school"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Turmas -->
    <div class="card card-outline card-primary elevation-1">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Turmas Associadas às suas Disciplinas
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="turmasTable">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">#</th>
                            <th>Turma</th>
                            <th>Classe</th>
                            <th>Ano Lectivo</th>
                            <th>Disciplinas que Leciona</th>
                            <th>Estudantes Ativos</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($turmas as $index => $turma)
                            @php
                                $totalEstudantes = $turma->estudantes()->where('status', 'Ativo')->count();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-info mr-3">
                                            <span class="initials">{{ substr($turma->nome, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 font-weight-bold text-primary">{{ $turma->nome }}</h5>
                                            <small class="text-muted">{{ $turma->descricao }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light border">
                                        <i class="fas fa-university mr-1"></i>
                                        {{ $turma->classe->nome ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $turma->anoLectivo->ano ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if(isset($turma->disciplinas_docente) && count($turma->disciplinas_docente) > 0)
                                        @foreach($turma->disciplinas_docente as $disciplina)
                                            <a href="{{ route('docente.disciplina', $disciplina->id) }}" class="badge badge-primary p-2 mb-1">
                                                <i class="fas fa-book mr-1"></i>{{ $disciplina->nome }}
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Nenhuma disciplina cadastrada</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success px-3 py-2">
                                        <i class="fas fa-user-graduate mr-1"></i>
                                        {{ $totalEstudantes }} Estudantes
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(isset($turma->disciplinas_docente) && count($turma->disciplinas_docente) > 0)
                                            @php $firstDisc = $turma->disciplinas_docente->first(); @endphp
                                            <a href="{{ route('docente.notas_frequencia.show', $firstDisc->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit mr-1"></i> Notas Freq.
                                            </a>
                                            <a href="{{ route('docente.notas_exames.show', $firstDisc->id) }}" class="btn btn-warning btn-sm text-dark">
                                                <i class="fas fa-file-invoice mr-1"></i> Exames
                                            </a>
                                        @endif
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
    .small-box {
        border-radius: 0.5rem;
        transition: transform 0.2s ease-in-out;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
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
        font-size: 18px;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#turmasTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
        },
        responsive: true
    });
});
</script>
@endsection
