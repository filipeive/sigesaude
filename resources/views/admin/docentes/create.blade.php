@extends('adminlte::page')

@section('title', 'Cadastrar Novo Docente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-chalkboard-teacher"></i> Cadastrar Novo Docente</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.docentes.index') }}">Docentes</a></li>
                <li class="breadcrumb-item active">Cadastrar</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Dados do Docente</h3>
                </div>
                
                <form action="{{ route('admin.docentes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <label for="foto_perfil">Foto de Perfil</label>
                                    <div class="mt-2">
                                        <img id="preview-image" src="{{ asset('img/default-profile.png') }}" 
                                            class="img-circle elevation-2" 
                                            style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('foto_perfil').click();"
                                            alt="Foto de perfil">
                                    </div>
                                    <div class="mt-2">
                                        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control-file d-none" accept="image/*">
                                        <label for="foto_perfil" class="btn btn-sm btn-primary">
                                            <i class="fas fa-upload"></i> Selecionar foto
                                        </label>
                                    </div>
                                    @error('foto_perfil')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2">Informações Pessoais</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">E-mail <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telefone">Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" required>
                                            @error('telefone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Senha <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">A senha deve ter pelo menos 8 caracteres.</small>
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2">Informações Acadêmicas</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departamento_id">Departamento <span class="text-danger">*</span></label>
                                            <select name="departamento_id" id="departamento_id" class="form-control select2 @error('departamento_id') is-invalid @enderror" required>
                                                <option value="">Selecione um departamento</option>
                                                @foreach($departamentos as $id => $nome)
                                                    <option value="{{ $id }}" {{ old('departamento_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                            @error('departamento_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="formacao">Formação Acadêmica <span class="text-danger">*</span></label>
                                            <input type="text" name="formacao" id="formacao" class="form-control @error('formacao') is-invalid @enderror" value="{{ old('formacao') }}" required>
                                            @error('formacao')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="anos_experiencia">Anos de Experiência</label>
                                            <input type="number" name="anos_experiencia" id="anos_experiencia" class="form-control @error('anos_experiencia') is-invalid @enderror" value="{{ old('anos_experiencia') }}" min="0">
                                            @error('anos_experiencia')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="Inativo" {{ old('status') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2">Cursos Associados</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="cursos">Cursos <span class="text-danger">*</span></label>
                                            <select name="cursos[]" id="cursos" class="form-control select2 @error('cursos') is-invalid @enderror" multiple required>
                                                @foreach($cursos as $id => $nome)
                                                    <option value="{{ $id }}" {{ (is_array(old('cursos')) && in_array($id, old('cursos'))) ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Selecione os cursos que este docente irá ministrar.</small>
                                            @error('cursos')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Selecione uma opção'
        });
        
        // Visualização prévia da imagem
        $('#foto_perfil').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
                $('#preview-image').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });
        
        // Toggle de visualização da senha
        $('#toggle-password').click(function() {
            const passwordField = $('#password');
            const passwordFieldType = passwordField.attr('type');
            const newType = passwordFieldType === 'password' ? 'text' : 'password';
            const newIcon = passwordFieldType === 'password' ? 'fa-eye-slash' : 'fa-eye';
            
            passwordField.attr('type', newType);
            $(this).find('i').removeClass('fa-eye fa-eye-slash').addClass(newIcon);
        });
    });
</script>
@endsection