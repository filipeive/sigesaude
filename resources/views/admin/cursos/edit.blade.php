@extends('adminlte::page')

@section('title', 'Editar Curso')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-edit"></i> Editar Curso</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nome"><strong>Nome</strong></label>
                    <input type="text" name="nome" id="nome" class="form-control" value="{{ $curso->nome }}" required>
                </div>
                <div class="form-group">
                    <label for="descricao"><strong>Descrição</strong></label>
                    <textarea name="descricao" id="descricao" class="form-control">{{ $curso->descricao }}</textarea>
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Atualizar Curso</button>
                <a href="{{ route('admin.cursos.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
@stop