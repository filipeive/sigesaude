@extends('adminlte::page')

@section('title', 'Secretaria - Pagamentos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-money-bill-wave mr-2"></i>Pagamentos</h1>
        <div>
            <a href="{{ route('secretaria.pagamentos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Novo Pagamento
            </a>
            <a href="{{ route('secretaria.dashboard') }}" class="btn btn-default">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Acompanhamento de Pagamentos</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('secretaria.pagamentos.index') }}" class="form-row align-items-end mb-3">
                <div class="col-md-4">
                    <label>Estudante ou referência</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Pesquisar...">
                </div>
                <div class="col-md-3">
                    <label>Categoria</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todas</option>
                        @foreach(['propina' => 'Propina', 'matricula' => 'Matrícula', 'taxa' => 'Taxa', 'inscricao' => 'Inscrição'] as $value => $label)
                            <option value="{{ $value }}" {{ request('tipo') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Todos</option>
                        @foreach(['pendente' => 'Pendente', 'pago' => 'Pago', 'cancelado' => 'Cancelado'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Estudante</th>
                            <th>Turma</th>
                            <th>Categoria</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagamentos as $pagamento)
                            <tr>
                                <td><code>{{ $pagamento->referencia }}</code></td>
                                <td>
                                    <strong>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $pagamento->estudante?->matricula ?? '' }}</small>
                                </td>
                                <td>{{ $pagamento->turma?->nome ?? $pagamento->estudante?->turma?->nome ?? 'N/A' }}</td>
                                <td>{{ ucfirst($pagamento->tipo ?? 'N/A') }}</td>
                                <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                <td>{{ $pagamento->data_vencimento ? \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $pagamento->status === 'pago' ? 'success' : ($pagamento->status === 'cancelado' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($pagamento->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('secretaria.pagamentos.show', $pagamento) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye mr-1"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Nenhum pagamento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pagamentos->links() }}
        </div>
    </div>
@stop
