@extends('adminlte::page')

@section('title', 'Gestão de Pagamentos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-money-bill-wave text-primary"></i> Gestão de Pagamentos</h1>
        <a href="{{ route('admin.pagamentos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Novo Pagamento
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

    <!-- ── Card Estatísticas ── -->
    <div class="row mb-3">
        <div class="col-md-4 col-sm-12">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalPendentes }}</h3>
                    <p>Pendentes</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalVencidas }}</h3>
                    <p>Vencidas</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalPagos }}</h3>
                    <p>Pagas</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
    </div>

    <!-- ── Instruções de Pagamento ── -->
    <div class="card card-outline card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Instruções de Pagamento</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info mb-0" style="border-left: 4px solid #0056b3;">
                        <strong><i class="fas fa-building mr-1"></i> Dados Bancários</strong>
                        <hr style="margin: 8px 0;">
                        <p class="mb-1"><strong>Entidade:</strong> <code style="font-size:1.2em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code></p>
                        <p class="mb-0"><strong>Como pagar:</strong> Em qualquer ATM ou Internet Banking → selecione
                            <em>Pagamentos &gt; Pagamento de Serviços</em> → insira a entidade e a referência do pagamento.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning mb-0" style="border-left: 4px solid #f57f17;">
                        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Avisos Importantes</strong>
                        <hr style="margin: 8px 0;">
                        <ul class="mb-0" style="padding-left: 1.2rem;">
                            <li>As <strong>propinas mensais</strong> devem ser pagas até ao dia <strong>10</strong> de cada mês.</li>
                            <li>Cada pagamento tem uma <strong>referência única</strong> — não partilhe com terceiros.</li>
                            <li>Após pagamento, envie o comprovativo pela plataforma para confirmação.</li>
                            <li>Pagamentos vencidos podem gerar multas adicionais.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Filtros ── -->
    <div class="card card-outline card-primary mb-3">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estudante</label>
                            <input type="text" name="estudante" class="form-control" value="{{ request('estudante') }}" placeholder="Nome...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Turma</label>
                            <select name="turma_id" class="form-control">
                                <option value="">— Todas —</option>
                                @foreach($turmas as $t)
                                    <option value="{{ $t->id }}" {{ request('turma_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->classe->nome ?? '' }} {{ $t->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Categoria</label>
                            <select name="tipo" class="form-control">
                                <option value="">— Todas —</option>
                                @foreach($categorias as $k => $v)
                                    <option value="{{ $k }}" {{ request('tipo') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">— Todos —</option>
                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Data Inicial</label>
                            <div class="input-group date" id="data_inicio_container" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#data_inicio_container"
                                    name="data_inicio" value="{{ request('data_inicio') }}" placeholder="dd/mm/aaaa">
                                <div class="input-group-append" data-target="#data_inicio_container" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Data Final</label>
                            <div class="input-group date" id="data_fim_container" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#data_fim_container"
                                    name="data_fim" value="{{ request('data_fim') }}" placeholder="dd/mm/aaaa">
                                <div class="input-group-append" data-target="#data_fim_container" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search mr-1"></i> Filtrar</button>
                        <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary"><i class="fas fa-eraser mr-1"></i> Limpar</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.pagamentos.exportar', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-csv mr-1"></i> Exportar CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Lista ── -->
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
                            <th>Referência</th>
                            <th>Estudante</th>
                            <th>Turma</th>
                            <th>Categoria</th>
                            <th>
                                <a href="{{ route('admin.pagamentos.index', array_merge(request()->all(), ['ordem' => 'valor', 'direcao' => request('ordem') == 'valor' && request('direcao') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Valor
                                    @if (request('ordem') == 'valor')<i class="fas fa-sort-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>@else<i class="fas fa-sort"></i>@endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('admin.pagamentos.index', array_merge(request()->all(), ['ordem' => 'data_vencimento', 'direcao' => request('ordem') == 'data_vencimento' && request('direcao') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Vencimento
                                    @if (!request('ordem') || request('ordem') == 'data_vencimento')<i class="fas fa-sort-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>@else<i class="fas fa-sort"></i>@endif
                                </a>
                            </th>
                            <th>Status</th>
                            <th style="width:200px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($pagamentos as $p)
                        <tr>
                            <td><code>{{ $p->referencia }}</code></td>
                            <td>
                                <strong>{{ $p->estudante->user->name ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $p->estudante?->matricula ?? '' }}</small>
                            </td>
                            <td>{{ $p->turma?->nome ?? $p->estudante?->turma?->nome ?? '—' }}</td>
                            <td>
                                @php $tipos = ['propina'=>'Propina Mensal','matricula'=>'Matrícula','taxa'=>'Taxa / Outros','inscricao'=>'Inscrição']; @endphp
                                <span class="badge badge-{{ $p->tipo == 'propina' ? 'primary' : ($p->tipo == 'matricula' ? 'success' : 'secondary') }}">
                                    {{ $tipos[$p->tipo] ?? $p->tipo ?? '—' }}
                                </span>
                            </td>
                            <td>{{ number_format($p->valor, 2, ',', '.') }} MZN</td>
                            <td>{{ Carbon\Carbon::parse($p->data_vencimento)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $p->status == 'pago' ? 'success' : ($p->status == 'pendente' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pagamentos.show', $p) }}" class="btn btn-xs btn-info" title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pagamentos.edit', $p->id) }}" class="btn btn-xs btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($p->status == 'pago')
                                    <a href="{{ route('admin.pagamentos.recibo', $p) }}" class="btn btn-xs btn-success" title="Recibo PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.pagamentos.destroy', $p) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Excluir pagamento {{ $p->referencia }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">Nenhum pagamento encontrado.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $pagamentos->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function () {
            $('#data_inicio_container').datetimepicker({ format: 'DD/MM/YYYY', locale: 'pt' });
            $('#data_fim_container').datetimepicker({ format: 'DD/MM/YYYY', locale: 'pt' });
        });
    </script>
@endsection
