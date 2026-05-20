@extends('adminlte::page')

@section('title', 'Detalhes da Matrícula')

@section('content_header')
    <h1><i class="fas fa-info-circle"></i> Detalhes da Matrícula</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                            src="{{ $matricula->estudante?->user?->foto_perfil ? Storage::url($matricula->estudante->user->foto_perfil) : asset('vendor/adminlte/dist/img/user.jpg') }}"
                            alt="Foto do estudante">
                    </div>
                    <h3 class="profile-username text-center">{{ $matricula->estudante?->user?->name ?? 'N/A' }}</h3>
                    <p class="text-muted text-center">{{ $matricula->turma?->nome ?? 'N/A' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Matrícula</b> <a class="float-right">{{ $matricula->estudante?->matricula ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $matricula->estudante?->user?->email ?? 'N/A' }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Telefone</b> <a class="float-right">{{ $matricula->estudante?->user?->telefone ?? 'N/A' }}</a>
                        </li>
                    </ul>
                    @if($matricula->estudante)
                        <a href="{{ route('admin.estudantes.show', $matricula->estudante->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-graduate mr-1"></i> Ver Perfil Completo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Seção de Instruções de Pagamento -->
            <div class="alert alert-info mb-3" style="border-left: 4px solid #1976d2;">
                <h5><i class="fas fa-credit-card mr-2"></i> Instrucoes de Pagamento da Matricula</h5>
                <p class="mb-1">
                    <strong>Entidade:</strong> <code>11151</code>
                    &nbsp;&nbsp;
                    <strong>Referencia:</strong> <code>{{ $matricula->referencia }}</code>
                </p>
                <ul style="margin: 0;">
                    <li>A referencia acima é utilizada para efectuar o pagamento da matricula anual.</li>
                    <li>Apos o pagamento em qualquer ATM ou Internet Banking, o encarregado deve enviar o comprovativo pela plataforma.</li>
                    <li>Betancourt: apos confirmacao do pagamento, o estudante pode ser activado no sistema.</li>
                    <li>As <strong>propinas mensais</strong> sao geradas automaticamente no modulo de pagamentos e devem ser saldadas ate ao dia 10 de cada mes.</li>
                </ul>
            </div>
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detalhes da Matrícula</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-barcode mr-1"></i> Referência de Pagamento</strong>
                    <p class="text-muted">
                        <span class="badge badge-secondary" style="font-size: 1.1rem; padding: 10px;">
                            {{ $matricula->referencia }}
                        </span>
                    </p>
                    <hr>
                    <strong><i class="fas fa-clipboard-check mr-1"></i> Status</strong>
                    <p class="text-muted">
                        @if($matricula->status == 'Ativo')
                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Ativa</span>
                        @elseif($matricula->status == 'Pendente')
                            <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pendente</span>
                        @else
                            <span class="badge badge-danger">{{ $matricula->status }}</span>
                        @endif
                    </p>
                    
                    <div class="mt-3">
                        @if($matricula->status == 'Pendente')
                            <a href="{{ route('admin.matriculas.pdf', $matricula->id) }}" class="btn btn-outline-danger mr-2">
                                <i class="fas fa-file-pdf mr-1"></i> Guia de Instrução de Pagamento
                            </a>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalConfirmar">
                                <i class="fas fa-check mr-1"></i> Confirmar Matrícula
                            </button>
                        @else
                            <a href="{{ route('admin.matriculas.pdf', $matricula->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-file-invoice mr-1"></i> Recibo de Matrícula
                            </a>
                        @endif
                    </div>

                    <hr>
                    <strong><i class="fas fa-file-upload mr-1"></i> Comprovativo de Pagamento</strong>
                    @if($matricula->comprovativo)
                        <p class="text-muted">
                            <a href="{{ Storage::url($matricula->comprovativo) }}" target="_blank" class="btn btn-xs btn-info">
                                <i class="fas fa-eye mr-1"></i> Ver Comprovativo
                            </a>
                        </p>
                    @else
                        <p class="text-muted">Nenhum comprovativo enviado.</p>
                        <form action="{{ route('admin.matriculas.comprovativo', $matricula->id) }}" method="POST" enctype="multipart/form-data" class="form-inline">
                            @csrf
                            <div class="form-group mr-2">
                                <input type="file" name="comprovativo" class="form-control-file" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Anexar</button>
                        </form>
                    @endif

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-users mr-1"></i> Turma</strong>
                            <p class="text-muted">{{ $matricula->turma?->nome ?? 'N/A' }} - {{ $matricula->turma?->ano_serie ?? 'N/A' }}º Ano</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> Ano Letivo</strong>
                            <p class="text-muted">{{ $matricula->anoLectivo?->ano ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-check mr-1"></i> Data de Matrícula</strong>
                            <p class="text-muted">{{ $matricula->data_matricula ? \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-money-bill mr-1"></i> Valor</strong>
                            <p class="text-muted">{{ $matricula->valor ? number_format($matricula->valor, 2, ',', '.') . ' MZN' : 'N/A' }}</p>
                        </div>
                    </div>
                    @if($matricula->observacoes)
                        <hr>
                        <strong><i class="fas fa-sticky-note mr-1"></i> Observações</strong>
                        <p class="text-muted">{{ $matricula->observacoes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar -->
    <div class="modal fade" id="modalConfirmar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Confirmar Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.matriculas.confirmar', $matricula->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Deseja confirmar a matrícula de <strong>{{ $matricula->estudante?->user?->name }}</strong>?</p>
                        <p>Esta ação ativará o estudante no sistema.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Sim, Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop