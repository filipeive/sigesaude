@extends('adminlte::page')

@section('title', 'Editar Disciplina')

@section('content_header')
    <h1>Editar Disciplina</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.disciplinas.update', $disciplina->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" value="{{ $disciplina->nome }}" required>
                </div>
                <div class="form-group">
                    <label for="curso_id">Curso</label>
                    <select name="curso_id" id="curso_id" class="form-control" required>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ $disciplina->curso_id == $curso->id ? 'selected' : '' }}>{{ $curso->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="docente_id">Docente</label>
                    <select name="docente_id" id="docente_id" class="form-control" required>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ $disciplina->docente_id == $docente->id ? 'selected' : '' }}>{{ $docente->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar Disciplina</button>
            </form>
        </div>
    </div>
@stop