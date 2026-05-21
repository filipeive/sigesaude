@extends('adminlte::page')

@section('title', 'Secretaria - Detalhes da Matrícula')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-clipboard-check mr-2"></i>Detalhes da Matrícula</h1>
        <a href="{{ route('secretaria.matriculas.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Matrículas
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
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $matricula->estudante?->user?->name ?? 'N/A' }}</h3>
                    <p class="text-muted text-center">{{ $matricula->estudante?->matricula ?? 'Sem número de matrícula' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{ $matricula->estudante?->user?->email ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telefone</b> <span class="float-right">{{ $matricula->estudante?->user?->telefone ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Turma</b> <span class="float-right">{{ $matricula->turma?->nome ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados da Matrícula</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Referência</strong>
                            <p><code>{{ $matricula->referencia ?? 'N/A' }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status</strong>
                            <p>
                                <span class="badge badge-{{ $matricula->status === 'Ativo' ? 'success' : ($matricula->status === 'Cancelado' ? 'danger' : 'warning') }}">
                                    {{ $matricula->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Ano Lectivo</strong>
                            <p>{{ $matricula->anoLectivo?->ano ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Valor</strong>
                            <p>{{ $matricula->valor ? number_format($matricula->valor, 2, ',', '.') . ' MZN' : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($matricula->observacoes)
                        <hr>
                        <strong>Observações</strong>
                        <p>{{ $matricula->observacoes }}</p>
                    @endif

                    <hr>
                    <h5>Comprovativo</h5>
                    @if($matricula->comprovativo)
                        <a href="{{ Storage::url($matricula->comprovativo) }}" target="_blank" class="btn btn-info mb-2">
                            <i class="fas fa-eye mr-1"></i> Ver comprovativo
                        </a>
                    @else
                        <p class="text-muted">Nenhum comprovativo anexado.</p>
                    @endif

                    <form action="{{ route('secretaria.matriculas.comprovativo', $matricula) }}" method="POST" enctype="multipart/form-data" class="form-inline">
                        @csrf
                        <input type="file" name="comprovativo" class="form-control-file mr-2" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload mr-1"></i> Anexar
                        </button>
                    </form>

                    @error('comprovativo')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    @if($matricula->status !== 'Ativo')
                        <hr>
                        <form action="{{ route('secretaria.matriculas.confirmar', $matricula) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Confirmar esta matrícula e ativar o estudante?')">
                                <i class="fas fa-check mr-1"></i> Confirmar Matrícula
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
