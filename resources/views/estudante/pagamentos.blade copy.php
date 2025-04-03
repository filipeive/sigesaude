@extends('adminlte::page')

@section('title', 'Meus Pagamentos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-wallet mr-2"></i> Situação Financeira
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{-- route('estudante.home') --}}">Home</a></li>
                    <li class="breadcrumb-item active">Pagamentos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Notificações -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check mr-1"></i> Sucesso!</h5>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban mr-1"></i> Erro!</h5>
                {{ session('error') }}
            </div>
        @endif

        <!-- Cards de Resumo Financeiro -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dívida Atual</span>
                        <span class="info-box-number">{{ number_format($totalPendente, 2, ',', '.') }} MZN</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalPendente > 0 ? 100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            @if ($totalPendente > 0)
                                <i class="fas fa-clock mr-1"></i> Pagamento pendente
                            @else
                                <i class="fas fa-check-circle mr-1"></i> Tudo em dia
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pago</span>
                        <span class="info-box-number">{{ number_format($totalPago, 2, ',', '.') }} MZN</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalPago > 0 ? 100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            Desde {{ date('Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Próximo Vencimento</span>
                        <span class="info-box-number">
                            @if ($proximoVencimento)
                                {{-- use carbon para a data do proximo pagamento --}}
                                {{ \Carbon\Carbon::parse($proximoVencimento->data_vencimento)->format('d/m/Y') }}
                            @else
                                Nenhum vencimento próximo
                            @endif
                        </span>
                        @if ($proximoVencimento)
                            <div class="progress">
                                @php
                                    $diasRestantes = max(
                                        0,
                                        \Carbon\Carbon::now()->diffInDays($proximoVencimento->data_vencimento, false),
                                    );
                                    $percentual = min(100, max(0, ($diasRestantes / 30) * 100));
                                @endphp
                                <div class="progress-bar" style="width: {{ $percentual }}%"></div>
                            </div>
                            <span class="progress-description">
                                @if ($diasRestantes <= 0)
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Vencido
                                @elseif ($diasRestantes <= 5)
                                    <i class="fas fa-clock mr-1"></i> Vence em {{ $diasRestantes }} dias
                                @else
                                    <i class="fas fa-calendar-check mr-1"></i> {{ $diasRestantes }} dias restantes
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Filtros e Abas -->
        <div class="card">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="propinas-tab" data-toggle="pill" href="#propinas" role="tab">
                            <i class="fas fa-money-bill-wave mr-2"></i> Propinas
                            @if ($propinas->where('status', 'pendente')->count() > 0)
                                <span
                                    class="badge badge-danger ml-1">{{ $propinas->where('status', 'pendente')->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="historico-tab" data-toggle="pill" href="#historico" role="tab">
                            <i class="fas fa-history mr-2"></i> Histórico
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="metodos-tab" data-toggle="pill" href="#metodos" role="tab">
                            <i class="fas fa-credit-card mr-2"></i> Métodos de Pagamento
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <!-- Aba de Propinas -->
                    <div class="tab-pane fade show active" id="propinas" role="tabpanel">
                        <div class="row mb-4">
                            <!-- Seletor de Ano Letivo -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('estudante.pagamentos') }}"
                                            class="form-inline">
                                            <div class="form-group mb-2">
                                                <label for="ano_letivo" class="mr-2">Ano Letivo:</label>
                                                <select name="ano_letivo" id="ano_letivo" class="form-control"
                                                    onchange="this.form.submit()">
                                                    @foreach ($anosLetivos as $ano)
                                                        <option value="{{ $ano->id }}"
                                                            {{ $anoLetivo && $ano->id == $anoLetivo->id ? 'selected' : '' }}>
                                                            {{ $ano->ano }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status_filter"><i class="fas fa-filter mr-1"></i> Status</label>
                                    <select class="form-control select2" id="status_filter">
                                        <option value="todos">Todos</option>
                                        <option value="pendente">Pendentes</option>
                                        <option value="vencido">Vencidos</option>
                                        <option value="pago">Pagos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-search mr-1"></i> Buscar</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search_input"
                                            placeholder="Descrição, referência...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instruções de Pagamento -->
                        <div class="alert alert-info">
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
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela de Propinas -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Mês</th>
                                        <th>Vencimento</th>
                                        <th>Referência</th>
                                        <th class="text-right">Valor (MZN)</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($propinas as $propina)
                                        @php
                                            $dataVencimento = \Carbon\Carbon::parse($propina->data_vencimento);
                                            $isVencido = $propina->status == 'pendente' && $dataVencimento->isPast();
                                        @endphp
                                        <tr
                                            class="{{ $propina->status == 'pago' ? 'table-success' : ($isVencido ? 'table-danger' : 'table-warning') }}">
                                            <td>{{ Carbon\Carbon::parse($propina->data_vencimento)->format('F/Y') }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($propina->data_vencimento)->format('d/m/Y') }}
                                                @if ($isVencido)
                                                    <span class="badge badge-danger ml-1">Vencido</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $propina->referencia }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                            data-clipboard-text="{{ $propina->referencia }}">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">{{ number_format($propina->valor, 2, ',', '.') }}</td>
                                            <td>
                                                @if ($propina->status == 'pendente')
                                                    @if ($isVencido)
                                                        <span class="badge badge-danger">Vencido</span>
                                                    @else
                                                        <span class="badge badge-warning">Pendente</span>
                                                    @endif
                                                @elseif($propina->status == 'pago')
                                                    <span class="badge badge-success">Pago</span>
                                                @elseif($propina->status == 'em_analise')
                                                    <span class="badge badge-info">Em Análise</span>
                                                @else
                                                    <span class="badge badge-secondary">Cancelado</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-info" data-toggle="modal"
                                                        data-target="#detalhesModal{{ $propina->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if ($propina->status == 'pendente')
                                                        <button class="btn btn-primary" data-toggle="modal"
                                                            data-target="#pagarModal{{ $propina->id }}">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-success" data-toggle="modal"
                                                            data-target="#reciboModal{{ $propina->id }}">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                                    <h4 class="text-muted">Nenhuma propina encontrada</h4>
                                                    <p class="text-muted">Não há propinas para o período selecionado.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="mt-3">
                            {{ $propinas->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                    <!-- Aba de Histórico -->
                    <div class="tab-pane fade" id="historico" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="periodo_filter"><i class="fas fa-calendar-week mr-1"></i> Período</label>
                                    <select class="form-control select2" id="periodo_filter">
                                        <option value="todos">Todos</option>
                                        <option value="30dias">Últimos 30 dias</option>
                                        <option value="3meses">Últimos 3 meses</option>
                                        <option value="6meses">Últimos 6 meses</option>
                                        <option value="1ano">Último ano</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_filter"><i class="fas fa-tag mr-1"></i> Tipo</label>
                                    <select class="form-control select2" id="tipo_filter">
                                        <option value="todos">Todos</option>
                                        <option value="propina">Propina</option>
                                        <option value="matricula">Matrícula</option>
                                        <option value="taxa">Taxas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-file-export mr-1"></i> Exportar</label>
                                    <div class="input-group">
                                        <select class="form-control" id="export_format">
                                            <option value="pdf">PDF</option>
                                            <option value="excel">Excel</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" id="export_btn">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela de Histórico -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Descrição</th>
                                        <th>Referência</th>
                                        <th class="text-right">Valor (MZN)</th>
                                        <th>Método</th>
                                        <th class="text-center">Recibo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pagamentos->where('status', 'pago') as $pagamento)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $pagamento->descricao }}</td>
                                            <td>{{ $pagamento->referencia }}</td>
                                            <td class="text-right">{{ number_format($pagamento->valor, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $pagamento->metodo_pagamento ?? 'Transferência Bancária' }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-file-download mr-1"></i> Baixar
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

                    <!-- Aba de Métodos de Pagamento -->
                    <div class="tab-pane fade" id="metodos" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Instruções de
                                            Pagamento</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="paymentMethodsAccordion">
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button"
                                                            data-toggle="collapse" data-target="#collapseOne">
                                                            <i class="fas fa-atm mr-2"></i> Pagamento via ATM
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseOne" class="collapse show"
                                                    data-parent="#paymentMethodsAccordion">
                                                    <div class="card-body">
                                                        <ol>
                                                            <li>Dirija-se a qualquer ATM dos Bancos indicados</li>
                                                            <li>Escolha a opção "Pagamentos" > "Pagamento de Serviços"</li>
                                                            <li>Preencha os dados conforme sua ficha de pagamentos</li>
                                                            <li>Confirme e guarde o comprovante</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button"
                                                            data-toggle="collapse" data-target="#collapseTwo">
                                                            <i class="fas fa-laptop mr-2"></i> Internet Banking
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse"
                                                    data-parent="#paymentMethodsAccordion">
                                                    <div class="card-body">
                                                        <ol>
                                                            <li>Acesse sua conta bancária online</li>
                                                            <li>Procure pela opção "Pagamentos" > "Pagamento de Serviços"
                                                            </li>
                                                            <li>Preencha os dados conforme sua ficha de pagamentos</li>
                                                            <li>Confirme e guarde o comprovante</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h3 class="card-title"><i class="fas fa-university mr-2"></i> Bancos Parceiros
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <img src="{{ asset('img/banks/bci.png') }}" alt="BCI"
                                                            class="img-fluid mb-3" style="max-height: 50px;">
                                                        <h5>Banco Comercial e de Investimentos</h5>
                                                        <p class="text-muted">ATM, Internet Banking</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body text-center">
                                                        <img src="{{ asset('img/banks/ponto24.png') }}" alt="Ponto24"
                                                            class="img-fluid mb-3" style="max-height: 50px;">
                                                        <h5>Ponto24</h5>
                                                        <p class="text-muted">ATM, Agências</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulário de Upload de Comprovante -->
                                <div class="card mt-4">
                                    <div class="card-header bg-info text-white">
                                        <h3 class="card-title"><i class="fas fa-upload mr-2"></i> Enviar Comprovante</h3>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('estudante.registrar.pagamento') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label for="referencia"><i class="fas fa-hashtag mr-1"></i>
                                                    Referência</label>
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
                                                <label for="data_pagamento"><i class="fas fa-calendar-day mr-1"></i> Data
                                                    do Pagamento</label>
                                                <input type="date" class="form-control" id="data_pagamento"
                                                    name="data_pagamento" required value="{{ date('Y-m-d') }}"
                                                    max="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="comprovante"><i class="fas fa-file-upload mr-1"></i>
                                                    Comprovante</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="comprovante"
                                                        name="comprovante" required accept=".pdf,.jpg,.jpeg,.png">
                                                    <label class="custom-file-label" for="comprovante">Escolher
                                                        arquivo</label>
                                                </div>
                                                <small class="form-text text-muted">Formatos aceitos: PDF, JPG, PNG (max.
                                                    5MB)</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="observacao"><i class="fas fa-comment-alt mr-1"></i>
                                                    Observações</label>
                                                <textarea class="form-control" id="observacao" name="observacao" rows="2"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-paper-plane mr-1"></i> Enviar Comprovante
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modais -->
    @foreach ($propinas as $propina)
        @php
            $isVencido = $propina->status == 'pendente' && \Carbon\Carbon::parse($propina->data_vencimento)->isPast();
        @endphp

        <!-- Modal de Detalhes -->
        <div class="modal fade" id="detalhesModal{{ $propina->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-info-circle mr-2"></i> Detalhes da Propina</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info"><i class="fas fa-file-invoice"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Descrição</span>
                                        <span class="info-box-number">{{ $propina->descricao }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-calendar-alt"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Vencimento</span>
                                        <span
                                            class="info-box-number">{{ \Carbon\Carbon::parse($propina->data_vencimento)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Valor</span>
                                        <span class="info-box-number">{{ number_format($propina->valor, 2, ',', '.') }}
                                            MZN</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-info-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Status</span>
                                        <span class="info-box-number">
                                            @if ($propina->status == 'pendente')
                                                @if ($isVencido)
                                                    <span class="badge badge-danger">Vencido</span>
                                                @else
                                                    <span class="badge badge-warning">Pendente</span>
                                                @endif
                                            @elseif($propina->status == 'pago')
                                                <span class="badge badge-success">Pago</span>
                                            @else
                                                <span class="badge badge-secondary">Cancelado</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-university mr-2"></i> Dados para Pagamento</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="30%">Entidade</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" value="11151"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary copy-btn"
                                                                data-clipboard-text="11151">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Referência</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ $propina->referencia }}" readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary copy-btn"
                                                                data-clipboard-text="{{ $propina->referencia }}">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Valor</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            value="{{ number_format($propina->valor, 2, ',', '') }}"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary copy-btn"
                                                                data-clipboard-text="{{ number_format($propina->valor, 2, ',', '') }}">
                                                                <i class="fas fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($propina->status == 'pendente')
                            <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                                data-target="#pagarModal{{ $propina->id }}">
                                <i class="fas fa-money-bill-wave mr-1"></i> Pagar Agora
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Pagamento -->
        <div class="modal fade" id="pagarModal{{ $propina->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-money-bill-wave mr-2"></i> Pagar Propina</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle mr-2"></i> Instruções para Pagamento</h5>
                            <p>Escolha um dos métodos abaixo para realizar seu pagamento.</p>
                        </div>

                        <div class="payment-summary mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-1">{{ $propina->descricao }}</h5>
                                    <p class="text-muted mb-1">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Vencimento: {{ \Carbon\Carbon::parse($propina->data_vencimento)->format('d/m/Y') }}
                                    </p>
                                    <span class="badge badge-{{ $isVencido ? 'danger' : 'warning' }}">
                                        {{ $isVencido ? 'Vencido' : 'Pendente' }}
                                    </span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h3 class="text-primary mb-0">
                                        {{ number_format($propina->valor, 2, ',', '.') }} MZN
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs nav-justified mb-4" id="paymentTabs{{ $propina->id }}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="atm-tab{{ $propina->id }}" data-toggle="tab"
                                    href="#atm{{ $propina->id }}">
                                    <i class="fas fa-atm mr-1"></i> ATM/Internet Banking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="mobile-tab{{ $propina->id }}" data-toggle="tab"
                                    href="#mobile{{ $propina->id }}">
                                    <i class="fas fa-mobile-alt mr-1"></i> Mobile Banking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="upload-tab{{ $propina->id }}" data-toggle="tab"
                                    href="#upload{{ $propina->id }}">
                                    <i class="fas fa-upload mr-1"></i> Upload Comprovante
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="paymentTabsContent{{ $propina->id }}">
                            <div class="tab-pane fade show active" id="atm{{ $propina->id }}">
                                <div class="alert alert-info">
                                    <p><strong>Instruções para pagamento:</strong></p>
                                    <ol>
                                        <li>Dirija-se a qualquer ATM dos Bancos indicados</li>
                                        <li>Escolha a opção "Pagamentos" > "Pagamento de Serviços"</li>
                                        <li>Preencha os seguintes dados:</li>
                                    </ol>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-5 font-weight-bold">Entidade:</div>
                                            <div class="col-7 d-flex justify-content-between">
                                                <span>11151</span>
                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                    data-clipboard-text="11151">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-5 font-weight-bold">Referência:</div>
                                            <div class="col-7 d-flex justify-content-between">
                                                <span>{{ $propina->referencia }}</span>
                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                    data-clipboard-text="{{ $propina->referencia }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5 font-weight-bold">Valor:</div>
                                            <div class="col-7 d-flex justify-content-between">
                                                <span>{{ number_format($propina->valor, 2, ',', '.') }} MZN</span>
                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                    data-clipboard-text="{{ number_format($propina->valor, 2, ',', '') }}">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-success">
                                        <i class="fas fa-file-pdf mr-1"></i> Baixar Instruções
                                    </a>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="mobile{{ $propina->id }}">
                                <div class="alert alert-info">
                                    <p><strong>Instruções para pagamento via Mobile Banking:</strong></p>
                                    <ol>
                                        <li>Acesse o aplicativo do seu banco no celular</li>
                                        <li>Procure pela opção "Pagamentos" > "Pagamento de Serviços"</li>
                                        <li>Preencha os mesmos dados mostrados na aba ATM</li>
                                        <li>Confirme e guarde o comprovante digital</li>
                                    </ol>
                                </div>
                                <div class="text-center"> <img src="{{ asset('img/mobile-payment.png') }}"
                                        alt="Mobile Payment" class="img-fluid" style="max-height: 200px;">
                                </div>
                            </div>
                            <div class="tab-pane fade" id="upload{{ $propina->id }}">
                                <form action="{{ route('estudante.registrar.pagamento') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="referencia" value="{{ $propina->referencia }}">

                                    <div class="form-group">
                                        <label for="data_pagamento"><i class="fas fa-calendar-day mr-1"></i> Data do
                                            Pagamento</label>
                                        <input type="date" class="form-control" id="data_pagamento"
                                            name="data_pagamento" required value="{{ date('Y-m-d') }}"
                                            max="{{ date('Y-m-d') }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="metodo_pagamento"><i class="fas fa-credit-card mr-1"></i> Método de
                                            Pagamento</label>
                                        <select class="form-control" id="metodo_pagamento" name="metodo_pagamento"
                                            required>
                                            <option value="ATM">ATM</option>
                                            <option value="Internet Banking">Internet Banking</option>
                                            <option value="Mobile Banking">Mobile Banking</option>
                                            <option value="Depósito Bancário">Depósito Bancário</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="comprovante"><i class="fas fa-file-upload mr-1"></i>
                                            Comprovante</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="comprovante"
                                                name="comprovante" required accept=".pdf,.jpg,.jpeg,.png">
                                            <label class="custom-file-label" for="comprovante">Escolher arquivo</label>
                                        </div>
                                        <small class="form-text text-muted">Formatos aceitos: PDF, JPG, PNG (max.
                                            5MB)</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="observacao"><i class="fas fa-comment-alt mr-1"></i>
                                            Observações</label>
                                        <textarea class="form-control" id="observacao" name="observacao" rows="2"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-paper-plane mr-1"></i> Enviar Comprovante
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Recibo -->
        @if ($propina->status == 'pago')
            <div class="modal fade" id="reciboModal{{ $propina->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fas fa-file-invoice mr-2"></i> Recibo de Pagamento</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                                <h4>Pagamento Confirmado</h4>
                                <p class="text-muted">Pagamento processado com sucesso</p>
                            </div>

                            <div class="card bg-light">
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
                                            {{ \Carbon\Carbon::parse($propina->data_pagamento)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 font-weight-bold">Valor:</div>
                                        <div class="col-6">{{ number_format($propina->valor, 2, ',', '.') }} MZN</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 font-weight-bold">Método:</div>
                                        <div class="col-6">{{ $propina->metodo_pagamento ?? 'Transferência Bancária' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-primary mb-2">
                                    <i class="fas fa-download mr-1"></i> Baixar Recibo
                                </a>
                                <a href="#" class="btn btn-outline-primary mb-2">
                                    <i class="fas fa-envelope mr-1"></i> Enviar por Email
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('vendor/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .info-box {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
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

        .empty-state {
            opacity: 0.7;
        }

        .payment-summary {
            border-left: 4px solid var(--primary);
        }

        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid var(--primary);
        }
    </style>
@stop
@section('js')
    <!-- Adicione DataTables CSS e JS no cabeçalho -->
    <link rel="stylesheet"
        href="{{ asset('vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <script src="{{ asset('vendor/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables na tabela de propinas
            var table = $('#propinasTable').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese.json"
                },
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                columnDefs: [{
                        orderable: false,
                        targets: [5]
                    }, // Coluna de ações não ordenável
                    {
                        className: "text-right",
                        targets: [3]
                    }, // Alinhar valores à direita
                    {
                        width: "15%",
                        targets: [1, 4, 5]
                    } // Definir largura para algumas colunas
                ],
                order: [
                    [1, 'asc']
                ], // Ordenar por data de vencimento por padrão
                initComplete: function() {
                    // Adicionar filtro personalizado para status
                    this.api().columns([4]).every(function() {
                        var column = this;
                        var select = $(
                                '<select class="form-control form-control-sm"><option value="">Todos Status</option><option value="pago">Pago</option><option value="pendente">Pendente</option><option value="vencido">Vencido</option></select>'
                                )
                            .appendTo($('#status_filter').empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                            });
                    });
                }
            });

            // Integrar com o campo de busca existente
            $('#search_input').keyup(function() {
                table.search($(this).val()).draw();
            });

            // Integrar com o seletor de ano letivo
            $('#ano_letivo').change(function() {
                window.location.href = "{{ route('estudante.pagamentos') }}?ano_letivo=" + $(this).val();
            });

            // Clipboard para copiar referências
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

            // Custom file input
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
