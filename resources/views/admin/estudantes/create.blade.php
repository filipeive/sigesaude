{{-- resources/views/admin/estudantes/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Novo Estudante')

@section('content_header')
    <h1>{{ isset($estudante) ? 'Editar Estudante' : 'Novo Estudante' }}</h1>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($estudante) ? 'Editar Informações' : 'Informações do Estudante' }}</h3>
                </div>

                <form
                    action="{{ isset($estudante) ? route('admin.estudantes.update', $estudante->id) : route('admin.estudantes.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($estudante))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row">
                            {{-- Informações Pessoais --}}
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
                                                value="{{ old('name', $estudante->user->name ?? '') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="email">E-mail</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email"
                                                value="{{ old('email', $estudante->user->email ?? '') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if (!isset($estudante))
                                            <div class="form-group">
                                                <label for="password">Senha</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" required>
                                                @error('password')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="telefone">Telefone</label>
                                            <input type="text"
                                                class="form-control @error('telefone') is-invalid @enderror" id="telefone"
                                                name="telefone"
                                                value="{{ old('telefone', $estudante->user->telefone ?? '') }}" required>
                                            @error('telefone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="foto_perfil">Foto de Perfil</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('foto_perfil') is-invalid @enderror"
                                                        id="foto_perfil" name="foto_perfil">
                                                    <label class="custom-file-label" for="foto_perfil">Escolher
                                                        arquivo</label>
                                                </div>
                                            </div>
                                            @error('foto_perfil')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Informações Acadêmicas --}}
                            <div class="col-md-6">
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Informações Acadêmicas</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="matricula">Número de Matrícula</label>
                                            <input type="text"
                                                class="form-control @error('matricula') is-invalid @enderror" id="matricula"
                                                name="matricula"
                                                value="{{ old('matricula', $estudante->matricula ?? '') }}" required>
                                            @error('matricula')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="curso_id">Curso</label>
                                            <select class="form-control select2 @error('curso_id') is-invalid @enderror"
                                                id="curso_id" name="curso_id" required>
                                                <option value="">Selecione um curso</option>
                                                @foreach ($cursos as $id => $nome)
                                                    <option value="{{ $id }}"
                                                        {{ old('curso_id', $estudante->curso_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('curso_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="ano_ingresso">Ano de Ingresso</label>
                                            <input type="number"
                                                class="form-control @error('ano_ingresso') is-invalid @enderror"
                                                id="ano_ingresso" name="ano_ingresso"
                                                value="{{ old('ano_ingresso', $estudante->ano_ingresso ?? date('Y')) }}"
                                                required>
                                            @error('ano_ingresso')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="ano_lectivo_id">Ano Letivo</label>
                                            <select
                                                class="form-control select2 @error('ano_lectivo_id') is-invalid @enderror"
                                                id="ano_lectivo_id" name="ano_lectivo_id" required>
                                                <option value="">Selecione o ano letivo</option>
                                                @foreach ($anosLectivos as $id => $ano)
                                                    <option value="{{ $id }}"
                                                        {{ old('ano_lectivo_id', $estudante->ano_lectivo_id ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $ano }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('ano_lectivo_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="data_nascimento">Data de Nascimento</label>
                                            <input type="date"
                                                class="form-control @error('data_nascimento') is-invalid @enderror"
                                                id="data_nascimento" name="data_nascimento"
                                                value="{{ old('data_nascimento', $estudante->data_nascimento ?? '') }}"
                                                required>
                                            @error('data_nascimento')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="genero">Gênero</label>
                                            <select class="form-control @error('genero') is-invalid @enderror"
                                                id="genero" name="genero" required>
                                                <option value="">Selecione o gênero</option>
                                                <option value="Masculino"
                                                    {{ old('genero', $estudante->genero ?? '') == 'Masculino' ? 'selected' : '' }}>
                                                    Masculino</option>
                                                <option value="Feminino"
                                                    {{ old('genero', $estudante->genero ?? '') == 'Feminino' ? 'selected' : '' }}>
                                                    Feminino</option>
                                                <option value="Outro"
                                                    {{ old('genero', $estudante->genero ?? '') == 'Outro' ? 'selected' : '' }}>
                                                    Outro</option>
                                            </select>
                                            @error('genero')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="turno">Turno</label>
                                            <select class="form-control @error('turno') is-invalid @enderror"
                                                id="turno" name="turno" required>
                                                <option value="">Selecione o turno</option>
                                                <option value="Diurno"
                                                    {{ old('turno', $estudante->turno ?? '') == 'Diurno' ? 'selected' : '' }}>
                                                    Diurno</option>
                                                <option value="Noturno"
                                                    {{ old('turno', $estudante->turno ?? '') == 'Noturno' ? 'selected' : '' }}>
                                                    Noturno</option>
                                            </select>
                                            @error('turno')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if (isset($estudante))
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="form-control @error('status') is-invalid @enderror"
                                                    id="status" name="status" required>
                                                    <option value="Ativo"
                                                        {{ old('status', $estudante->status) == 'Ativo' ? 'selected' : '' }}>
                                                        Ativo</option>
                                                    <option value="Inativo"
                                                        {{ old('status', $estudante->status) == 'Inativo' ? 'selected' : '' }}>
                                                        Inativo</option>
                                                    <option value="Concluído"
                                                        {{ old('status', $estudante->status) == 'Concluído' ? 'selected' : '' }}>
                                                        Concluído</option>
                                                    <option value="Desistente"
                                                        {{ old('status', $estudante->status) == 'Desistente' ? 'selected' : '' }}>
                                                        Desistente</option>
                                                </select>
                                                @error('status')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="contato_emergencia">Contato de Emergência</label>
                                            <input type="text"
                                                class="form-control @error('contato_emergencia') is-invalid @enderror"
                                                id="contato_emergencia" name="contato_emergencia"
                                                value="{{ old('contato_emergencia', $estudante->contato_emergencia ?? '') }}"
                                                required>
                                            @error('contato_emergencia')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($estudante) ? 'Atualizar' : 'Cadastrar' }}
                        </button>
                        <a href="{{ route('admin.estudantes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
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
                    $('#preview').attr('src', reader.result);
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
