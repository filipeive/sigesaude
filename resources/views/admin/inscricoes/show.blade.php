@extends('adminlte::page')

@section('title', 'Detalhes da Inscrição')

@section('content_header')
    <h1 class="text-primary"><i class="fas fa-info-circle"></i> Detalhes da Inscrição</h1>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5><strong>Estudante:</strong> {{ $inscricao->estudante->user->name }}</h5>
                    <h5><strong>Curso:</strong> {{ $inscricao->estudante->curso->nome }}</h5>
                    <h5><strong>Nível:</strong> {{ $inscricao->estudante->nivel->nome }}</h5>
                    <h5><strong>Ano Lectivo:</strong> {{ $inscricao->anoLectivo->ano }}</h5>
                    <h5><strong>Semestre:</strong> {{ $inscricao->semestre }}</h5>
                    <h5><strong>Data de Inscrição:</strong> {{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</h5>
                    <h5><strong>Status:</strong> <span class="badge bg-{{ $inscricao->status == 'Confirmada' ? 'success' : 'warning' }}">{{ $inscricao->status }}</span></h5>
                </div>
                <div class="col-md-6">
                    <h5><strong>Disciplinas:</strong></h5>
                    <ul>
                        @foreach($inscricao->disciplinas as $disciplina)
                            <li>{{ $disciplina->nome }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <a href="{{ route('admin.inscricoes.edit', $inscricao->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Inscrição
            </a>
            <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop