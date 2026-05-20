{{-- resources/views/estudante/perfil/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('content_header')
    <h1><i class="fas fa-user-edit mr-2"></i>Editar Perfil</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('estudante.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('estudante.perfil.index') }}">Perfil</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Ocorreram erros:</h5>
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-address-card mr-2"></i>Dados Pessoais</h3>
        </div>

        <form method="POST" action="{{ route('estudante.perfil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <!-- Foto de Perfil -->
                    <div class="col-md-3 text-center">
                        <label class="d-block"><i class="fas fa-camera mr-1"></i> Foto de Perfil</label>
                        <img src="{{ $estudante->user->foto_perfil_url ?? asset('vendor/adminlte/dist/img/user.jpg') }}"
                             alt="Foto de Perfil"
                             class="img-circle img-fluid mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <input type="file" class="form-control-file d-block mx-auto @error('foto_perfil') is-invalid @enderror"
                               id="foto_perfil" name="foto_perfil" accept="image/*">
                        @error('foto_perfil')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Dados de Login -->
                    <div class="col-md-9">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label"><i class="fas fa-user mr-1"></i> Nome Completo</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name', $estudante->user->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label"><i class="fas fa-envelope mr-1"></i> E-mail</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email', $estudante->user->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefone" class="col-sm-2 col-form-label"><i class="fas fa-phone mr-1"></i> Telefone</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                       id="telefone" name="telefone"
                                       value="{{ old('telefone', $estudante->user->telefone) }}">
                                @error('telefone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="genero" class="col-sm-2 col-form-label"><i class="fas fa-venus-mars mr-1"></i> Género</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero">
                                    <option value="">Selecione</option>
                                    <option value="Masculino" {{ old('genero', $estudante->user->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Feminino" {{ old('genero', $estudante->user->genero) == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                    <option value="Outro" {{ old('genero', $estudante->user->genero) == 'Outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('genero')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fas fa-key mr-1"></i> Alterar Senha</h5>
                        <p class="text-muted">Deixe em branco se não quiser alterar.</p>
                        <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label"><i class="fas fa-lock mr-1"></i> Nova Senha</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Mínimo 8 caracteres">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class="col-sm-2 col-form-label"><i class="fas fa-lock mr-1"></i> Confirmar</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" placeholder="Repita a nova senha">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Salvar Alterações
                </button>
                <a href="{{ route('estudante.perfil.index') }}" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left mr-1"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
<script>
    // Atualizar o nome do arquivo no label
    document.getElementById('foto_perfil')?.addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Escolher arquivo';
        var label = e.target.nextElementSibling;
        if (label && label.classList.contains('custom-file-label')) {
            label.innerText = fileName;
        }
    });
</script>
@stop
