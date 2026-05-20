@extends('adminlte::page')

@section('title', 'Editar Docente')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-1"></i> Editar — {{ $docente->user->name }}</h1>
        <a href="{{ route('admin.docentes.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Dados do Docente</h3>
                </div>

                <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <label>Foto de Perfil</label>
                                    <div class="mt-2">
                                        <img id="preview-image"
                                            src="{{ $docente->user->foto_perfil ? asset('storage/' . $docente->user->foto_perfil) : asset('img/default-profile.png') }}"
                                            class="img-circle elevation-2"
                                            style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                            onclick="document.getElementById('foto_perfil').click();" alt="Foto">
                                    </div>
                                    <div class="mt-2">
                                        <input type="file" name="foto_perfil" id="foto_perfil" class="form-control-file d-none" accept="image/*">
                                        <label for="foto_perfil" class="btn btn-sm btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Alterar foto
                                        </label>
                                    </div>
                                    @error('foto_perfil')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-user mr-2"></i>Informações Pessoais</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nome Completo <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $docente->user->name) }}" required>
                                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>E-mail <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $docente->user->email) }}" required>
                                            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Telefone <span class="text-danger">*</span></label>
                                            <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror"
                                                value="{{ old('telefone', $docente->user->telefone) }}" required>
                                            @error('telefone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-graduation-cap mr-2"></i>Informações Acadêmicas</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Departamento <span class="text-danger">*</span></label>
                                            <select name="departamento_id" class="form-control select2 @error('departamento_id') is-invalid @enderror" required>
                                                <option value="">Selecione...</option>
                                                @foreach($departamentos as $id => $nome)
                                                    <option value="{{ $id }}" {{ old('departamento_id', $docente->departamento_id) == $id ? 'selected' : '' }}>{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                            @error('departamento_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Turma Titular (Coordenador)</label>
                                            <select name="turma_id" id="turma_id" class="form-control select2 @error('turma_id') is-invalid @enderror">
                                                <option value="">— Sem turma atribuída —</option>
                                                @foreach($turmas as $t)
                                                    <option value="{{ $t->id }}" {{ old('turma_id', $docente->turma_id) == $t->id ? 'selected' : '' }}>
                                                        {{ $t->classe->nome ?? '' }} {{ $t->nome }} ({{ $t->ano_serie }}º Ano)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Se for coordenador de turma, selecione-a.</small>
                                            @error('turma_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Formação <span class="text-danger">*</span></label>
                                            <input type="text" name="formacao" class="form-control @error('formacao') is-invalid @enderror"
                                                value="{{ old('formacao', $docente->formacao) }}" required>
                                            @error('formacao')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Anos de Experiência</label>
                                            <input type="number" name="anos_experiencia" class="form-control @error('anos_experiencia') is-invalid @enderror"
                                                value="{{ old('anos_experiencia', $docente->anos_experiencia) }}" min="0">
                                            @error('anos_experiencia')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                <option value="Ativo" {{ old('status', $docente->status) == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                                <option value="Inativo" {{ old('status', $docente->status) == 'Inativo' ? 'selected' : '' }}>Inativo</option>
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
                            <a href="{{ route('admin.docentes.show', $docente->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Salvar Alterações
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
        $('.select2').select2({ theme: 'bootstrap4', placeholder: 'Selecione...' });
        $('#foto_perfil').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => { $('#preview-image').attr('src', e.target.result); };
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endsection
