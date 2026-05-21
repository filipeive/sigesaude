@extends('adminlte::page')

@section('title', 'Secretaria - Novo Pagamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-file-invoice-dollar mr-2"></i>Novo Pagamento</h1>
        <a href="{{ route('secretaria.pagamentos.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Pagamentos
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header"><h3 class="card-title">Dados do Pagamento</h3></div>
        <form method="POST" action="{{ route('secretaria.pagamentos.store') }}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Estudante</label>
                        <select name="estudante_id" class="form-control @error('estudante_id') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            @foreach($estudantes as $estudante)
                                <option value="{{ $estudante->id }}" {{ old('estudante_id') == $estudante->id ? 'selected' : '' }}>
                                    {{ $estudante->user?->name }} - {{ $estudante->matricula }}
                                </option>
                            @endforeach
                        </select>
                        @error('estudante_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-6">
                        <label>Turma</label>
                        <select name="turma_id" class="form-control">
                            <option value="">Sem turma</option>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>{{ $turma->classe?->nome }} {{ $turma->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Ano Lectivo</label>
                        <select name="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" required>
                            @foreach($anosLectivos as $ano)
                                <option value="{{ $ano->id }}" {{ old('ano_lectivo_id', $anoAtivo?->id) == $ano->id ? 'selected' : '' }}>{{ $ano->ano }}</option>
                            @endforeach
                        </select>
                        @error('ano_lectivo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Categoria</label>
                        <select name="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                            @foreach(['propina' => 'Propina', 'matricula' => 'Matrícula', 'taxa' => 'Taxa', 'inscricao' => 'Inscrição'] as $value => $label)
                                <option value="{{ $value }}" {{ old('tipo') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Valor</label>
                        <input type="number" step="0.01" name="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}" required>
                        @error('valor')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Vencimento</label>
                        <input type="date" name="data_vencimento" class="form-control @error('data_vencimento') is-invalid @enderror" value="{{ old('data_vencimento') }}" required>
                        @error('data_vencimento')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Método</label>
                        <select name="metodo_pagamento" class="form-control">
                            <option value="">Não informado</option>
                            @foreach(['dinheiro' => 'Dinheiro', 'transferencia' => 'Transferência', 'mpesa' => 'M-Pesa', 'emola' => 'eMola', 'mkesh' => 'M-Kesh', 'cheque' => 'Cheque', 'outro' => 'Outro'] as $value => $label)
                                <option value="{{ $value }}" {{ old('metodo_pagamento') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label>Descrição</label>
                        <textarea name="descricao" class="form-control" rows="2">{{ old('descricao') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Criar Pagamento</button>
                <a href="{{ route('secretaria.pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop
