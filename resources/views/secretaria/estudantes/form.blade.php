@extends('adminlte::page')

@section('title', isset($estudante) ? 'Editar Estudante' : 'Novo Estudante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-graduate mr-2"></i>{{ isset($estudante) ? 'Editar Estudante' : 'Novo Estudante' }}</h1>
        <a href="{{ route('secretaria.estudantes.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Estudantes
        </a>
    </div>
@stop

@section('content')
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Corrija os campos destacados.</strong>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Dados do Estudante</h3>
        </div>
        <form method="POST" action="{{ isset($estudante) ? route('secretaria.estudantes.update', $estudante) : route('secretaria.estudantes.store') }}">
            @csrf
            @isset($estudante)
                @method('PUT')
            @endisset

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome completo</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $estudante->user->name ?? '') }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $estudante->user->email ?? '') }}" required>
                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @empty($estudante)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    @endempty
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone', $estudante->user->telefone ?? '') }}" required>
                            @error('telefone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Número de matrícula</label>
                            <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror" value="{{ old('matricula', $estudante->matricula ?? '') }}" required>
                            @error('matricula')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Turma</label>
                            <select name="turma_id" class="form-control @error('turma_id') is-invalid @enderror" required>
                                <option value="">Selecione</option>
                                @foreach($turmas as $turma)
                                    <option value="{{ $turma->id }}" {{ old('turma_id', $estudante->turma_id ?? '') == $turma->id ? 'selected' : '' }}>
                                        {{ $turma->classe?->nome }} {{ $turma->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('turma_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ano lectivo</label>
                            <select name="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" required>
                                <option value="">Selecione</option>
                                @foreach($anosLectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ old('ano_lectivo_id', $estudante->ano_lectivo_id ?? '') == $ano->id ? 'selected' : '' }}>{{ $ano->ano }}</option>
                                @endforeach
                            </select>
                            @error('ano_lectivo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ano de ingresso</label>
                            <input type="number" name="ano_ingresso" class="form-control @error('ano_ingresso') is-invalid @enderror" value="{{ old('ano_ingresso', $estudante->ano_ingresso ?? date('Y')) }}" required>
                            @error('ano_ingresso')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento', isset($estudante) && $estudante->data_nascimento ? \Carbon\Carbon::parse($estudante->data_nascimento)->format('Y-m-d') : '') }}" required>
                            @error('data_nascimento')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gênero</label>
                            <select name="genero" class="form-control @error('genero') is-invalid @enderror" required>
                                @foreach(['Masculino', 'Feminino', 'Outro'] as $genero)
                                    <option value="{{ $genero }}" {{ old('genero', $estudante->genero ?? '') === $genero ? 'selected' : '' }}>{{ $genero }}</option>
                                @endforeach
                            </select>
                            @error('genero')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Turno</label>
                            <select name="turno" class="form-control @error('turno') is-invalid @enderror" required>
                                @foreach(['Diurno', 'Noturno'] as $turno)
                                    <option value="{{ $turno }}" {{ old('turno', $estudante->turno ?? '') === $turno ? 'selected' : '' }}>{{ $turno }}</option>
                                @endforeach
                            </select>
                            @error('turno')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @isset($estudante)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    @foreach(['Ativo', 'Inativo', 'Concluído', 'Desistente'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $estudante->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    @endisset
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Contacto de emergência</label>
                            <input type="text" name="contato_emergencia" class="form-control @error('contato_emergencia') is-invalid @enderror" value="{{ old('contato_emergencia', $estudante->contato_emergencia ?? '') }}" required>
                            @error('contato_emergencia')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Salvar
                </button>
                <a href="{{ route('secretaria.estudantes.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@stop
