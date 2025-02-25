@extends('adminlte::page')

@section('title', 'Meu Perfil')

@section('content_header')
    <h1>Meu Perfil</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="breadcrumb-item active">Meu Perfil</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar Perfil</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('estudante.perfil.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Nome Completo</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $estudante->user->name) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $estudante->user->email) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="telefone" class="col-sm-2 col-form-label">Telefone</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone', $estudante->user->telefone) }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="genero" class="col-sm-2 col-form-label">Gênero</label>
                    <div class="col-sm-10">
                        <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero">
                            <option value="">Selecione o gênero</option>
                            <option value="Masculino" {{ old('genero', $estudante->user->genero) === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Feminino" {{ old('genero', $estudante->user->genero) === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                            <option value="Outro" {{ old('genero', $estudante->user->genero) === 'Outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="foto_perfil" class="col-sm-2 col-form-label">Foto de Perfil</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file @error('foto_perfil') is-invalid @enderror" id="foto_perfil" name="foto_perfil">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Nova Senha</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-sm-2 col-form-label">Confirmar Senha</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="{{ route('estudante.perfil.index') }}" class="btn btn-default">Voltar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection