@extends('adminlte::page')

@section('title', 'Editar Inscrição')

@section('content_header')
    <h1>Editar Inscrição</h1>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Atualizar Informações da Inscrição</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('estudante.inscricoes.update', $inscricao->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="semestre">Semestre</label>
                            <select name="semestre" id="semestre" class="form-control">
                                <option value="1" {{ $inscricao->semestre == 1 ? 'selected' : '' }}>1º Semestre</option>
                                <option value="2" {{ $inscricao->semestre == 2 ? 'selected' : '' }}>2º Semestre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Pendente" {{ $inscricao->status == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="Confirmada" {{ $inscricao->status == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="Cancelada" {{ $inscricao->status == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" name="valor" id="valor" class="form-control" value="{{ $inscricao->valor }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data_inscricao">Data da Inscrição</label>
                            <input type="date" name="data_inscricao" id="data_inscricao" class="form-control" value="{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}">
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('estudante.inscricoes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card-outline {
            border-top: 3px solid !important;
        }
        .card-primary {
            border-top-color: #007bff !important;
        }
    </style>
@stop