@extends('adminlte::page')
@section('title', $turma->nome_completo ?? $turma->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chalkboard mr-2"></i>{{ $turma->classe->nome ?? '' }} - {{ $turma->nome }}</h1>
        <a href="{{ route('admin.turmas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Info Card -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info text-white rounded-circle" style="width:80px;height:80px;font-size:1.5rem;font-weight:bold;">
                            {{ $turma->nome }}
                        </div>
                    </div>
                    <h3 class="profile-username text-center">{{ $turma->classe->nome ?? 'N/A' }} - {{ $turma->nome }}</h3>
                    <p class="text-muted text-center">{{ $turma->anoLectivo->ano ?? 'Ano Lectivo N/A' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Classe</b> <span class="float-right">{{ $turma->classe->nome ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Ano Lectivo</b> <span class="float-right">{{ $turma->anoLectivo->ano ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Estudantes</b> <span class="float-right badge badge-info">{{ $turma->estudantes->count() }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Disciplinas</b>
                            <span class="float-right badge badge-success">
                                {{ $turma->classe ? $turma->classe->disciplinas->count() : 0 }}
                            </span>
                        </li>
                    </ul>
                    <a href="{{ route('admin.turmas.edit', $turma) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                </div>
            </div>

            <!-- Disciplinas da Classe -->
            <div class="card card-success">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-book mr-1"></i> Disciplinas</h3></div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($turma->classe->disciplinas ?? [] as $disc)
                            <li class="list-group-item d-flex justify-content-between">
                                {{ $disc->nome }}
                                <small class="text-muted">{{ $disc->docente->user->name ?? '—' }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Sem disciplinas</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Estudantes -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-1"></i> Estudantes da Turma</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Matrícula</th>
                                <th>Turno</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($turma->estudantes as $i => $est)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $est->user->name ?? 'N/A' }}</strong>
                                    <br><small class="text-muted">{{ $est->user->email ?? '' }}</small>
                                </td>
                                <td><code>{{ $est->matricula }}</code></td>
                                <td>{{ $est->turno ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $est->status == 'Ativo' ? 'success' : 'secondary' }}">
                                        {{ $est->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.estudantes.show', $est->id) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Nenhum estudante nesta turma.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection