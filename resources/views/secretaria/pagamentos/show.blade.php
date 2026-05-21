@extends('adminlte::page')

@section('title', 'Secretaria - Detalhes do Pagamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-money-check-alt mr-2"></i>Detalhes do Pagamento</h1>
        <a href="{{ route('secretaria.pagamentos.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Pagamentos
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h4>{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</h4>
                    <p class="text-muted mb-1">{{ $pagamento->estudante?->matricula ?? '' }}</p>
                    <p class="mb-1"><strong>Turma:</strong> {{ $pagamento->turma?->nome ?? $pagamento->estudante?->turma?->nome ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Email:</strong> {{ $pagamento->estudante?->user?->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados do Pagamento</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Referência</strong>
                            <p><code>{{ $pagamento->referencia }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status</strong>
                            <p>
                                <span class="badge badge-{{ $pagamento->status === 'pago' ? 'success' : ($pagamento->status === 'cancelado' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($pagamento->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Categoria</strong>
                            <p>{{ ucfirst($pagamento->tipo ?? 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Valor</strong>
                            <p>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Vencimento</strong>
                            <p>{{ $pagamento->data_vencimento ? $pagamento->data_vencimento->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Data de Pagamento</strong>
                            <p>{{ $pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($pagamento->descricao)
                        <hr>
                        <strong>Observações</strong>
                        <p>{!! nl2br(e($pagamento->descricao)) !!}</p>
                    @endif

                    <hr>
                    <form action="{{ route('secretaria.pagamentos.status', $pagamento) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <label>Novo status</label>
                                <select name="status" class="form-control" required>
                                    <option value="pendente" {{ $pagamento->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="pago" {{ $pagamento->status === 'pago' ? 'selected' : '' }}>Pago</option>
                                    <option value="cancelado" {{ $pagamento->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label>Observação</label>
                                <input type="text" name="observacao" class="form-control" value="{{ old('observacao') }}" placeholder="Ex: confirmado pela secretaria">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">
                            <i class="fas fa-save mr-1"></i> Atualizar Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
