@extends('adminlte::page')
@section('title', 'Editar Ano Lectivo')

@section('content_header')
    <h1><i class="fas fa-calendar-edit mr-2"></i>Editar Ano Lectivo</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Editar dados do ano lectivo {{ $anoLectivo->ano }}</h3>
                </div>
                <form action="{{ route('admin.anos-lectivos.update', $anoLectivo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="ano">Ano</label>
                            <input type="number" name="ano" id="ano" class="form-control @error('ano') is-invalid @enderror" 
                                   value="{{ old('ano', $anoLectivo->ano) }}" required>
                            @error('ano')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="Ativo" {{ old('status', $anoLectivo->status) == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="Inativo" {{ old('status', $anoLectivo->status) == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($anoLectivo->status != 'Ativo')
                                <small class="text-muted">Nota: Se marcar como Ativo, o ano lectivo atualmente ativo será desativado.</small>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.anos-lectivos.index') }}" class="btn btn-default">Cancelar</a>
                        <button type="submit" class="btn btn-warning float-right">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
