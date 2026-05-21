@extends('adminlte::page')

@section('title', 'Secretaria - Estudante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-graduate mr-2"></i>{{ $estudante->user?->name ?? 'Estudante' }}</h1>
        <div>
            <a href="{{ route('secretaria.estudantes.edit', $estudante) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-1"></i> Editar
            </a>
            <a href="{{ route('secretaria.estudantes.index') }}" class="btn btn-default">
                <i class="fas fa-arrow-left mr-1"></i> Estudantes
            </a>
        </div>
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
                    <h4>{{ $estudante->user?->name ?? 'N/A' }}</h4>
                    <p class="text-muted mb-1">{{ $estudante->matricula }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $estudante->user?->email ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Telefone:</strong> {{ $estudante->user?->telefone ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Status:</strong> <span class="badge badge-{{ $estudante->status === 'Ativo' ? 'success' : 'secondary' }}">{{ $estudante->status }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Dados Académicos</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"><strong>Turma</strong><p>{{ $estudante->turma?->nome ?? 'N/A' }}</p></div>
                        <div class="col-md-4"><strong>Classe</strong><p>{{ $estudante->turma?->classe?->nome ?? 'N/A' }}</p></div>
                        <div class="col-md-4"><strong>Ano Lectivo</strong><p>{{ $estudante->anoLectivo?->ano ?? 'N/A' }}</p></div>
                        <div class="col-md-4"><strong>Turno</strong><p>{{ $estudante->turno }}</p></div>
                        <div class="col-md-4"><strong>Ano de Ingresso</strong><p>{{ $estudante->ano_ingresso }}</p></div>
                        <div class="col-md-4"><strong>Contacto Emergência</strong><p>{{ $estudante->contato_emergencia }}</p></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3 class="card-title">Resumo</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Matrículas:</strong> {{ $estudante->matriculas->count() }}</p>
                    <p class="mb-0"><strong>Pagamentos:</strong> {{ $estudante->pagamentos->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
