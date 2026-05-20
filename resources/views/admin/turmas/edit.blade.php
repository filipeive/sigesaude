@extends('adminlte::page')
@section('title', 'Editar Turma')

@section('content_header')
    <h1><i class="fas fa-edit mr-2"></i>Editar Turma</h1>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">Editar: {{ $turma->nome }}</h3></div>
                <form action="{{ route('admin.turmas.update', $turma) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="classe_id">Classe <span class="text-danger">*</span></label>
                            <select class="form-control @error('classe_id') is-invalid @enderror" id="classe_id" name="classe_id" required>
                                <option value="">Selecione a classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id', $turma->classe_id) == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome da Turma <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                   id="nome" name="nome" value="{{ old('nome', $turma->nome) }}" required>
                            @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select class="form-control @error('ano_lectivo_id') is-invalid @enderror" id="ano_lectivo_id" name="ano_lectivo_id" required>
                                @foreach($anosLectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ old('ano_lectivo_id', $turma->ano_lectivo_id) == $ano->id ? 'selected' : '' }}>
                                        {{ $ano->ano }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ano_lectivo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2">{{ old('descricao', $turma->descricao) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Atualizar</button>
                        <a href="{{ route('admin.turmas.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection