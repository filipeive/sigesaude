@extends('adminlte::page')
@section('title', 'Nova Disciplina')

@section('content_header')
    <h1><i class="fas fa-plus-circle mr-2"></i>Nova Disciplina</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.disciplinas.index') }}">Disciplinas</a></li>
        <li class="breadcrumb-item active">Nova</li>
    </ol>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Dados da Disciplina</h3></div>
                <form action="{{ route('admin.disciplinas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nome">Nome da Disciplina <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                   id="nome" name="nome" value="{{ old('nome') }}"
                                   placeholder="Ex: Matemática" required>
                            @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

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
                            <small class="text-muted">A classe/nível a que esta disciplina pertence</small>
                            @error('classe_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label for="docente_id">Docente <span class="text-danger">*</span></label>
                            <select class="form-control @error('docente_id') is-invalid @enderror" id="docente_id" name="docente_id" required>
                                <option value="">Selecione o docente</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->user->name ?? 'Docente #'.$docente->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('docente_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nivel_id">Nível de Dificuldade <span class="text-danger">*</span></label>
                                    <select class="form-control @error('nivel_id') is-invalid @enderror" id="nivel_id" name="nivel_id" required>
                                        <option value="">Selecione</option>
                                        @foreach($niveis as $id => $nome)
                                            <option value="{{ $id }}" {{ old('nivel_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('nivel_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="carga_horaria">Carga Horária</label>
                                    <input type="text" class="form-control @error('carga_horaria') is-invalid @enderror"
                                           id="carga_horaria" name="carga_horaria" value="{{ old('carga_horaria') }}"
                                           placeholder="Ex: 4h/semana">
                                    @error('carga_horaria')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Salvar</button>
                        <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection