@extends('adminlte::page')

@section('title', 'Detalhes do Curso')

@section('content_header')
    <h1>Detalhes do Curso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li><strong>Nome:</strong> {{ $curso->nome }}</li>
                <li><strong>Descrição:</strong> {{ $curso->descricao ?? 'N/A' }}</li>
                <li><strong>Total de Estudantes:</strong> {{ $curso->estudantes_count }}</li>
                <li><strong>Total de Disciplinas:</strong> {{ $curso->disciplinas_count }}</li>
            </ul>
        </div>
    </div>
@stop