@extends('adminlte::page')
@section('title', $disciplina->nome)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-book mr-2"></i>{{ $disciplina->nome }}</h1>
        <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Informações da Disciplina</h3></div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nome:</dt>
                        <dd class="col-sm-8">{{ $disciplina->nome }}</dd>

                        <dt class="col-sm-4">Classe:</dt>
                        <dd class="col-sm-8">
                            @if($disciplina->classe)
                                <a href="{{ route('admin.classes.show', $disciplina->classe) }}">{{ $disciplina->classe->nome }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Docente:</dt>
                        <dd class="col-sm-8">{{ $disciplina->docente->user->name ?? 'Não atribuído' }}</dd>

                        <dt class="col-sm-4">Nível:</dt>
                        <dd class="col-sm-8">{{ $disciplina->nivel->nome ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Carga Horária:</dt>
                        <dd class="col-sm-8">{{ $disciplina->carga_horaria ?? 'N/A' }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.disciplinas.edit', $disciplina->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Turmas que estudam esta disciplina</h3></div>
                <div class="card-body p-0">
                    @php
                        $turmasRelacionadas = $disciplina->classe ? $disciplina->classe->turmas()->with('anoLectivo')->withCount('estudantes')->get() : collect();
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($turmasRelacionadas as $turma)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $turma->nome }}</strong>
                                    <br><small class="text-muted">{{ $turma->anoLectivo->ano ?? '' }}</small>
                                </div>
                                <span class="badge badge-info badge-pill">{{ $turma->estudantes_count }} alunos</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Nenhuma turma associada a esta classe.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
