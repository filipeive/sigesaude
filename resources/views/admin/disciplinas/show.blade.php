@extends('adminlte::page')

@section('title', 'Detalhes da Disciplina')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-book-open"></i> Detalhes da Disciplina</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>Nome:</strong> {{ $disciplina->nome }}</li>
                <li class="list-group-item"><strong>Curso:</strong> {{ $disciplina->curso->nome }}</li>
                <li class="list-group-item"><strong>Docente:</strong> {{ $disciplina->docente->user->name ?? 'Não definido' }}</li>
            </ul>
            <a href="{{ route('admin.disciplinas.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
    </div>
@stop
