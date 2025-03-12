@extends('adminlte::page')

@section('title', 'Detalhes da Inscrição')

@section('content_header')
    <h1>Detalhes da Inscrição</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações da Inscrição</h3>
        </div>
        <div class="card-body">
            <p><strong>Referência:</strong> {{ $inscricao->referencia }}</p>
            <p><strong>Status:</strong> 
                <span class="badge {{ $inscricao->status == 'Confirmada' ? 'bg-success' : 'bg-warning' }}">
                    {{ $inscricao->status }}
                </span>
            </p>
            <p><strong>Data de Inscrição:</strong> {{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Disciplinas Inscritas</h3>
        </div>
        <div class="card-body">
            @if ($disciplinas->isEmpty())
                <div class="alert alert-info">
                    Nenhuma disciplina associada a esta inscrição.
                </div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disciplinas as $disciplina)
                            <tr>
                                <td>{{ $disciplina->nome }}</td>
                                <td>
                                    <span class="badge {{ $disciplina->pivot->tipo == 'Normal' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $disciplina->pivot->tipo ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <a href="{{ route('estudante.inscricoes.index') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Página de detalhes da inscrição carregada.');
    </script>
@stop