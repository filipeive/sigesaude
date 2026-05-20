@extends('adminlte::page')

@section('title', 'Cadastrar Docente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-chalkboard-teacher mr-1"></i> Cadastrar Novo Docente</h1>
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
                            <!-- Foto -->
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <label>Foto de Perfil</label>
                                    <div class="mt-2">
                                        <img id="preview-image" src="{{ asset('img/default-profile.png') }}"
                                            class="img-circle elevation-2"
                                            style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('foto_perfil').click();" alt="Foto">
                                    </div>
                                    <div class="mt-2">
                                        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control-file d-none" accept="image/*">
                                        <label for="foto_perfil" class="btn btn-sm btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Selecionar foto
                                        </label>
                                    </div>
                                    @error('foto_perfil')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pessoais -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-user mr-2"></i>Informações Pessoais</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required autocomplete="name">
                                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>E-mail <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror"
                                                value="{{ old('telefone') }}" required>
                                            @error('telefone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Senha <span class="text-danger">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                                            <small class="form-text text-muted">Mínimo de 8 caracteres.</small>
                                            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Académicas -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5 class="border-bottom pb-2 mb-3"><i class="fas fa-graduation-cap mr-2"></i>Informações Acadêmicas</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Departamento <span class="text-danger">*</span></label>
                                            <select name="departamento_id" class="form-control select2 @error('departamento_id') is-invalid @enderror" required>
                                                <option value="">Selecione...</option>
                                                @foreach($departamentos as $id => $nome)
                                                    <option value="{{ $id }}" {{ old('departamento_id') == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                            @error('departamento_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Turma Titular (Coordenador)</label>
                                            <select name="turma_id" class="form-control select2 @error('turma_id') is-invalid @enderror">
                                                <option value="">— Sem turma atribuída —</option>
                                                @foreach($turmas as $t)
                                                    <option value="{{ $t->id }}" {{ old('turma_id') == $t->id ? 'selected' : '' }}>
                                                        {{ $t->classe->nome ?? '' }} {{ $t->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Se este docente for coordenador de turma, selecione-a aqui.</small>
                                            @error('turma_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Formação Acadêmica <span class="text-danger">*</span></label>
                                            <input type="text" name="formacao" class="form-control @error('formacao') is-invalid @enderror"
                                                value="{{ old('formacao') }}" required>
                                            @error('formacao')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Anos de Experiência</label>
                                            <input type="number" name="anos_experiencia" class="form-control @error('anos_experiencia') is-invalid @enderror"
                                                value="{{ old('anos_experiencia') }}" min="0">
                                            @error('anos_experiencia')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="Inativo" {{ old('status') == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                                            </select>
                                            @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Salvar Docente
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
        $('.select2').select2({ theme: 'bootstrap4', placeholder: 'Selecione uma opção' });
        $('#foto_perfil').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => { $('#preview-image').attr('src', e.target.result); };
            reader.readAsDataURL(this.files[0]);
        });
        $('#toggle-password')?.click(function () {
            const p = $('#password');
            p.attr('type', p.attr('type') === 'password' ? 'text' : 'password');
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endsection
