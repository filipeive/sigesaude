@extends('adminlte::page')

@section('title', 'Relatórios Financeiros')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-chart-line text-primary mr-2"></i> Relatórios Financeiros</h1>
        <div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#gerarRelatorioModal">
                <i class="fas fa-file-export mr-1"></i> Gerar Novo Relatório
            </button>
            <a href="{{ route('admin.financeiro.index') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Cards de Resumo -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($saldo_mensal ?? 0, 2, ',', '.') }}</h3>
                    <p>Saldo deste Mês</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#detalhesSaldoModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($entradas_mensal ?? 0, 2, ',', '.') }}</h3>
                    <p>Entradas deste Mês</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#detalheEntradasModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($saidas_mensal ?? 0, 2, ',', '.') }}</h3>
                    <p>Saídas deste Mês</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#detalheSaidasModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $crescimento_percentual ?? 0 }}%</h3>
                    <p>Crescimento vs. Mês Anterior</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#detalheCrescimentoModal">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Evolução -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Evolução Financeira</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-calendar"></i> Período
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item periodo-filtro" data-periodo="7">Últimos 7 dias</a>
                                <a href="#" class="dropdown-item periodo-filtro" data-periodo="30">Últimos 30 dias</a>
                                <a href="#" class="dropdown-item periodo-filtro" data-periodo="90">Últimos 90 dias</a>
                                <a href="#" class="dropdown-item periodo-filtro" data-periodo="365">Último ano</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="graficoEvolucao" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categorias -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribuição por Categoria</h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#entradas-tab" data-toggle="tab">Entradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#saidas-tab" data-toggle="tab">Saídas</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane active" id="entradas-tab">
                            <canvas id="graficoCategoriasEntradas" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="tab-pane" id="saidas-tab">
                            <canvas id="graficoCategoriasSaidas" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Relatórios -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Relatórios Salvos</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar relatório...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>Relatório</th>
                        <th>Período</th>
                        <th>Data de Geração</th>
                        <th>Gerado por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($relatorios as $relatorio)
                        <tr>
                            <td>{{ $relatorio->titulo }}</td>
                            <td>{{ $relatorio->periodo }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($relatorio->created_at)) }}</td>
                            <td>{{ $relatorio->usuario->name ?? 'Sistema' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.financeiro.relatorios.show', $relatorio->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.financeiro.relatorios.download', $relatorio->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-relatorio" data-toggle="modal" data-target="#deleteRelatorioModal" data-id="{{ $relatorio->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum relatório encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $relatorios->links() ?? '' }}
            </div>
        </div>
    </div>

    <!-- Modal Gerar Relatório -->
    <div class="modal fade" id="gerarRelatorioModal" tabindex="-1" role="dialog" aria-labelledby="gerarRelatorioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="gerarRelatorioModalLabel">Gerar Novo Relatório</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.financeiro.relatorios.gerar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titulo">Título do Relatório</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Período</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="date_range" name="periodo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Transação</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="incluir_entradas" name="incluir_entradas" value="1" checked>
                                        <label class="form-check-label" for="incluir_entradas">Incluir Entradas</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="incluir_saidas" name="incluir_saidas" value="1" checked>
                                        <label class="form-check-label" for="incluir_saidas">Incluir Saídas</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categorias</label>
                                    <select class="form-control select2" multiple="multiple" data-placeholder="Selecione as categorias" name="categorias[]">
                                        <optgroup label="Entradas">
                                            <option value="vendas">Vendas</option>
                                            <option value="servicos">Serviços</option>
                                            <option value="investimentos">Investimentos</option>
                                            <option value="outras_entradas">Outras entradas</option>
                                        </optgroup>
                                        <optgroup label="Saídas">
                                            <option value="fornecedores">Fornecedores</option>
                                            <option value="funcionarios">Funcionários</option>
                                            <option value="impostos">Impostos</option>
                                            <option value="infraestrutura">Infraestrutura</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="outras_saidas">Outras saídas</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Incluir no Relatório</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="incluir_resumo" name="incluir_resumo" value="1" checked>
                                        <label class="form-check-label" for="incluir_resumo">Resumo Financeiro</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="incluir_graficos" name="incluir_graficos" value="1" checked>
                                        <label class="form-check-label" for="incluir_graficos">Gráficos</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="incluir_detalhes" name="incluir_detalhes" value="1" checked>
                                        <label class="form-check-label" for="incluir_detalhes">Detalhes das Transações</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Formato</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="formato" id="formato_pdf" value="pdf" checked>
                                        <label class="form-check-label" for="formato_pdf">PDF</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="formato" id="formato_excel" value="excel">
                                        <label class="form-check-label" for="formato_excel">Excel</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="formato" id="formato_csv" value="csv">
                                        <label class="form-check-label" for="formato_csv">CSV</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="observacoes">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Gerar Relatório</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Exclusão de Relatório -->
    <div class="modal fade" id="deleteRelatorioModal" tabindex="-1" role="dialog" aria-labelledby="deleteRelatorioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="deleteRelatorioModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir este relatório? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteRelatorioForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalhes -->
    <div class="modal fade" id="detalhesSaldoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Detalhes do Saldo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Saldo Anterior</th>
                                <td>{{ number_format($saldo_anterior ?? 0, 2, ',', '.') }} MZN</td>
                            </tr>
                            <tr>
                                <th>Entradas do Mês</th>
                                <td>{{ number_format($entradas_mensal ?? 0, 2, ',', '.') }} MZN</td>
                            </tr>
                            <tr>
                                <th>Saídas do Mês</th>
                                <td>{{ number_format($saidas_mensal ?? 0, 2, ',', '.') }} MZN</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Saldo Atual</th>
                                <td>{{ number_format($saldo_mensal ?? 0, 2, ',', '.') }} MZN</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <style>
        .small-box .icon {
            font-size: 70px;
            top: 5px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #007bff;
            border-color: #006fe6;
            color: #fff;
            padding: 0 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            // DateRangePicker
            $('#date_range').daterangepicker({
                startDate: moment().subtract(30, 'days'),
                endDate: moment(),
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro']
                }
            });
            
            // Select2
            $('.select2').select2({
                theme: 'default',
                placeholder: 'Selecione as categorias',
                allowClear: true
            });
            
            // Modal de exclusão de relatório
            $('.delete-relatorio').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('admin.financeiro.relatorios.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#deleteRelatorioForm').attr('action', url);
            });
            
            // Gráfico de Evolução Financeira
            var ctxEvolucao = document.getElementById('graficoEvolucao').getContext('2d');
            var chartEvolucao = new Chart(ctxEvolucao, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    datasets: [{
                        label: 'Entradas',
                        data: [12000, 15000, 18000, 14000, 16000, 19000, 22000, 25000, 23000, 26000, 24000, 28000],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Saídas',
                        data: [10000, 13000, 15000, 12000, 14000, 16000, 18000, 20000, 19000, 21000, 20000, 23000],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('pt-MZ', { 
                                            style: 'currency', 
                                            currency: 'MZN' 
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('pt-MZ') + ' MZN';
                                }
                            }
                        }
                    }
                }
            });
            
            // Gráfico de Categorias - Entradas
            var ctxCatEntradas = document.getElementById('graficoCategoriasEntradas').getContext('2d');
            var chartCatEntradas = new Chart(ctxCatEntradas, {
                type: 'doughnut',
                data: {
                    labels: ['Vendas', 'Serviços', 'Investimentos', 'Outras'],
                    datasets: [{
                        data: [12000, 8000, 3000, 2000],
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.8)',
                            'rgba(46, 204, 113, 0.8)',
                            'rgba(155, 89, 182, 0.8)',
                            'rgba(241, 196, 15, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('pt-MZ', { 
                                            style: 'currency', 
                                            currency: 'MZN' 
                                        }).format(context.raw);
                                        
                                        let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = Math.round((context.parsed * 100) / sum) + '%';
                                        label += ' (' + percentage + ')';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            
            // Gráfico de Categorias - Saídas
            var ctxCatSaidas = document.getElementById('graficoCategoriasSaidas').getContext('2d');
            var chartCatSaidas = new Chart(ctxCatSaidas, {
                type: 'doughnut',
                data: {
                    labels: ['Fornecedores', 'Funcionários', 'Impostos', 'Infraestrutura', 'Marketing', 'Outras'],
                    datasets: [{
                        data: [5000, 8000, 3000, 4000, 2000, 1000],
                        backgroundColor: [
                            'rgba(231, 76, 60, 0.8)',
                            'rgba(230, 126, 34, 0.8)',
                            'rgba(243, 156, 18, 0.8)',
                            'rgba(26, 188, 156, 0.8)',
                            'rgba(52, 152, 219, 0.8)',
                            'rgba(149, 165, 166, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('pt-MZ', { 
                                            style: 'currency', 
                                            currency: 'MZN' 
                                        }).format(context.raw);
                                        
                                        let sum = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = Math.round((context.parsed * 100) / sum) + '%';
                                        label += ' (' + percentage + ')';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            
            // Filtro de período para o gráfico de evolução
            $('.periodo-filtro').click(function(e) {
                e.preventDefault();
                var dias = $(this).data('periodo');
                // Aqui você incluiria a lógica para atualizar o gráfico com o período selecionado
                // usando uma requisição AJAX para buscar os dados do servidor
                
                // Exemplo de como seria a chamada:
                $.ajax({
                    url: '{{ route("admin.financeiro.ajax.grafico") }}',
                    data: { periodo: dias },
                    success: function(response) {
                        // Atualizar os dados do gráfico
                        chartEvolucao.data.labels = response.labels;
                        chartEvolucao.data.datasets[0].data = response.entradas;
                        chartEvolucao.data.datasets[1].data = response.saidas;
                        chartEvolucao.update();
                    }
                });
            });
        });
    </script>
@stop