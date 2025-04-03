@extends('adminlte::page')

@section('title', 'Situação Financeira')

@section('content_header')
    <div class="row justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">
                <i class="fas fa-wallet mr-2 text-primary"></i>Situação Financeira
            </h1>
            <small class="text-muted">Gerencie seus pagamentos e acompanhe seu histórico</small>
        </div>
        <a href="#" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-university mr-1"></i> Referências Bancárias
        </a>
        </div>
@stop

@section('content')
    <!-- Notificações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <!-- Cards principais com resumo financeiro -->
    <div class="row">
        <div class="col-lg-4">
            <div class="info-box bg-danger shadow-sm">
                <span class="info-box-icon bg-danger-dark"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Dívida Atual</span>
                    <span class="info-box-number">{{ number_format($totalPendente, 2, '.', ',') }} MZN</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $totalPendente > 0 ? 100 : 0 }}%"></div>
                    </div>
                    <span class="progress-description">
                        @if ($totalPendente > 0)
                            <i class="fas fa-clock mr-1"></i>Pagamento pendente
                        @else
                            <i class="fas fa-check-circle mr-1"></i>Tudo em dia
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-box bg-success shadow-sm">
                <span class="info-box-icon bg-success-dark"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pago</span>
                    <span class="info-box-number">{{ number_format($totalPago, 2, '.', ',') }} MZN</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $totalPago > 0 ? 100 : 0 }}%"></div>
                    </div>
                    <span class="progress-description">
                        Desde {{ date('Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-box bg-warning shadow-sm">
                <span class="info-box-icon bg-warning-dark"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Próximo Vencimento</span>
                    <span class="info-box-number">
                        @if ($proximoVencimento)
                            {{ date('d/m/Y', strtotime($proximoVencimento->data_vencimento)) }}
                        @else
                            Nenhum vencimento próximo
                        @endif
                    </span>
                    @if ($proximoVencimento)
                        <div class="progress">
                            @php
                                $diasRestantes = max(
                                    0,
                                    (strtotime($proximoVencimento->data_vencimento) - time()) / 86400,
                                );
                                $percentual = min(100, max(0, ($diasRestantes / 30) * 100));
                            @endphp
                            <div class="progress-bar bg-white" style="width: {{ $percentual }}%"></div>
                        </div>
                        <span class="progress-description">
                            @if ($diasRestantes <= 0)
                                <i class="fas fa-exclamation-triangle mr-1"></i>Vencido
                            @elseif ($diasRestantes <= 5)
                                <i class="fas fa-clock mr-1"></i>Vence em {{ ceil($diasRestantes) }} dias
                            @else
                                <i class="fas fa-calendar-check mr-1"></i>{{ ceil($diasRestantes) }} dias restantes
                            @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs para organização de conteúdo -->
     
    <div class="card shadow-sm mt-4">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="financialTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active py-3" id="payments-tab" data-toggle="tab" href="#payments" role="tab">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        <span class="font-weight-bold">Propinas Pendentes</span>
                        @if ($propinas->where('status', 'pendente')->count() > 0)
                            <span
                                class="badge badge-danger ml-2">{{ $propinas->where('status', 'pendente')->count() }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" id="history-tab" data-toggle="tab" href="#history" role="tab">
                        <i class="fas fa-history mr-2"></i>
                        <span class="font-weight-bold">Histórico</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-3" id="payment-methods-tab" data-toggle="tab" href="#payment-methods"
                        role="tab">
                        <i class="fas fa-credit-card mr-2"></i>
                        <span class="font-weight-bold">Métodos</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="financialTabsContent">
                <!-- Tab: Propinas Pendentes -->
                <div class="tab-pane fade show active" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    <!-- Filtros e busca -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ano_letivo" class="font-weight-bold text-primary">
                                    <i class="fas fa-calendar-alt mr-1"></i>Ano Letivo:
                                </label>
                                <select name="ano_letivo" id="ano_letivo" class="form-control select2"
                                    style="width: 100%;">
                                    @foreach ($anosLetivos as $ano)
                                        <option value="{{ $ano->id }}"
                                            {{ $anoLetivo && $ano->id == $anoLetivo->id ? 'selected' : '' }}>
                                            {{ $ano->ano }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status_filter" class="font-weight-bold text-primary">
                                    <i class="fas fa-filter mr-1"></i>Status:
                                </label>
                                <select id="status_filter" class="form-control select2" style="width: 100%;">
                                    <option value="todos">Todos</option>
                                    <option value="pendente">Pendentes</option>
                                    <option value="vencido">Vencidos</option>
                                    <option value="pago">Pagos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">
                                    <i class="fas fa-search mr-1"></i>Buscar Propina:
                                </label>
                                <div class="input-group">
                                    <input type="text" id="table_search" class="form-control"
                                        placeholder="Digite a descrição ou referência...">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerta com instruções de pagamento -->
                    <div class="alert alert-info shadow-sm" role="alert">
                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Como pagar suas propinas:</h5>
                                <p>Para proceder com o pagamento usando referências, dirija-se a qualquer ATM dos Bancos
                                    indicados, procure pela opção <strong>PAGAMENTOS</strong> e selecione depois a opção
                                    <strong>PAGAMENTO DE SERVIÇOS</strong>.
                                </p>
                                <p class="mb-0">Digite a <strong>ENTIDADE</strong>, <strong>REFERÊNCIA</strong> e o
                                    <strong>VALOR</strong> para cada pagamento conforme a sua ficha de pagamentos.
                                    Poderá também fazer o pagamento através da <strong>INTERNET</strong>,
                                    <strong>TELEFONE</strong> (E-Banking, Mobile) ou por <strong>DEPÓSITO
                                        DIRECTO</strong>.
                                </p>
                                <hr>
                                <p class="mb-0 text-center"><strong>NB: PARA O PAGAMENTO DE PROPINAS, USE SOMENTE AS
                                        REDES INDICADAS NA ABA "MÉTODOS DE PAGAMENTO"!</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela de propinas com design melhorado -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-borderless">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">
                                        <i class="fas fa-file-invoice-dollar mr-1 text-primary"></i>Propina
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-calendar-day mr-1 text-primary"></i>Data Limite
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-building mr-1 text-primary"></i>Entidade
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-hashtag mr-1 text-primary"></i>Referência
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-money-bill-wave mr-1 text-primary"></i>Montante
                                    </th>
                                    <th class="border-0">
                                        <i class="fas fa-info-circle mr-1 text-primary"></i>Situação
                                    </th>
                                    <th class="border-0 text-center">
                                        <i class="fas fa-cogs mr-1 text-primary"></i>Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($propinas as $propina)
                                    @php
                                        $isVencido =
                                            strtotime($propina->data_vencimento) < time() &&
                                            $propina->status == 'pendente';
                                        $rowClass =
                                            $propina->status == 'pago'
                                                ? 'bg-success-light'
                                                : ($isVencido
                                                    ? 'bg-danger-light'
                                                    : 'bg-warning-light');
                                    @endphp
                                    <tr class="{{ $rowClass }} hover-shadow" data-status="{{ $propina->status }}"
                                        data-vencido="{{ $isVencido ? 'sim' : 'nao' }}">
                                        <td>{{ $propina->descricao ?? 'Descrição não disponível' }}</td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($propina->data_vencimento)) }}
                                            @if ($isVencido)
                                                <span class="badge badge-danger ml-1">Vencido</span>
                                            @endif
                                        </td>
                                        <td>11151</td>
                                        <td>
                                            <span class="d-none d-md-inline">{{ $propina->referencia }}</span>
                                            <span
                                                class="d-inline d-md-none">{{ substr($propina->referencia, 0, 6) }}...</span>
                                            <button type="button" class="btn btn-xs btn-outline-secondary copy-btn"
                                                data-toggle="tooltip" title="Copiar referência"
                                                data-clipboard-text="{{ $propina->referencia }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </td>
                                        <td class="font-weight-bold">{{ number_format($propina->valor, 2, '.', ',') }}
                                            MZN</td>
                                        <td>
                                            @if ($propina->status == 'pendente')
                                                @if ($isVencido)
                                                    <span class="badge badge-danger p-2"><i
                                                            class="fas fa-exclamation-triangle mr-1"></i>EM
                                                        ATRASO</span>
                                                @else
                                                    <span class="badge badge-warning p-2"><i
                                                            class="fas fa-clock mr-1"></i>PENDENTE</span>
                                                @endif
                                            @elseif ($propina->status == 'em_analise')
                                                <span class="badge badge-info p-2"><i
                                                        class="fas fa-hourglass-half mr-1"></i>EM ANÁLISE</span>
                                            @elseif ($propina->status == 'cancelado')
                                                <span class="badge badge-secondary p-2"><i
                                                        class="fas fa-times-circle mr-1"></i>CANCELADO</span>
                                            @else
                                                <span class="badge badge-success p-2"><i
                                                        class="fas fa-check-circle mr-1"></i>PAGO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#detalhesModal{{ $propina->id }}">
                                                    <i class="fas fa-eye mr-1"></i><span
                                                        class="d-none d-md-inline">Detalhes</span>
                                                </button>

                                                @if ($propina->status == 'pendente')
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal" data-target="#pagarModal{{ $propina->id }}">
                                                        <i class="fas fa-money-bill-wave mr-1"></i><span
                                                            class="d-none d-md-inline">Pagar</span>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#reciboModal{{ $propina->id }}">
                                                        <i class="fas fa-file-invoice mr-1"></i><span
                                                            class="d-none d-md-inline">Recibo</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                <h4 class="text-muted">Nenhuma propina encontrada</h4>
                                                <p class="text-muted">Não há propinas pendentes para o período selecionado.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Paginação -->
                    <div class="mt-3">
                        {{ $propinas->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                <!-- Tab: Histórico de Pagamentos -->
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <!-- Filtros para o histórico -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="periodo_filter"><i class="fas fa-calendar-week mr-1"></i>Período:</label>
                                <select id="periodo_filter" class="form-control select2" style="width: 100%;">
                                    <option value="todos">Todos</option>
                                    <option value="30dias">Últimos 30 dias</option>
                                    <option value="3meses">Últimos 3 meses</option>
                                    <option value="6meses">Últimos 6 meses</option>
                                    <option value="1ano">Último ano</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_filter"><i class="fas fa-tag mr-1"></i>Tipo de Pagamento:</label>
                                <select id="tipo_filter" class="form-control select2" style="width: 100%;">
                                    <option value="todos">Todos</option>
                                    <option value="propina">Propina</option>
                                    <option value="matricula">Matrícula</option>
                                    <option value="taxa">Taxas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="download_report"><i class="fas fa-file-download mr-1"></i>Relatório:</label>
                                <div class="input-group">
                                    <select id="report_type" class="form-control">
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="download_report">
                                            <i class="fas fa-download mr-1"></i>Baixar Relatório
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de histórico de pagamentos -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="m-0"><i class="fas fa-chart-line mr-2"></i>Evolução de Pagamentos</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="paymentsChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Tabela de histórico de pagamentos -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-borderless">
                            <thead class="thead-light">
                                <tr>
                                    <th><i class="fas fa-calendar-alt mr-1 text-primary"></i>Data</th>
                                    <th><i class="fas fa-file-invoice-dollar mr-1 text-primary"></i>Descrição</th>
                                    <th><i class="fas fa-hashtag mr-1 text-primary"></i>Referência</th>
                                    <th><i class="fas fa-money-bill-wave mr-1 text-primary"></i>Valor</th>
                                    <th><i class="fas fa-credit-card mr-1 text-primary"></i>Método</th>
                                    <th><i class="fas fa-file-alt mr-1 text-primary"></i>Recibo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pagamentos->where('status', 'pago')->sortByDesc('data_pagamento') as $pagamento)
                                    <tr class="hover-shadow">
                                        <td>{{ date('d-m-Y', strtotime($pagamento->data_pagamento)) }}</td>
                                        <td>{{ $pagamento->descricao }}</td>
                                        <td>{{ $pagamento->referencia }}</td>
                                        <td class="font-weight-bold">{{ number_format($pagamento->valor, 2, '.', ',') }}
                                            MZN</td>
                                        <td>{{ $pagamento->metodo_pagamento ?? 'Transferência Bancária' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-success"
                                                data-toggle="tooltip" title="Baixar recibo">
                                                <i class="fas fa-file-download mr-1"></i>Recibo
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                                <h4 class="text-muted">Nenhum pagamento registrado</h4>
                                                <p class="text-muted">Não há pagamentos no período selecionado.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Métodos de Pagamento -->
                <div class="tab-pane fade" id="payment-methods" role="tabpanel" aria-labelledby="payment-methods-tab">
                    <div class="row">
                        <!-- Instruções de pagamento -->
                        <div class="col-lg-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="m-0"><i class="fas fa-info-circle mr-2"></i>Como Pagar Suas Propinas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span class="bg-primary">Opções de Pagamento</span>
                                        </div>

                                        <div>
                                            <i class="fas fa-money-bill bg-success"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header">Pagamento via ATM</h3>
                                                <div class="timeline-body">
                                                    <p>1. Dirija-se a qualquer ATM dos Bancos indicados (BCI ou use o
                                                        Ponto24)</p>
                                                    <p>2. Escolha a opção "Pagamentos" > "Pagamento de Serviços"</p>
                                                    <p>3. Preencha os seguintes dados:</p>
                                                    <ul class="list-unstyled ml-3">
                                                        <li><strong>Entidade:</strong> 11151</li>
                                                        <li><strong>Referência:</strong> Da sua propina</li>
                                                        <li><strong>Valor:</strong> Exato da propina</li>
                                                    </ul>
                                                    <p>4. Confirme e guarde o comprovante</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <i class="fas fa-laptop bg-info"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header">Pagamento via Internet Banking</h3>
                                                <div class="timeline-body">
                                                    <p>1. Acesse sua conta bancária online</p>
                                                    <p>2. Procure pela opção <strong>PAGAMENTOS</strong></p>
                                                    <p>3. Selecione <strong>PAGAMENTO DE SERVIÇOS</strong></p>
                                                    <p>4. Digite os mesmos dados: Entidade, Referência e Valor</p>
                                                    <p>5. Confirme e salve ou imprima o comprovante</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <i class="fas fa-university bg-warning"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header">Pagamento no Balcão do Banco</h3>
                                                <div class="timeline-body">
                                                    <p>Apresente no balcão do banco a entidade, referência e valor de cada
                                                        propina que deseja pagar.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Redes disponíveis e envio de comprovante -->
                        <div class="col-lg-6">
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="m-0"><i class="fas fa-credit-card mr-2"></i>Redes Bancárias Disponíveis
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row mb-4">
                                        <div class="col-6 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body p-3">
                                                    <img src="{{ asset('img/banks/bci.png') }}" alt="BCI"
                                                        class="img-fluid" style="max-height: 80px;">
                                                    <h5 class="mt-2">BCI</h5>
                                                    <p class="text-muted mb-0">ATM, Internet Banking</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-body p-3">
                                                    <img src="{{ asset('img/banks/ponto24.png') }}" alt="Ponto24"
                                                        class="img-fluid" style="max-height: 80px;">
                                                    <h5 class="mt-2">Ponto24</h5>
                                                    <p class="text-muted mb-0">ATM, Agências</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning shadow-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>Importante:</strong> Utilize apenas as redes bancárias listadas acima para
                                        seus pagamentos.
                                    </div>
                                </div>
                            </div>

                            <!-- Upload de comprovante -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5 class="m-0"><i class="fas fa-upload mr-2"></i>Enviar Comprovante de Pagamento
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('estudante.registrar.pagamento') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="referencia">
                                                <i class="fas fa-hashtag mr-1"></i>Referência do Pagamento:
                                            </label>
                                            <select class="form-control select2" id="referencia" name="referencia"
                                                required>
                                                <option value="">Selecione a referência</option>
                                                @foreach ($propinas->where('status', 'pendente') as $propina)
                                                    <option value="{{ $propina->referencia }}">
                                                        {{ $propina->descricao }} - {{ $propina->referencia }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="data_pagamento">
                                                <i class="fas fa-calendar-day mr-1"></i>Data do Pagamento:
                                            </label>
                                            <input type="date" class="form-control" id="data_pagamento"
                                                name="data_pagamento" required value="{{ date('Y-m-d') }}"
                                                max="{{ date('Y-m-d') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="comprovante">
                                                <i class="fas fa-file-upload mr-1"></i>Upload do Comprovante:
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="comprovante"
                                                    name="comprovante" required accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label" for="comprovante">Escolher
                                                    arquivo</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Formatos aceitos: PDF, JPG, JPEG, PNG. Tamanho máximo: 5MB
                                            </small>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg btn-action">
                                                <i class="fas fa-paper-plane mr-1"></i>Enviar Comprovante
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

<!-- Modais -->
@foreach ($propinas as $propina)
    @php
        $isVencido = strtotime($propina->data_vencimento) < time() && $propina->status == 'pendente';
    @endphp
    <!-- Modal de Detalhes -->
    <div class="modal fade" id="detalhesModal{{ $propina->id }}" tabindex="-1" role="dialog"
        aria-labelledby="detalhesModalLabel{{ $propina->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detalhesModalLabel{{ $propina->id }}">
                        <i class="fas fa-info-circle mr-2"></i>Detalhes da Propina
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-info"><i class="fas fa-file-invoice"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Descrição</span>
                                    <span class="info-box-number">{{ $propina->descricao }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data de Vencimento</span>
                                    <span
                                        class="info-box-number">{{ date('d-m-Y', strtotime($propina->data_vencimento)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Valor</span>
                                    <span class="info-box-number">{{ number_format($propina->valor, 2, '.', ',') }}
                                        MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box shadow-sm">
                                <span class="info-box-icon bg-warning"><i class="fas fa-info-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Situação</span>
                                    <span class="info-box-number">
                                        @if ($propina->status == 'pendente')
                                            @if ($isVencido)
                                                <span class="badge badge-danger">EM ATRASO</span>
                                            @else
                                                <span class="badge badge-warning">PENDENTE</span>
                                            @endif
                                        @else
                                            <span class="badge badge-success">PAGO</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="card mt-3 shadow-sm">
                        <div class="card-header bg-light">
                            <h5><i class="fas fa-university mr-2"></i>Dados para Pagamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Entidade:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="11151" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary copy-btn" type="button"
                                                    data-clipboard-text="11151" data-toggle="tooltip"
                                                    title="Copiar entidade">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Referência:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                value="{{ $propina->referencia }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary copy-btn" type="button"
                                                    data-clipboard-text="{{ $propina->referencia }}"
                                                    data-toggle="tooltip" title="Copiar referência">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Valor:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                value="{{ number_format($propina->valor, 2, '.', '') }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary copy-btn" type="button"
                                                    data-clipboard-text="{{ number_format($propina->valor, 2, '.', '') }}"
                                                    data-toggle="tooltip" title="Copiar valor">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    @if ($propina->status == 'pendente')
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                            data-target="#pagarModal{{ $propina->id }}">
                            <i class="fas fa-money-bill-wave mr-1"></i>Pagar Agora
                        </button>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Pagamento -->
    <div class="modal fade" id="pagarModal{{ $propina->id }}" tabindex="-1" role="dialog"
        aria-labelledby="pagarModalLabel{{ $propina->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="pagarModalLabel{{ $propina->id }}">
                        <i class="fas fa-money-bill-wave mr-2"></i>Pagar Propina
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="payment-summary mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $propina->descricao }}</h5>
                            <span class="badge badge-{{ $isVencido ? 'danger' : 'warning' }}">
                                {{ $isVencido ? 'Vencido' : 'Pendente' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Vencimento:</small>
                                <p class="mb-0">{{ date('d/m/Y', strtotime($propina->data_vencimento)) }}</p>
                            </div>
                            <div class="text-right">
                                <small class="text-muted">Valor:</small>
                                <h4 class="mb-0 text-primary">
                                    {{ number_format($propina->valor, 2, '.', ',') }} MZN
                                </h4>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-pills mb-3 nav-justified" id="pagamento-tabs{{ $propina->id }}"
                        role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="atm-tab{{ $propina->id }}" data-toggle="pill"
                                href="#atm{{ $propina->id }}" role="tab"
                                aria-controls="atm{{ $propina->id }}" aria-selected="true">
                                <i class="fas fa-credit-card mr-1"></i>ATM/Internet Banking
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="comprovante-tab{{ $propina->id }}" data-toggle="pill"
                                href="#comprovante{{ $propina->id }}" role="tab"
                                aria-controls="comprovante{{ $propina->id }}" aria-selected="false">
                                <i class="fas fa-file-upload mr-1"></i>Upload de Comprovante
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pagamento-tabs-content{{ $propina->id }}">
                        <div class="tab-pane fade show active" id="atm{{ $propina->id }}" role="tabpanel"
                            aria-labelledby="atm-tab{{ $propina->id }}">
                            <div class="alert alert-info shadow-sm">
                                <p><strong>Instruções para pagamento:</strong></p>
                                <ol class="pl-3 mb-0">
                                    <li>Dirija-se a qualquer ATM dos Bancos indicados (BCI ou use o Ponto24)</li>
                                    <li>Escolha a opção "Pagamentos" > "Pagamento de Serviços"</li>
                                    <li>Preencha os seguintes dados:</li>
                                </ol>
                            </div>

                            <div class="card bg-light shadow-sm">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-5 font-weight-bold">Entidade:</div>
                                        <div class="col-7 d-flex justify-content-between">
                                            <span>11151</span>
                                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                data-clipboard-text="11151" data-toggle="tooltip"
                                                title="Copiar entidade">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 font-weight-bold">Referência:</div>
                                        <div class="col-7 d-flex justify-content-between">
                                            <span>{{ $propina->referencia }}</span>
                                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                data-clipboard-text="{{ $propina->referencia }}"
                                                data-toggle="tooltip" title="Copiar referência">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 font-weight-bold">Valor:</div>
                                        <div class="col-7 d-flex justify-content-between">
                                            <span>{{ number_format($propina->valor, 2, '.', ',') }} MZN</span>
                                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                data-clipboard-text="{{ number_format($propina->valor, 2, '.', '') }}"
                                                data-toggle="tooltip" title="Copiar valor">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-success mb-2" data-toggle="tooltip"
                                    title="Baixar instruções em PDF">
                                    <i class="fas fa-file-pdf mr-1"></i>Baixar Instruções
                                </a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="comprovante{{ $propina->id }}" role="tabpanel"
                            aria-labelledby="comprovante-tab{{ $propina->id }}">
                            <form action="{{ route('estudante.registrar.pagamento') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="referencia" value="{{ $propina->referencia }}">

                                <div class="form-group">
                                    <label for="data_pagamento{{ $propina->id }}">
                                        <i class="fas fa-calendar-day mr-1"></i>Data do Pagamento:
                                    </label>
                                    <input type="date" class="form-control"
                                        id="data_pagamento{{ $propina->id }}" name="data_pagamento" required
                                        value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="form-group">
                                    <label for="metodo_pagamento{{ $propina->id }}">
                                        <i class="fas fa-credit-card mr-1"></i>Método de Pagamento:
                                    </label>
                                    <select class="form-control" id="metodo_pagamento{{ $propina->id }}"
                                        name="metodo_pagamento" required>
                                        <option value="ATM">ATM</option>
                                        <option value="Internet Banking">Internet Banking</option>
                                        <option value="Depósito Bancário">Depósito Bancário</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="comprovante{{ $propina->id }}">
                                        <i class="fas fa-file-upload mr-1"></i>Upload do Comprovante:
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input"
                                            id="comprovante{{ $propina->id }}" name="comprovante" required
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="comprovante{{ $propina->id }}">
                                            Escolher arquivo
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Formatos aceitos: PDF, JPG, JPEG, PNG. Tamanho máximo: 5MB
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="observacao{{ $propina->id }}">
                                        <i class="fas fa-comment-alt mr-1"></i>Observações (opcional):
                                    </label>
                                    <textarea class="form-control" id="observacao{{ $propina->id }}" name="observacao" rows="2"></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-action">
                                        <i class="fas fa-paper-plane mr-1"></i>Enviar Comprovante
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Recibo -->
    @if ($propina->status == 'pago')
        <div class="modal fade" id="reciboModal{{ $propina->id }}" tabindex="-1" role="dialog"
            aria-labelledby="reciboModalLabel{{ $propina->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="reciboModalLabel{{ $propina->id }}">
                            <i class="fas fa-file-invoice mr-2"></i>Recibo de Pagamento
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h4>Pagamento Confirmado</h4>
                            <p class="text-muted">Pagamento processado com sucesso</p>
                        </div>

                        <div class="card bg-light shadow-sm">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold">Descrição:</div>
                                    <div class="col-6">{{ $propina->descricao }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold">Referência:</div>
                                    <div class="col-6">{{ $propina->referencia }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold">Data de Pagamento:</div>
                                    <div class="col-6">
                                        {{ date('d-m-Y', strtotime($propina->data_pagamento ?? $propina->updated_at)) }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 font-weight-bold">Valor:</div>
                                    <div class="col-6">{{ number_format($propina->valor, 2, '.', ',') }} MZN</div>
                                </div>
                                <div class="row">
                                    <div class="col-6 font-weight-bold">Método:</div>
                                    <div class="col-6">{{ $propina->metodo_pagamento ?? 'Transferência Bancária' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-primary mb-2" data-toggle="tooltip"
                                title="Baixar recibo em PDF">
                                <i class="fas fa-download mr-1"></i>Baixar Recibo
                            </a>
                            <a href="#" class="btn btn-outline-primary mb-2" data-toggle="tooltip"
                                title="Enviar recibo por email">
                                <i class="fas fa-envelope mr-1"></i>Enviar por Email
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Cores personalizadas */
        :root {
            --danger-dark: #d33724;
            --success-dark: #28a745;
            --warning-dark: #ffc107;
            --primary-dark: #007bff;
            --bg-success-light: rgba(40, 167, 69, 0.1);
            --bg-danger-light: rgba(220, 53, 69, 0.1);
            --bg-warning-light: rgba(255, 193, 7, 0.1);
        }

        /* Cards e boxes */
        .info-box {
            border-radius: 0.5rem;
            overflow: hidden;
            border: none;
        }

        .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .bg-danger-dark {
            background-color: var(--danger-dark);
        }

        .bg-success-dark {
            background-color: var(--success-dark);
        }

        .bg-warning-dark {
            background-color: var(--warning-dark);
        }

        /* Tabelas */
        .table-borderless td,
        .table-borderless th {
            border: none;
        }

        .thead-light th {
            background-color: #f8f9fa;
            color: #495057;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.15rem 1rem rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.35em 0.65em;
        }

        /* Empty state */
        .empty-state {
            opacity: 0.7;
        }

        /* Nav tabs */
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            background-color: transparent;
            border-bottom: 3px solid var(--primary);
            font-weight: bold;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            color: var(--primary);
            border-bottom: 3px solid rgba(0, 123, 255, 0.3);
        }

        /* Progress bars */
        .progress {
            height: 6px;
            border-radius: 3px;
        }

        /* Timeline */
        .timeline:before {
            background-color: #dee2e6;
        }

        .timeline>div>.timeline-item {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            border-radius: 0.5rem;
        }

        /* Tooltip */
        .tooltip-inner {
            max-width: 200px;
            padding: 6px 10px;
            background-color: #343a40;
            border-radius: 4px;
        }

        /* Custom background classes */
        .bg-success-light {
            background-color: var(--bg-success-light);
        }

        .bg-danger-light {
            background-color: var(--bg-danger-light);
        }

        .bg-warning-light {
            background-color: var(--bg-warning-light);
        }

        /* Select2 customization */
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
        }

        /* Payment summary in modals */
        .payment-summary {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .card-title {
                font-size: 1.2rem;
            }

            .info-box-number {
                font-size: 1.1rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.8rem;
            }

            .progress-description {
                font-size: 0.8rem;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.9rem;
            }

            .timeline {
                font-size: 0.9rem;
            }

            .timeline-body p {
                margin-bottom: 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover',
                placement: 'top'
            });

            // Inicializar clipboard
            new ClipboardJS('.copy-btn').on('success', function(e) {
                $(e.trigger).tooltip('hide')
                    .attr('title', 'Copiado!')
                    .tooltip('show');

                setTimeout(function() {
                    $(e.trigger).tooltip('hide')
                        .attr('title', 'Copiar')
                        .tooltip('dispose');
                }, 1000);

                e.clearSelection();
            });

            // Inicializar select2
            $('.select2').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: Infinity
            });

            // Alterar ano letivo automaticamente
            $('#ano_letivo').change(function() {
                $(this).closest('form').submit();
            });

            // Atualizar nome do arquivo no upload
            $(document).on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Filtro de status na tabela de propinas
            $('#status_filter').change(function() {
                let status = $(this).val();

                if (status === 'todos') {
                    $('table tbody tr').show();
                } else if (status === 'vencido') {
                    $('table tbody tr').hide();
                    $('table tbody tr[data-vencido="sim"]').show();
                } else {
                    $('table tbody tr').hide();
                    $('table tbody tr[data-status="' + status + '"]').show();
                }
            });

            // Busca na tabela
            $('#table_search').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Efeito de loading ao clicar em ações
            $(document).on('click', '.btn-action', function() {
                let btn = $(this);
                btn.prop('disabled', true);
                btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Processando...');
            });

            // Gráfico de histórico de pagamentos
            if ($('#paymentsChart').length > 0) {
                const ctx = document.getElementById('paymentsChart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out',
                            'Nov', 'Dez'
                        ],
                        datasets: [{
                            label: 'Pagamentos',
                            backgroundColor: 'rgba(60,141,188,0.2)',
                            borderColor: 'rgba(60,141,188,1)',
                            pointRadius: 4,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: [
                                @foreach ($pagamentos->where('status', 'pago')->sortBy('data_pagamento') as $pagamento)
                                    {{ $pagamento->valor }},
                                @endforeach
                            ]
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            display: false
                        },
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: true
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        return value + ' MZN';
                                    }
                                }
                            }]
                        }
                    }
                });
            }

            // Destacar propinas próximas do vencimento
            const proxVencimento = {{ $proximoVencimento ? 'true' : 'false' }};
            if (proxVencimento) {
                setInterval(function() {
                    $('.info-box.bg-warning').fadeOut(500).fadeIn(500);
                }, 5000);
            }
        });
    </script>
@stop
