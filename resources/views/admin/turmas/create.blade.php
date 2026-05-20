@extends('adminlte::page')
@section('title', 'Nova Turma')

@section('content_header')
    <h1><i class="fas fa-plus-circle mr-2"></i>Nova Turma</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.turmas.index') }}">Turmas</a></li>
        <li class="breadcrumb-item active">Nova</li>
    </ol>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados da Turma</h3>
                </div>
                <form action="{{ route('admin.turmas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="classe_id">Classe <span class="text-danger">*</span></label>
                            <select class="form-control @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                                <option value="">Selecione a classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nome }} ({{ $classe->nivel }}º nível)
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="nome">Nome da Turma <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                   id="nome" name="nome" value="{{ old('nome') }}"
                                   placeholder="Ex: A, B, C ou Turma A" required>
                            <small class="text-muted">Secção dentro da classe (Ex: A, B, C)</small>
                            @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select class="form-control @error('ano_lectivo_id') is-invalid @enderror" id="ano_lectivo_id" name="ano_lectivo_id" required>
                                <option value="">Selecione o ano lectivo</option>
                                @foreach($anosLectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ old('ano_lectivo_id') == $ano->id ? 'selected' : '' }}>
                                        {{ $ano->ano }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ano_lectivo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2" placeholder="Descrição opcional">{{ old('descricao') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Salvar</button>
                        <a href="{{ route('admin.turmas.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection