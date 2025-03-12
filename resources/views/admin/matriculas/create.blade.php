@extends('adminlte::page')

@section('title', 'Criar Matrícula')

@section('content_header')
    <h1>Criar Matrícula</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.matriculas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="estudante_id">Estudante</label>
                    <select name="estudante_id" id="estudante_id" class="form-control" required>
                        @foreach($estudantes as $estudante)
                            <option value="{{ $estudante->id }}">{{ $estudante->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="disciplina_id">Disciplina</label>
                    <select name="disciplina_id" id="disciplina_id" class="form-control" required>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}">{{ $disciplina->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Criar Matrícula</button>
            </form>
        </div>
    </div>
@stop