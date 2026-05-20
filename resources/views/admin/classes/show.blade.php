@extends('adminlte::page')
@section('title', $classe->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-school mr-2"></i>{{ $classe->nome }}</h1>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width:80px;height:80px;font-size:2rem;">
                            {{ $classe->nivel }}º
                        </div>
                    </div>
                    <h3 class="profile-username text-center">{{ $classe->nome }}</h3>
                    <p class="text-muted text-center">{{ $classe->descricao ?? 'Sem descrição' }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Turmas</b> <a class="float-right badge badge-primary">{{ $classe->turmas->count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Disciplinas</b> <a class="float-right badge badge-success">{{ $classe->disciplinas->count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Total Estudantes</b>
                            <a class="float-right badge badge-info">
                                {{ $classe->turmas->sum(function($t) { return $t->estudantes->count(); }) }}
                            </a>
                        </li>
                    </ul>
                    <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit mr-1"></i> Editar Classe
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Turmas desta Classe -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Turmas desta Classe</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.turmas.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nova Turma
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Turma</th>
                                <th>Ano Lectivo</th>
                                <th>Estudantes</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classe->turmas as $turma)
                            <tr>
                                <td><strong>{{ $turma->nome }}</strong></td>
                                <td>{{ $turma->anoLectivo->ano ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ $turma->estudantes->count() }}</span></td>
                                <td>
                                    <a href="{{ route('admin.turmas.show', $turma) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Nenhuma turma criada para esta classe.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Disciplinas desta Classe -->
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title"><i class="fas fa-book mr-1"></i> Disciplinas desta Classe</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.disciplinas.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Nova Disciplina
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Disciplina</th>
                                <th>Docente</th>
                                <th>Carga Horária</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classe->disciplinas as $disciplina)
                            <tr>
                                <td><strong>{{ $disciplina->nome }}</strong></td>
                                <td>{{ $disciplina->docente->user->name ?? 'Não atribuído' }}</td>
                                <td>{{ $disciplina->carga_horaria ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">Nenhuma disciplina cadastrada para esta classe.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
