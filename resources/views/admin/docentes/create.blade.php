{{-- resources/views/admin/docentes/create.blade.php --}}
@extends('adminlte::page')

@section('title', isset($docente) ? 'Editar Docente' : 'Novo Docente')

@section('content_header')
    <h1>{{ isset($docente) ? 'Editar Docente' : 'Novo Docente' }}</h1>
@stop

@section('content')
    <!-- Notificações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i>Sucesso:</h5>
            {{ session('success') }}
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
            <h3 class="card-title">Informações do Docente</h3>
        </div>

        <form action="{{ isset($docente) ? route('admin.docentes.update', $docente->id) : route('admin.docentes.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($docente))
                @method('PUT')
            @endif

            <div class="card-body">
                <div class="row">
                    <!-- Coluna da Foto e Informações Básicas -->
                    <div class="col-md-3">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Foto de Perfil</h3>
                            </div>
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ isset($docente) ? $docente->user->foto_perfil_url : '/img/default-profile.png' }}"
                                        alt="Foto do docente">
                                </div>
                                <div class="form-group mt-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('foto_perfil') is-invalid @enderror"
                                            id="foto_perfil" name="foto_perfil">
                                        <label class="custom-file-label" for="foto_perfil">Escolher foto</label>
                                    </div>
                                    @error('foto_perfil')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna das Informações Detalhadas -->
                    <div class="col-md-9">
                        <div class="row">
                            <!-- Informações Pessoais -->
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Informações Pessoais</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nome Completo</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                value="{{ old('name', isset($docente) ? $docente->user->name : '') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email"
                                                value="{{ old('email', isset($docente) ? $docente->user->email : '') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if (!isset($docente))
                                            <div class="form-group">
                                                <label for="password">Senha</label>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required>
                                                @error('password')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="telefone">Telefone</label>
                                            <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                                id="telefone" name="telefone"
                                                value="{{ old('telefone', isset($docente) ? $docente->user->telefone : '') }}" required>
                                            @error('telefone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Profissionais -->
                            <div class="col-md-6">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Informações Profissionais</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="departamento">Departamento</label>
                                            <input type="text" class="form-control @error('departamento') is-invalid @enderror"
                                                id="departamento" name="departamento"
                                                value="{{ old('departamento', isset($docente) ? $docente->departamento : '') }}" required>
                                            @error('departamento')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="formacao">Formação Acadêmica</label>
                                            <input type="text" class="form-control @error('formacao') is-invalid @enderror"
                                                id="formacao" name="formacao"
                                                value="{{ old('formacao', isset($docente) ? $docente->formacao : '') }}" required>
                                            @error('formacao')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="anos_experiencia">Anos de Experiência</label>
                                            <input type="number" class="form-control @error('anos_experiencia') is-invalid @enderror"
                                                id="anos_experiencia" name="anos_experiencia"
                                                value="{{ old('anos_experiencia', isset($docente) ? $docente->anos_experiencia : '') }}" required>
                                            @error('anos_experiencia')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="Ativo"
                                                    {{ old('status', isset($docente) ? $docente->status : '') == 'Ativo' ? 'selected' : '' }}>
                                                    Ativo</option>
                                                <option value="Inativo"
                                                    {{ old('status', isset($docente) ? $docente->status : '') == 'Inativo' ? 'selected' : '' }}>
                                                    Inativo</option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="cursos">Cursos Associados</label>
                                            <select class="form-control select2 @error('cursos') is-invalid @enderror"
                                                id="cursos" name="cursos[]" multiple required>
                                                @foreach ($cursos as $id => $nome)
                                                    <option value="{{ $id }}"
                                                        {{ (isset($docente) && $docente->cursos->contains($id)) ? 'selected' : '' }}>
                                                        {{ $nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('cursos')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>
                    {{ isset($docente) ? 'Atualizar' : 'Cadastrar' }}
                </button>
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inicializa Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Preview da imagem
            $('#foto_perfil').change(function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    $('.profile-user-img').attr('src', reader.result);
                }
                if (file) {
                    reader.readAsDataURL(file);
                }
            });

            // BS Custom File Input
            bsCustomFileInput.init();
        });
    </script>
@stop