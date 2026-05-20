@extends('adminlte::page')

@section('title', 'Editar Matrícula')

@section('content_header')
    <h1><i class="fas fa-edit mr-2"></i>Editar Matrícula</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.matriculas.index') }}">Matrículas</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Estudante: {{ $matricula->estudante?->user?->name ?? 'N/A' }}</h3>
                </div>
                <form action="{{ route('admin.matriculas.update', $matricula->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turma_id">Turma <span class="text-danger">*</span></label>
                                    <select name="turma_id" id="turma_id" class="form-control @error('turma_id') is-invalid @enderror" required>
                                        @foreach($turmas as $turma)
                                            <option value="{{ $turma->id }}" {{ (old('turma_id', $matricula->turma_id) == $turma->id) ? 'selected' : '' }}>
                                                {{ $turma->nome }} ({{ $turma->classe->nome ?? '' }}) - {{ $turma->anoLectivo->ano ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('turma_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ano_lectivo_id">Ano Letivo <span class="text-danger">*</span></label>
                                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" required>
                                        @foreach($anosLectivos as $ano)
                                            <option value="{{ $ano->id }}" {{ (old('ano_lectivo_id', $matricula->ano_lectivo_id) == $ano->id) ? 'selected' : '' }}>
                                                {{ $ano->ano }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valor">Valor da Matrícula (MZN)</label>
                                    <input type="number" step="0.01" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', $matricula->valor) }}">
                                    @error('valor')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Pendente" {{ old('status', $matricula->status) == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="Ativo" {{ old('status', $matricula->status) == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                        <option value="Cancelado" {{ old('status', $matricula->status) == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_matricula">Data da Matrícula</label>
                                    <input type="date" name="data_matricula" id="data_matricula" class="form-control @error('data_matricula') is-invalid @enderror" value="{{ old('data_matricula', $matricula->data_matricula ? $matricula->data_matricula->format('Y-m-d') : '') }}">
                                    @error('data_matricula')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referencia">Referência (Gerada Automaticamente)</label>
                                    <input type="text" id="referencia" class="form-control" value="{{ $matricula->referencia }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observacoes">Observações</label>
                            <textarea name="observacoes" id="observacoes" rows="3" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $matricula->observacoes) }}</textarea>
                            @error('observacoes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Atualizar Matrícula</button>
                        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop