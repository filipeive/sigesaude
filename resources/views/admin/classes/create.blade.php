@extends('adminlte::page')
@section('title', 'Nova Classe')

@section('content_header')
    <h1><i class="fas fa-plus-circle mr-2"></i>Nova Classe</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Classes</a></li>
        <li class="breadcrumb-item active">Nova</li>
    </ol>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados da Classe</h3>
                </div>
                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nome">Nome da Classe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                   id="nome" name="nome" value="{{ old('nome') }}"
                                   placeholder="Ex: 10ª Classe" required>
                            @error('nome')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nivel">Nível (número) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nivel') is-invalid @enderror"
                                   id="nivel" name="nivel" value="{{ old('nivel') }}"
                                   min="1" max="12" placeholder="Ex: 10" required>
                            <small class="text-muted">O número da classe (1 a 12)</small>
                            @error('nivel')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror"
                                      id="descricao" name="descricao" rows="3"
                                      placeholder="Descrição opcional da classe">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Salvar
                        </button>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
