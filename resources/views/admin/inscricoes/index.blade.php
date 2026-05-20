@extends('adminlte::page')

@section('title', 'Inscrições Semestrais')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-plus mr-2"></i>Inscrições Semestrais</h1>
        <a href="{{ route('admin.inscricoes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Inscrição
        </a>
    </div>
@stop

@section('content')
    <!-- Inscrições Pendentes -->
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-hourglass-half mr-1"></i> Pendentes</h3>
        </div>
        <div class="card-body">
            @if ($inscricoesPendentes->isEmpty())
                <div class="alert alert-info mb-0"><i class="fas fa-info-circle mr-1"></i> Nenhuma inscrição pendente.</div>
            @else
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Estudante</th>
                            <th>Turma</th>
                            <th>Ano Lectivo</th>
                            <th>Semestre</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscricoesPendentes as $inscricao)
                            <tr>
                                <td>{{ $inscricao->estudante->user->name }}</td>
                                <td>{{ $inscricao->estudante->turma?->classe?->nome ?? 'N/A' }} ({{ $inscricao->estudante->turma?->nome ?? 'N/T' }})</td>
                                <td>{{ $inscricao->anoLectivo->ano }}</td>
                                <td>{{ $inscricao->semestre }}º</td>
                                <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.inscricoes.show', $inscricao->id) }}" class="btn btn-sm btn-primary" title="Ver"><i class="fas fa-eye"></i></a>
                                    <form action="{{ route('admin.inscricoes.aprovar', $inscricao->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Aprovar" onclick="return confirm('Aprovar esta inscrição?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.inscricoes.recusar', $inscricao->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" title="Recusar" onclick="return confirm('Recusar esta inscrição?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Inscrições Confirmadas -->
    <div class="card card-outline card-success mt-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-check-circle mr-1"></i> Confirmadas</h3>
        </div>
        <div class="card-body">
            @if ($inscricoesConfirmadas->isEmpty())
                <div class="alert alert-info mb-0"><i class="fas fa-info-circle mr-1"></i> Nenhuma inscrição confirmada.</div>
            @else
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Estudante</th>
                            <th>Turma</th>
                            <th>Ano Lectivo</th>
                            <th>Semestre</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscricoesConfirmadas as $inscricao)
                            <tr>
                                <td>{{ $inscricao->estudante->user->name }}</td>
                                <td>{{ $inscricao->estudante->turma?->classe?->nome ?? 'N/A' }} ({{ $inscricao->estudante->turma?->nome ?? 'N/T' }})</td>
                                <td>{{ $inscricao->anoLectivo->ano }}</td>
                                <td>{{ $inscricao->semestre }}º</td>
                                <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.inscricoes.show', $inscricao->id) }}" class="btn btn-sm btn-primary" title="Ver"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-outline {
            border-top: 3px solid !important;
        }

        .card-warning {
            border-top-color: #ffc107 !important;
        }

        .card-success {
            border-top-color: #28a745 !important;
        }
    </style>
@stop