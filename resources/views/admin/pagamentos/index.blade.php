@extends('adminlte::page')

@section('title', 'Gestão de Pagamentos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-money-bill-wave text-primary"></i> Gestão de Pagamentos</h1>
        <a href="{{ route('admin.pagamentos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Novo Pagamento
        </a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pagamentos.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estudante</label>
                            <input type="text" name="estudante" class="form-control" value="{{ request('estudante') }}"
                                placeholder="Nome do estudante">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente
                                </option>
                                <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data Inicial</label>
                            <div class="input-group date" id="data_inicio_container" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#data_inicio_container" name="data_inicio"
                                    value="{{ request('data_inicio') }}" placeholder="dd/mm/aaaa">
                                <div class="input-group-append" data-target="#data_inicio_container"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data Final</label>
                            <div class="input-group date" id="data_fim_container" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#data_fim_container" name="data_fim" value="{{ request('data_fim') }}"
                                    placeholder="dd/mm/aaaa">
                                <div class="input-group-append" data-target="#data_fim_container"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-eraser mr-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.pagamentos.exportar', request()->query()) }}" class="btn btn-success">
                            Exportar CSV
                        </a>                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Lista de Pagamentos</h3>
            <div class="card-tools">
                <span class="badge bg-primary">Total: {{ $pagamentos->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a
                                    href="{{ route('admin.pagamentos.index', array_merge(request()->all(), ['ordem' => 'referencia', 'direcao' => request('ordem') == 'referencia' && request('direcao') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Referência
                                    @if (request('ordem') == 'referencia')
                                        <i class="fas fa-sort-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Estudante</th>
                            <th>
                                <a
                                    href="{{ route('admin.pagamentos.index', array_merge(request()->all(), ['ordem' => 'valor', 'direcao' => request('ordem') == 'valor' && request('direcao') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Valor
                                    @if (request('ordem') == 'valor')
                                        <i class="fas fa-sort-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a
                                    href="{{ route('admin.pagamentos.index', array_merge(request()->all(), ['ordem' => 'data_vencimento', 'direcao' => request('ordem') == 'data_vencimento' && request('direcao') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Vencimento
                                    @if (request('ordem') == 'data_vencimento' || !request('ordem'))
                                        <i class="fas fa-sort-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pagamentos as $pagamento)
                            <tr>
                                <td>{{ $pagamento->referencia }}</td>
                                <td>{{ $pagamento->estudante->user->name }}</td>
                                <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                <td>{{ Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $pagamento->status == 'pago' ? 'success' : ($pagamento->status == 'pendente' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($pagamento->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pagamentos.show', $pagamento) }}"
                                        class="btn btn-sm btn-info" title="Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pagamentos.edit', $pagamento->id) }}"
                                        class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.pagamentos.destroy', $pagamento) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este pagamento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Nenhum pagamento encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $pagamentos->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script>
        $(function() {
            $('#data_inicio_container').datetimepicker({
                format: 'DD/MM/YYYY',
                locale: 'pt'
            });
            $('#data_fim_container').datetimepicker({
                format: 'DD/MM/YYYY',
                locale: 'pt'
            });
        });
    </script>
@endsection
