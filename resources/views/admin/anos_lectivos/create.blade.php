@extends('adminlte::page')
@section('title', 'Novo Ano Lectivo')

@section('content_header')
    <h1><i class="fas fa-calendar-plus mr-2"></i>Novo Ano Lectivo</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Preencha os dados do novo ano lectivo</h3>
                </div>
                <form action="{{ route('admin.anos-lectivos.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ano">Ano</label>
                            <input type="number" name="ano" id="ano" class="form-control @error('ano') is-invalid @enderror" 
                                   value="{{ old('ano', date('Y')) }}" required>
                            @error('ano')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="Inativo" {{ old('status', 'Inativo') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Nota: Apenas um ano lectivo pode estar ativo por vez. Se marcar como Ativo, o anterior será desativado.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.anos-lectivos.index') }}" class="btn btn-default">Cancelar</a>
                        <button type="submit" class="btn btn-primary float-right">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
