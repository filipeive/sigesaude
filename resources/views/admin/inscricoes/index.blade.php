@extends('adminlte::page')

@section('title', 'Inscrições')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Inscrições</h1>
        <a href="{{ route('admin.inscricoes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Inscrição
        </a>
    </div>
@stop

@section('content')
    <!-- Card para Inscrições Pendentes -->
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-hourglass-half"></i> Inscrições Pendentes
            </h3>
        </div>
        <div class="card-body">
            @if ($inscricoesPendentes->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma inscrição pendente no momento.
                </div>
            @else
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Estudante</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Ano Lectivo</th>
                            <th>Semestre</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscricoesPendentes as $inscricao)
                            <tr>
                                <td>{{ $inscricao->estudante->user->name }}</td>
                                <td>{{ $inscricao->estudante->curso->nome }}</td>
                                <td>{{ $inscricao->estudante->nivel->nome }}</td>
                                <td>{{ $inscricao->anoLectivo->ano }}</td>
                                <td>{{ $inscricao->semestre }}</td>
                                <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-warning">Pendente</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.inscricoes.show', $inscricao->id) }}"
                                        class="btn btn-sm btn-primary" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.inscricoes.edit', $inscricao->id) }}"
                                        class="btn btn-sm btn-warning" title="Editar inscrição">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.inscricoes.aprovar', $inscricao->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Aprovar inscrição"
                                            onclick="return confirm('Tem certeza que deseja aprovar esta inscrição?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.inscricoes.recusar', $inscricao->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Recusar inscrição"
                                            onclick="return confirm('Tem certeza que deseja recusar esta inscrição?')">
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

    <!-- Card para Inscrições Confirmadas -->
    <div class="card card-outline card-success mt-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-check-circle"></i> Inscrições Confirmadas
            </h3>
        </div>
        <div class="card-body">
            @if ($inscricoesConfirmadas->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma inscrição confirmada no momento.
                </div>
            @else
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Estudante</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Ano Lectivo</th>
                            <th>Semestre</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscricoesConfirmadas as $inscricao)
                            <tr>
                                <td>{{ $inscricao->estudante->user->name }}</td>
                                <td>{{ $inscricao->estudante->curso->nome }}</td>
                                <td>{{ $inscricao->estudante->nivel->nome }}</td>
                                <td>{{ $inscricao->anoLectivo->ano }}</td>
                                <td>{{ $inscricao->semestre }}</td>
                                <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-success">Confirmada</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.inscricoes.show', $inscricao->id) }}"
                                        class="btn btn-sm btn-primary" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.inscricoes.edit', $inscricao->id) }}"
                                        class="btn btn-sm btn-warning" title="Editar inscrição">
                                        <i class="fas fa-edit"></i>
                                    </a>
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