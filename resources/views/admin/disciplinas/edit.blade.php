@extends('adminlte::page')

@section('title', 'Editar Disciplina')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-edit"></i> Editar Disciplina</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('admin.disciplinas.update', $disciplina->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="nome"><strong>Nome</strong></label>
                    <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $disciplina->nome) }}" required>
                </div>

                <div class="form-group">
                    <label for="nivel_id"><strong>Nível</strong></label>
                    <select name="nivel_id" id="nivel_id" class="form-control" required>
                        @foreach($niveis as $nivel)
                            <option value="{{ $nivel->id }}" {{ old('nivel_id', $disciplina->nivel_id) == $nivel->id ? 'selected' : '' }}>{{ $nivel->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="curso_id"><strong>Curso</strong></label>
                    <select name="curso_id" id="curso_id" class="form-control" required>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $disciplina->curso_id) == $curso->id ? 'selected' : '' }}>{{ $curso->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="docente_id"><strong>Docente</strong></label>
                    <select name="docente_id" id="docente_id" class="form-control" required>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ old('docente_id', $disciplina->docente_id) == $docente->id ? 'selected' : '' }}>{{ $docente->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Atualizar Disciplina</button>
                <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
@stop