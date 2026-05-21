@extends('adminlte::page')

@section('title', 'Secretaria - Pré-Inscrições')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard-list mr-2"></i>Pré-Inscrições</h1>
        <a href="{{ route('secretaria.dashboard') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header"><h3 class="card-title">Candidatos</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('secretaria.pre-inscricoes.index') }}" class="form-row align-items-end mb-3">
                <div class="col-md-6">
                    <label>Nome, código ou referência</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Pesquisar...">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        @foreach(['Pendente', 'Confirmada', 'Expirada'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filtrar</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Candidato</th>
                            <th>Contacto</th>
                            <th>Classe</th>
                            <th>Referência</th>
                            <th>Status</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preInscricoes as $pre)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $pre->codigo_pre_inscricao }}</span></td>
                                <td>{{ $pre->nome_completo }}</td>
                                <td>{{ $pre->telefone }}<br><small>{{ $pre->email ?? 'Sem email' }}</small></td>
                                <td>{{ $pre->classe?->nome ?? 'N/A' }}</td>
                                <td><code>{{ $pre->referencia }}</code></td>
                                <td>
                                    <span class="badge badge-{{ $pre->status === 'Confirmada' ? 'success' : ($pre->status === 'Expirada' ? 'danger' : 'warning') }}">
                                        {{ $pre->status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('pre-inscricao.pdf', $pre->codigo_pre_inscricao) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                    <form method="POST" action="{{ route('secretaria.pre-inscricoes.status', $pre) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Confirmada">
                                        <button type="submit" class="btn btn-sm btn-success" {{ $pre->status === 'Confirmada' ? 'disabled' : '' }}>
                                            <i class="fas fa-check mr-1"></i> Confirmar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('secretaria.pre-inscricoes.status', $pre) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Expirada">
                                        <button type="submit" class="btn btn-sm btn-warning" {{ $pre->status === 'Expirada' ? 'disabled' : '' }}>
                                            <i class="fas fa-ban mr-1"></i> Expirar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Nenhuma pré-inscrição encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $preInscricoes->links() }}
        </div>
    </div>
@stop
