@extends('adminlte::page')

@section('title', 'Detalhes do Docente')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chalkboard-teacher mr-1"></i> {{ $docente->user->name }}</h1>
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Perfil -->
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($docente->user->foto_perfil)
                            <img src="{{ asset('storage/' . $docente->user->foto_perfil) }}" class="profile-user-img img-fluid img-circle" style="width:100px;height:100px;object-fit:cover;">
                        @else
                            <img src="{{ asset('img/default-profile.png') }}" class="profile-user-img img-fluid img-circle" style="width:100px;height:100px;object-fit:cover;">
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $docente->user->name }}</h3>
                    <p class="text-muted text-center">{{ $docente->formacao }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item"><b>Departamento</b> <span class="float-right">{{ $docente->departamento?->nome ?? '—' }}</span></li>
                        <li class="list-group-item"><b>Turma Titular</b>
                            <span class="float-right">
                                @if($docente->turma)<strong>{{ $docente->turma->classe->nome ?? '' }} {{ $docente->turma->nome }}</strong>@else<span class="text-muted">—</span>@endif
                            </span>
                        </li>
                        <li class="list-group-item"><b>Status</b>
                            <span class="float-right badge badge-{{ $docente->status == 'Ativo' ? 'success' : 'secondary' }}">{{ $docente->status }}</span>
                        </li>
                        <li class="list-group-item"><b>Anos Exp.</b> <span class="float-right">{{ $docente->anos_experiencia ?? '—' }}</span></li>
                    </ul>
                    <a href="{{ route('admin.docentes.edit', $docente->id) }}" class="btn btn-primary btn-block mb-1">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                    <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-list mr-1"></i> Lista de Docentes
                    </a>
                </div>
            </div>

            <!-- Contato -->
            <div class="card card-info mt-3">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-address-book mr-1"></i> Contato</h3></div>
                <div class="card-body">
                    <p><i class="fas fa-envelope mr-2 text-info"></i>{{ $docente->user->email }}</p>
                    <p><i class="fas fa-phone mr-2 text-info"></i>{{ $docente->user->telefone }}</p>
                </div>
            </div>
        </div>

        <!-- Info Académica -->
        <div class="col-md-9">
            <!-- Turmas Lecionadas + Alocações -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard mr-1"></i> Turmas e Disciplinas Lecionadas</h3>
                </div>
                <div class="card-body">
                    @php
                        $turmasUnicas = $docente->disciplinas
                            ->filter(fn($d) => $d->turma)
                            ->groupBy('turma_id')
                            ->map(function ($discs, $turmaId) use ($docente) {
                                $turma = $discs->first()->turma;
                                return (object)[
                                    'turma'        => $turma,
                                    'disciplinas'  => $discs,
                                    'disciplinas_count' => $discs->count(),
                                ];
                            })
                            ->values();
                    @endphp
                    @if($turmasUnicas->count() > 0)
                        <div class="row">
                            @foreach($turmasUnicas as $item)
                                <div class="col-md-6 mb-3">
                                    <div class="card card-outline card-light">
                                        <div class="card-header bg-light">
                                            <strong>{{ $item->turma->classe->nome ?? '' }} {{ $item->turma->nome }}</strong>
                                            <span class="badge badge-info float-right">{{ $item->disciplinas_count }} disciplinas</span>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                @foreach($item->disciplinas as $disc)
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>{{ $disc->nome }}</span>
                                                        @if($disc->carga_horaria)
                                                            <small class="text-muted">{{ $disc->carga_horaria }}h</small>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-1"></i> Nenhuma turma ou disciplina atribuída.</div>
                    @endif
                </div>
            </div>

            <!-- Alocações: Turmas × Disciplinas que lecciona -->
            <div class="card card-info mt-3">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-map-marker-alt mr-1"></i> Turmas e Disciplinas Lecionadas</h3></div>
                <div class="card-body">
                    @php
                        $alocacoes = $docente->alocacoes()
                            ->with(['turma.classe', 'disciplina'])
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    @if($alocacoes->isNotEmpty())
                        <table class="table table-hover table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Turma</th>
                                    <th>Classe</th>
                                    <th>Disciplina</th>
                                    <th>Ano Lectivo</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($alocacoes as $aloc)
                                <tr>
                                    <td>{{ $aloc->turma?->nome ?? '—' }}</td>
                                    <td>{{ $aloc->turma?->classe?->nome ?? '—' }}</td>
                                    <td>{{ $aloc->disciplina?->nome ?? '—' }}</td>
                                    <td>{{ $aloc->anoLectivo?->ano ?? '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted"><i class="fas fa-info-circle mr-1"></i> Nenhuma alocação registada. Atribua uma ou mais disciplinas a turmas nas configurações do docente.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
