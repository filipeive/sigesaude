{{-- resources/views/admin/users/create.blade.php --}}
@extends('adminlte::page')
@section('title', 'Novo Usuário')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">Novo Usuário</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuários</a></li>
                <li class="breadcrumb-item active">Novo Usuário</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Ocorreram erros:</h5>
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Dados do Novo Usuário</h3>
        </div>
        
        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1"></i>Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Digite o nome completo">
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1"></i>Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="Digite o email">
                        </div>
                        <div class="form-group">
                            <label for="telefone"><i class="fas fa-phone mr-1"></i>Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone') }}"
                                   placeholder="Digite o telefone">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo"><i class="fas fa-user-tag mr-1"></i>Tipo de Usuário</label>
                            <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                                <option value="">Selecione o tipo</option>
                                <option value="admin" {{ old('tipo') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="docente" {{ old('tipo') == 'docente' ? 'selected' : '' }}>Docente</option>
                                <option value="estudante" {{ old('tipo') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                                <option value="financeiro" {{ old('tipo') == 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                                <option value="secretaria" {{ old('tipo') == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="genero"><i class="fas fa-venus-mars mr-1"></i>Gênero</label>
                            <select class="form-control @error('genero') is-invalid @enderror" id="genero" name="genero">
                                <option value="">Selecione o gênero</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Feminino" {{ old('genero') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                <option value="Outro" {{ old('genero') == 'Outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_perfil"><i class="fas fa-camera mr-1"></i>Foto de Perfil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_perfil" name="foto_perfil">
                                    <label class="custom-file-label" for="foto_perfil">Escolher arquivo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock mr-1"></i>Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Digite a senha">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"><i class="fas fa-lock mr-1"></i>Confirmar Senha</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" placeholder="Confirme a senha">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Cadastrar
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Voltar
                </a>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script>
    // Preview da imagem selecionada
    document.getElementById('foto_perfil').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection