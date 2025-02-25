@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Criar Perfil de Estudante</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('estudante.store.profile') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="matricula" class="col-md-4 col-form-label text-md-right">Número de Matrícula</label>

                            <div class="col-md-6">
                                <input id="matricula" type="text" class="form-control @error('matricula') is-invalid @enderror" name="matricula" value="{{ old('matricula') }}" required autofocus>

                                @error('matricula')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="curso_id" class="col-md-4 col-form-label text-md-right">Curso</label>

                            <div class="col-md-6">
                                <select id="curso_id" class="form-control @error('curso_id') is-invalid @enderror" name="curso_id" required>
                                    <option value="">Selecione um curso</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                                    @endforeach
                                </select>

                                @error('curso_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="ano_lectivo_id" class="col-md-4 col-form-label text-md-right">Ano Lectivo</label>

                            <div class="col-md-6">
                                <select id="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" name="ano_lectivo_id" required>
                                    <option value="">Selecione um ano lectivo</option>
                                    @foreach($anosLectivos as $anoLectivo)
                                        <option value="{{ $anoLectivo->id }}">{{ $anoLectivo->ano }}</option>
                                    @endforeach
                                </select>

                                @error('ano_lectivo_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="data_nascimento" class="col-md-4 col-form-label text-md-right">Data de Nascimento</label>

                            <div class="col-md-6">
                                <input id="data_nascimento" type="date" class="form-control @error('data_nascimento') is-invalid @enderror" name="data_nascimento" value="{{ old('data_nascimento') }}" required>

                                @error('data_nascimento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="genero" class="col-md-4 col-form-label text-md-right">Gênero</label>

                            <div class="col-md-6">
                                <select id="genero" class="form-control @error('genero') is-invalid @enderror" name="genero" required>
                                    <option value="">Selecione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Feminino">Feminino</option>
                                    <option value="Outro">Outro</option>
                                </select>

                                @error('genero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="ano_ingresso" class="col-md-4 col-form-label text-md-right">Ano de Ingresso</label>

                            <div class="col-md-6">
                                <input id="ano_ingresso" type="number" min="2000" max="2099" step="1" class="form-control @error('ano_ingresso') is-invalid @enderror" name="ano_ingresso" value="{{ old('ano_ingresso', date('Y')) }}" required>

                                @error('ano_ingresso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="turno" class="col-md-4 col-form-label text-md-right">Turno</label>

                            <div class="col-md-6">
                                <select id="turno" class="form-control @error('turno') is-invalid @enderror" name="turno" required>
                                    <option value="">Selecione</option>
                                    <option value="Diurno">Diurno</option>
                                    <option value="Noturno">Noturno</option>
                                </select>

                                @error('turno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="contato_emergencia" class="col-md-4 col-form-label text-md-right">Contato de Emergência</label>

                            <div class="col-md-6">
                                <input id="contato_emergencia" type="text" class="form-control @error('contato_emergencia') is-invalid @enderror" name="contato_emergencia" value="{{ old('contato_emergencia') }}">

                                @error('contato_emergencia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Salvar Perfil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection