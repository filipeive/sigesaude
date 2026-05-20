@extends('adminlte::page')

@section('title', 'Detalhes do Pagamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-money-bill-wave mr-1"></i> Detalhes do Pagamento</h1>
        <a href="{{ route('estudante.pagamentos') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    <!-- Instruções de Pagamento -->
    <div class="alert alert-info mb-3" style="border-left: 4px solid #0056b3;">
        <h5><i class="fas fa-credit-card mr-1"></i> Instruções de Pagamento</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Entidade:</strong> <code style="font-size:1.2em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code></p>
                <p><strong>Referência:</strong> <code style="font-size:1.2em;">{{ $pagamento->referencia }}</code></p>
            </div>
            <div class="col-md-6">
                <p><strong>Valor:</strong> {{ number_format($pagamento->valor, 2, ',', '.') }} MZN</p>
                <p><strong>Vencimento:</strong> {{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</p>
            </div>
        </div>
        <hr style="margin:8px 0;">
        <p class="mb-0"><i class="fas fa-info-circle mr-1"></i>{{ $instrucaoTexto }}
            Dirija-se a qualquer ATM ou use Internet Banking → <em>Pagamentos &gt; Pagamento de Serviços</em> → insira a entidade e a referência.
        </p>
    </div>

    <!-- Dados do Pagamento -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Detalhes do Pagamento</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-barcode mr-1"></i> Referência</strong>
                    <p><code style="font-size:1.2rem;">{{ $pagamento->referencia }}</code></p>
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-chalkboard-teacher mr-1"></i> Turma</strong>
                    <p>{{ $pagamento->turma?->nome ?? $estudante->turma?->nome ?? '—' }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-calendar-check mr-1"></i> Data de Vencimento</strong>
                    <p>{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-calendar-alt mr-1"></i> Data de Pagamento</strong>
                    <p>{{ $pagamento->data_pagamento ? \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') : '—' }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-money-bill-wave mr-1"></i> Valor</strong>
                    <p>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</p>
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-clipboard-check mr-1"></i> Status</strong>
                    <p>
                        @if($pagamento->status == 'pago')
                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Pago</span>
                        @elseif($pagamento->status == 'pendente')
                            <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pendente</span>
                        @else
                            <span class="badge badge-danger">{{ $pagamento->status }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <hr>
            <a href="{{ route('estudante.pagamentos.recibo', $pagamento) }}" class="btn btn-success" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Baixar Recibo
            </a>
            @if($pagamento->status == 'pendente')
                <form action="{{ route('estudante.registrar.pagamento') }}" method="POST" enctype="multipart/form-data" class="d-inline mt-2">
                    @csrf
                    <input type="hidden" name="referencia" value="{{ $pagamento->referencia }}">
                    <div class="form-group d-inline">
                        <label class="mr-2">Enviar Comprovativo:</label>
                        <input type="file" name="comprovante" accept=".pdf,.jpg,.jpeg,.png" class="form-control-file d-inline" required>
                    </div>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-upload mr-1"></i> Enviar Comprovativo
                    </button>
                </form>
            @endif
        </div>
    </div>
@stop
