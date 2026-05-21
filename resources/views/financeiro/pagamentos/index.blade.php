@extends('adminlte::page')

@section('title', 'Financeiro - Pagamentos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-money-bill-wave mr-2"></i>Pagamentos</h1>
        <a href="{{ route('financeiro.dashboard') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Consulta Financeira de Pagamentos</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('financeiro.pagamentos.index') }}" class="form-row align-items-end mb-3">
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagamentos as $pagamento)
                            <tr>
                                <td><code>{{ $pagamento->referencia }}</code></td>
                                <td>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</td>
                                <td>{{ $pagamento->turma?->nome ?? $pagamento->estudante?->turma?->nome ?? 'N/A' }}</td>
                                <td>{{ ucfirst($pagamento->tipo ?? 'N/A') }}</td>
                                <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                <td>{{ $pagamento->data_vencimento ? $pagamento->data_vencimento->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $pagamento->status === 'pago' ? 'success' : ($pagamento->status === 'cancelado' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($pagamento->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Nenhum pagamento encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $pagamentos->links() }}
        </div>
    </div>
@stop
