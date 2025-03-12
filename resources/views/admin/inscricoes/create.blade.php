@extends('adminlte::page')

@section('title', 'Criar Inscrição')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-plus"></i> Criar Inscrição</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <form action="{{ route('admin.inscricoes.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="estudante_id"><strong>Estudante</strong></label>
                    <select name="estudante_id" id="estudante_id" class="form-control" required>
                        @foreach($estudantes as $estudante)
                            <option value="{{ $estudante->id }}">{{ $estudante->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="ano_lectivo_id"><strong>Ano Lectivo</strong></label>
                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control" required>
                        @if($anoAtual)
                            <option value="{{ $anoAtual->id }}">{{ $anoAtual->ano }}</option>
                        @else
                            <option value="">Nenhum ano letivo ativo encontrado</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="semestre"><strong>Semestre</strong></label>
                    <select name="semestre" id="semestre" class="form-control" required>
                        <option value="1">1º Semestre</option>
                        <option value="2">2º Semestre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="disciplinas"><strong>Disciplinas</strong></label>
                    <select name="disciplinas[]" id="disciplinas" class="form-control" multiple required>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}">{{ $disciplina->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Criar Inscrição</button>
                <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </form>
        </div>
    </div>
@stop