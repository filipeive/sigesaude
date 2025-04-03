@extends('adminlte::page')

@section('title', 'Gestão de Pagamentos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-money-bill-wave mr-2"></i> Gestão de Pagamentos
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pagamentos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Filtros Avançados -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i> Filtros
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('admin.pagamentos.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select2">
                                    <option value="">Todos</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Estudante</label>
                                <select name="estudante" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach($estudantes as $estudante)
                                        <option value="{{ $estudante->user->name }}" {{ request('estudante') == $estudante->user->name ? 'selected' : '' }}>
                                            {{ $estudante->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Período</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="dateRangePicker" name="date_range">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ordenar por</label>
                                <div class="input-group">
                                    <select name="ordem" class="form-control">
                                        <option value="data_vencimento" {{ request('ordem') == 'data_vencimento' ? 'selected' : '' }}>Data Vencimento</option>
                                        <option value="valor" {{ request('ordem') == 'valor' ? 'selected' : '' }}>Valor</option>
                                        <option value="created_at" {{ request('ordem') == 'created_at' ? 'selected' : '' }}>Data Criação</option>
                                    </select>
                                    <select name="direcao" class="form-control">
                                        <option value="desc" {{ request('direcao') == 'desc' ? 'selected' : '' }}>Decrescente</option>
                                        <option value="asc" {{ request('direcao') == 'asc' ? 'selected' : '' }}>Crescente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-default">
                                <i class="fas fa-undo mr-1"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Resumo Estatístico -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="far fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pagamentos Pendentes</span>
                        <span class="info-box-number">
                            {{ number_format(Pagamento::where('status', 'pendente')->count(), 0, ',', '.') }}
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ Pagamento::count() > 0 ? (Pagamento::where('status', 'pendente')->count() / Pagamento::count() * 100) : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ Pagamento::count() > 0 ? round(Pagamento::where('status', 'pendente')->count() / Pagamento::count() * 100, 2) : 0 }}% do total
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="far fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pagamentos Realizados</span>
                        <span class="info-box-number">
                            {{ number_format(Pagamento::where('status', 'pago')->count(), 0, ',', '.') }}
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ Pagamento::count() > 0 ? (Pagamento::where('status', 'pago')->count() / Pagamento::count() * 100) : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ Pagamento::count() > 0 ? round(Pagamento::where('status', 'pago')->count() / Pagamento::count() * 100, 2) : 0 }}% do total
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-box bg-gradient-danger">
                    <span class="info-box-icon"><i class="far fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pagamentos Cancelados</span>
                        <span class="info-box-number">
                            {{ number_format(Pagamento::where('status', 'cancelado')->count(), 0, ',', '.') }}
                        </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ Pagamento::count() > 0 ? (Pagamento::where('status', 'cancelado')->count() / Pagamento::count() * 100) : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ Pagamento::count() > 0 ? round(Pagamento::where('status', 'cancelado')->count() / Pagamento::count() * 100, 2) : 0 }}% do total
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Lista de Pagamentos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Pagamentos</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pagamentos.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Novo Pagamento
                    </a>
                    <a href="{{ route('admin.pagamentos.exportar', request()->query()) }}" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-file-export mr-1"></i> Exportar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pagamentosTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Referência</th>
                                <th>Estudante</th>
                                <th>Valor (MZN)</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagamentos as $pagamento)
                                <tr>
                                    <td>{{ $pagamento->referencia }}</td>
                                    <td>{{ $pagamento->estudante->user->name }}</td>
                                    <td class="text-right">{{ number_format($pagamento->valor, 2, ',', '.') }}</td>
                                    <td>{{ $pagamento->data_vencimento->format('d/m/Y') }}</td>
                                    <td>
                                        @if($pagamento->status == 'pago')
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle mr-1"></i> Pago
                                            </span>
                                        @elseif($pagamento->status == 'pendente')
                                            <span class="badge badge-{{ $pagamento->data_vencimento->isPast() ? 'danger' : 'warning' }}">
                                                <i class="fas fa-clock mr-1"></i> {{ $pagamento->data_vencimento->isPast() ? 'Vencido' : 'Pendente' }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times-circle mr-1"></i> Cancelado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.pagamentos.show', $pagamento->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pagamentos.edit', $pagamento->id) }}" 
                                               class="btn btn-primary btn-sm" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Excluir"
                                                    data-toggle="modal" 
                                                    data-target="#deleteModal{{ $pagamento->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal de Confirmação de Exclusão -->
                                <div class="modal fade" id="deleteModal{{ $pagamento->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title">Confirmar Exclusão</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Tem certeza que deseja excluir o pagamento <strong>{{ $pagamento->referencia }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('admin.pagamentos.destroy', $pagamento->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total:</th>
                                <th class="text-right">{{ number_format($pagamentos->sum('valor'), 2, ',', '.') }}</th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                {{ $pagamentos->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .info-box {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.02);
        }
        .badge {
            font-size: 0.85em;
            font-weight: 500;
            padding: 0.5em 0.8em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px);
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('vendor/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "Selecione uma opção",
                allowClear: true
            });
            
            // Inicializar Date Range Picker
            $('#dateRangePicker').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                    fromLabel: 'De',
                    toLabel: 'Até',
                    customRangeLabel: 'Personalizado',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    firstDay: 1
                },
                opens: 'right',
                autoUpdateInput: false,
                ranges: {
                    'Hoje': [moment(), moment()],
                    'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                    'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                    'Mês Passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
            
            $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                $('#filterForm').submit();
            });
            
            $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#filterForm').submit();
            });
            
            // Configurar DataTable
            $('#pagamentosTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "{{ asset('vendor/datatables/pt-PT.json') }}"
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                paging: false,
                searching: false,
                info: false,
                order: [],
                columnDefs: [
                    { orderable: false, targets: [5] },
                    { className: "text-right", targets: [2] },
                    { width: "15%", targets: [3,4,5] }
                ]
            });
            
            // Atualizar contadores ao clicar nos cards
            $('.info-box').click(function() {
                const status = $(this).find('.info-box-text').text().toLowerCase().includes('pendente') ? 'pendente' : 
                               $(this).find('.info-box-text').text().toLowerCase().includes('realizado') ? 'pago' : 'cancelado';
                
                $('select[name="status"]').val(status).trigger('change');
                $('#filterForm').submit();
            });
        });
    </script>
@stop