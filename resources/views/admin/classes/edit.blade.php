@extends('adminlte::page')
@section('title', 'Editar Classe')

@section('content_header')
    <h1><i class="fas fa-edit mr-2"></i>Editar Classe</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Classes</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Editar: {{ $classe->nome }}</h3>
                </div>
                <form action="{{ route('admin.classes.update', $classe) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nome">Nome da Classe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                   id="nome" name="nome" value="{{ old('nome', $classe->nome) }}" required>
                            @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nivel">Nível <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nivel') is-invalid @enderror"
                                   id="nivel" name="nivel" value="{{ old('nivel', $classe->nivel) }}"
                                   min="1" max="12" required>
                            @error('nivel')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $classe->descricao) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Atualizar</button>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
