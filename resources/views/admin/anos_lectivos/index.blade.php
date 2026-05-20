@extends('adminlte::page')
@section('title', 'Anos Lectivos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-calendar-alt mr-2"></i>Gestão de Anos Lectivos</h1>
        <a href="{{ route('admin.anos-lectivos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Novo Ano Lectivo
        </a>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Anos Lectivos Cadastrados</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th width="60">ID</th>
                        <th>Ano</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anosLectivos as $ano)
                        <tr>
                            <td>{{ $ano->id }}</td>
                            <td><strong>{{ $ano->ano }}</strong></td>
                            <td>
                                @if($ano->status == 'Ativo')
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $ano->created_at ? $ano->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.anos-lectivos.edit', $ano) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.anos-lectivos.destroy', $ano) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Tem certeza que deseja remover este ano lectivo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle fa-2x d-block mb-2"></i>
                                Nenhum ano lectivo cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($anosLectivos->hasPages())
        <div class="card-footer">
            {{ $anosLectivos->links() }}
        </div>
        @endif
    </div>
@endsection
