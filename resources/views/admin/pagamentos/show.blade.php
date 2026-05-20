@extends('adminlte::page')

@section('title', 'Detalhes do Pagamento')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave mr-2 text-primary"></i> Detalhes do Pagamento</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Perfil do Estudante -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $pagamento->estudante?->user?->foto_perfil ? Storage::url($pagamento->estudante->user->foto_perfil) : asset('vendor/adminlte/dist/img/user.jpg') }}"
                            alt="Foto do estudante" style="width:100px;height:100px;object-fit:cover;">
                    </div>
                    <h3 class="profile-username text-center">{{ $pagamento->estudante?->user?->name ?? 'N/A' }}</h3>
                    <p class="text-muted text-center">{{ $pagamento->estudante?->turma?->nome ?? 'Sem turma' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nº Matrícula</b> <span class="float-right">{{ $pagamento->estudante?->matricula ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{ $pagamento->estudante?->user?->email ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telefone</b> <span class="float-right">{{ $pagamento->estudante?->user?->telefone ?? 'N/A' }}</span>
                        </li>
                    </ul>
                    @if($pagamento->estudante)
                        <a href="{{ route('admin.estudantes.show', $pagamento->estudante->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-graduate mr-1"></i> Ver Perfil do Estudante
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detalhes do Pagamento -->
        <div class="col-md-8">
            <!-- Instruções de Pagamento -->
            <div class="alert alert-info mb-3" style="border-left: 4px solid #0056b3;">
                <h5><i class="fas fa-credit-card mr-2"></i> Instruções de Pagamento</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Entidade:</strong> <code style="font-size:1.3em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code></p>
                        <p class="mb-1"><strong>Referência:</strong> <code style="font-size:1.2em;">{{ $pagamento->referencia }}</code></p>
                        <p class="mb-1"><strong>Valor:</strong> {{ number_format($pagamento->valor, 2, ',', '.') }} MZN</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Vencimento:</strong> {{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Categoria:</strong>
                            @php
                                $tipos = ['propina'=>'Propina Mensal','matricula'=>'Matrícula','taxa'=>'Taxa','inscricao'=>'Inscrição'];
                            @endphp
                            <span class="badge badge-info">{{ $tipos[$pagamento->tipo] ?? '—' }}</span>
                        </p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span class="badge badge-{{ $pagamento->status == 'pago' ? 'success' : ($pagamento->status == 'pendente' ? 'warning' : 'danger') }}">
                                {{ ucfirst($pagamento->status) }}
                            </span>
                        </p>
                    </div>
                </div>
                <hr style="margin: 8px 0;">
                <p class="mb-0" style="font-size:0.9rem;">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $instrucaoTexto }}
                    Dirija-se a qualquer ATM ou use Internet Banking, selecione
                    <strong>Pagamentos &gt; Pagamento de Serviços</strong>, insira a entidade acima e a referência do pagamento.
                </p>
            </div>

            <!-- Dados do Pagamento -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Detalhes do Pagamento</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-barcode mr-1"></i> Referência</strong>
                            <p class="text-muted"><code style="font-size:1.2rem;">{{ $pagamento->referencia }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-tag mr-1"></i> Categoria</strong>
                            <p class="text-muted">
                                <span class="badge badge-{{ $pagamento->tipo == 'propina' ? 'primary' : 'secondary' }}">
                                    {{ $tipos[$pagamento->tipo] ?? '—' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-check mr-1"></i> Data de Vencimento</strong>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> Data de Pagamento</strong>
                            <p class="text-muted">
                                {{ $pagamento->data_pagamento ? \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') : '—' }}
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-chalkboard-teacher mr-1"></i> Turma</strong>
                            <p class="text-muted">{{ $pagamento->turma?->nome ?? $pagamento->estudante?->turma?->nome ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> Ano Lectivo</strong>
                            <p class="text-muted">{{ $pagamento->anoLectivo?->ano ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-money-bill-wave mr-1"></i> Valor</strong>
                            <p class="text-muted">{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-clipboard-check mr-1"></i> Status</strong>
                            <p class="text-muted">
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
                    @if($pagamento->descricao)
                    <hr>
                    <strong><i class="fas fa-sticky-note mr-1"></i> Observações</strong>
                    <p class="text-muted">{!! nl2br(e($pagamento->descricao)) !!}</p>
                    @endif

                    <!-- Ações -->
                    <hr>
                    <div class="btn-group">
                        <a href="{{ route('admin.pagamentos.edit', $pagamento->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                        @if($pagamento->status == 'pago')
                            <a href="{{ route('admin.pagamentos.recibo', $pagamento) }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Baixar Recibo PDF
                            </a>
                        @endif
                        @if($pagamento->status == 'pendente')
                            <form action="{{ route('admin.pagamentos.updateStatus', $pagamento) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="pago">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Confirmar como PAGO?')">
                                    <i class="fas fa-check mr-1"></i> Marcar como Pago
                                </button>
                            </form>
                        @endif
                    </div>
                    <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary ml-1">
                        <i class="fas fa-arrow-left mr-1"></i> Voltar à Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop
