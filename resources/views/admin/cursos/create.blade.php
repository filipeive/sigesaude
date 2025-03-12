@extends('adminlte::page')

@section('title', 'Criar Curso')

@section('content_header')
    <h1>Criar Curso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Criar Curso</button>
            </form>
        </div>
    </div>
@stop