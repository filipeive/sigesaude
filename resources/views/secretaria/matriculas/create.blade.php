@extends('adminlte::page')

@section('title', 'Secretaria - Nova Matrícula')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-plus-circle mr-2"></i>Nova Matrícula</h1>
        <a href="{{ route('secretaria.matriculas.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Matrículas
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header"><h3 class="card-title">Dados da Matrícula</h3></div>
        <form method="POST" action="{{ route('secretaria.matriculas.store') }}">
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
                        <select name="turma_id" class="form-control @error('turma_id') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            @foreach($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                    {{ $turma->classe?->nome }} {{ $turma->nome }} - {{ $turma->anoLectivo?->ano }}
                                </option>
                            @endforeach
                        </select>
                        @error('turma_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Ano Lectivo</label>
                        <select name="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            @foreach($anosLectivos as $ano)
                                <option value="{{ $ano->id }}" {{ old('ano_lectivo_id') == $ano->id ? 'selected' : '' }}>{{ $ano->ano }}</option>
                            @endforeach
                        </select>
                        @error('ano_lectivo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Valor</label>
                        <input type="number" step="0.01" name="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', 1500) }}">
                        @error('valor')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Data</label>
                        <input type="date" name="data_matricula" class="form-control @error('data_matricula') is-invalid @enderror" value="{{ old('data_matricula', date('Y-m-d')) }}">
                        @error('data_matricula')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 mt-3">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach(['Pendente', 'Ativo', 'Cancelado'] as $status)
                                <option value="{{ $status }}" {{ old('status', 'Pendente') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-8 mt-3">
                        <label>Observações</label>
                        <textarea name="observacoes" class="form-control" rows="2">{{ old('observacoes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Criar Matrícula</button>
                <a href="{{ route('secretaria.matriculas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop
