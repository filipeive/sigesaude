{{-- resources/views/admin/profile/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Editar Perfil')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">Editar Perfil</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Editar Perfil</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <!-- Notificações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i>Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i>Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i>Ocorreram erros:</h5>
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Editar Meu Perfil</h3>
        </div>

        <form method="POST" action="{{ route('admin.perfil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1"></i>Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1"></i>Email</label>
                            @if ($user->tipo === 'admin')
                                <!-- Se o usuário for admin, o campo é editável -->
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $user->email) }}">
                            @else
                                <!-- Se o usuário não for admin, o campo é desativado -->
                                <input type="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" disabled>
                                <!-- Campo oculto para enviar o valor do e-mail ao servidor -->
                                <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="telefone"><i class="fas fa-phone mr-1"></i>Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="genero"><i class="fas fa-venus-mars mr-1"></i>Gênero</label>
                            <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                                name="genero">
                                <option value="">Selecione o gênero</option>
                                @foreach (['Masculino', 'Feminino', 'Outro'] as $genero)
                                    <option value="{{ $genero }}"
                                        {{ old('genero', $user->genero) == $genero ? 'selected' : '' }}>
                                        {{ $genero }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_perfil"><i class="fas fa-camera mr-1"></i>Foto de Perfil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_perfil" name="foto_perfil">
                                    <label class="custom-file-label" for="foto_perfil">
                                        {{ $user->foto_perfil ? basename($user->foto_perfil) : 'Escolher arquivo' }}
                                    </label>
                                </div>
                            </div>
                            @if ($user->foto_perfil)
                                <div class="mt-2">
                                    <img src="{{ asset($user->foto_perfil) }}" alt="Foto atual" class="img-thumbnail"
                                        style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock mr-1"></i>Nova Senha (opcional)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Digite apenas se quiser alterar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"><i class="fas fa-lock mr-1"></i>Confirmar Nova Senha</label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation"
                                placeholder="Confirme a nova senha">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Salvar Alterações
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Voltar
                </a>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('foto_perfil').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
