@extends('adminlte::page')

@section('title', 'Gestão de Docentes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-chalkboard-teacher mr-1"></i> Gestão de Docentes</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Docentes</li>
            </ol>
        </div>
        <a href="{{ route('admin.docentes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Novo Docente
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.docentes.index') }}" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Nome ou departamento..." value="{{ request('search') }}">
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="turma_id" class="form-control">
                        <option value="">— Todas as Turmas —</option>
                        @foreach($turmas as $id => $label)
                            <option value="{{ $id }}" {{ request('turma_id') == $id ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search mr-1"></i> Filtrar</button>
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary mb-2 ml-1"><i class="fas fa-eraser mr-1"></i> Limpar</a>
            </form>
        </div>
    </div>

    <!-- Lista -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chalkboard-teacher mr-2"></i> Docentes ({{ $docentes->total() }})</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Departamento</th>
                        <th>Turma Titular</th>
                        <th>Formação</th>
                        <th>Turmas Lecionadas</th>
                        <th>Status</th>
                        <th style="width:160px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($docentes as $i => $d)
                    @php
                        $alocadas = $d->disciplinas->pluck('turma_id')->unique()->count();
                    @endphp
                    <tr>
                        <td>{{ $docentes->firstItem() + $i }}</td>
                        <td>
                            @if($d->user->foto_perfil)
                                <img src="{{ asset('storage/' . $d->user->foto_perfil) }}" class="img-circle" style="width:40px;height:40px;object-fit:cover;">
                            @else
                                <img src="{{ asset('img/default-profile.png') }}" class="img-circle" style="width:40px;height:40px;object-fit:cover;">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $d->user->name }}</strong>
                            <br><small class="text-muted">{{ $d->user->email }}</small>
                        </td>
                        <td>{{ $d->departamento?->nome ?? '—' }}</td>
                        <td>
                            @if($d->turma)
                                {{ $d->turma->classe->nome ?? '' }} {{ $d->turma->nome }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $d->formacao }}</td>
                        <td>
                            @php
                                $disciplinasCount = $d->disciplinas->count();
                                $turmasCount = $d->disciplinas->pluck('turma_id')->unique()->count();
                            @endphp
                            <span class="badge badge-info">{{ $disciplinasCount }} disciplinas</span>
                            <br><small class="text-muted">{{ $turmasCount }} turmas</small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $d->status == 'Ativo' ? 'success' : 'secondary' }}">{{ $d->status }}</span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <a href="{{ route('admin.docentes.show', $d->id) }}" class="btn btn-xs btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.docentes.edit', $d->id) }}" class="btn btn-xs btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Nenhum docente encontrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $docentes->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@stop
