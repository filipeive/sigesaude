{{-- resources/views/admin/estudantes/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'Editar Estudante')

@section('content_header')
    <h1>Editar Estudante</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.estudantes.index') }}">Estudantes</a></li>
        <li class="breadcrumb-item active">Editar Estudante</li>
    </ol>
@stop

@section('content')
    <!-- Mensagens de Sucesso ou Erro -->
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

    <!-- Formulário de Edição -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Editar Informações do Estudante</h3>
        </div>

        <form method="POST" action="{{ route('admin.estudantes.update', $estudante->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row">
                    <!-- Foto de Perfil -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="foto_perfil"><i class="fas fa-camera mr-1"></i>Foto de Perfil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto_perfil') is-invalid @enderror"
                                        id="foto_perfil" name="foto_perfil">
                                    <label class="custom-file-label" for="foto_perfil">
                                        {{ $estudante->foto_perfil ? basename($estudante->foto_perfil) : 'Escolher arquivo' }}
                                    </label>
                                </div>
                            </div>
                            @if ($estudante->foto_perfil)
                                <div class="mt-2">
                                    <img src="{{ asset($estudante->foto_perfil) }}" alt="Foto atual"
                                        class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            @error('foto_perfil')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Informações Pessoais -->
                    <div class="col-md-8">
                        <h5><i class="fas fa-info-circle mr-1"></i>Informações Pessoais</h5>
                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1"></i>Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $estudante->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1"></i>E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $estudante->email) }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="telefone"><i class="fas fa-phone mr-1"></i>Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone"
                                name="telefone" value="{{ old('telefone', $estudante->telefone) }}">
                            @error('telefone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contato_emergencia"><i class="fas fa-address-book mr-1"></i>Contato de Emergência</label>
                            <input type="text" class="form-control @error('contato_emergencia') is-invalid @enderror"
                                id="contato_emergencia" name="contato_emergencia"
                                value="{{ old('contato_emergencia', $estudante->contato_emergencia) }}">
                            @error('contato_emergencia')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Informações Acadêmicas -->
                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fas fa-university mr-1"></i>Informações Acadêmicas</h5>

                        <div class="form-group">
                            <label for="matricula"><i class="fas fa-id-card mr-1"></i>Número de Matrícula</label>
                            <input type="text" class="form-control @error('matricula') is-invalid @enderror" id="matricula"
                                name="matricula" value="{{ old('matricula', $estudante->matricula) }}">
                            @error('matricula')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="curso_id"><i class="fas fa-graduation-cap mr-1"></i>Curso</label>
                            <select class="form-control @error('curso_id') is-invalid @enderror" id="curso_id"
                                name="curso_id">
                                <option value="">Selecione um curso</option>
                                @foreach ($cursos as $id => $nome)
                                    <option value="{{ $id }}"
                                        {{ old('curso_id', $estudante->curso_id) == $id ? 'selected' : '' }}>
                                        {{ $nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('curso_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ano_ingresso"><i class="fas fa-calendar-alt mr-1"></i>Ano de Ingresso</label>
                            <input type="number" class="form-control @error('ano_ingresso') is-invalid @enderror"
                                id="ano_ingresso" name="ano_ingresso"
                                value="{{ old('ano_ingresso', $estudante->ano_ingresso) }}">
                            @error('ano_ingresso')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ano_lectivo_id"><i class="fas fa-calendar mr-1"></i>Ano Letivo</label>
                            <select class="form-control @error('ano_lectivo_id') is-invalid @enderror"
                                id="ano_lectivo_id" name="ano_lectivo_id">
                                <option value="">Selecione o ano letivo</option>
                                @foreach ($anosLectivos as $id => $ano)
                                    <option value="{{ $id }}"
                                        {{ old('ano_lectivo_id', $estudante->ano_lectivo_id) == $id ? 'selected' : '' }}>
                                        {{ $ano }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ano_lectivo_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status"><i class="fas fa-check-circle mr-1"></i>Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status"
                                name="status">
                                <option value="Ativo" {{ old('status', $estudante->status) == 'Ativo' ? 'selected' : '' }}>
                                    Ativo
                                </option>
                                <option value="Inativo"
                                    {{ old('status', $estudante->status) == 'Inativo' ? 'selected' : '' }}>
                                    Inativo
                                </option>
                                <option value="Concluído"
                                    {{ old('status', $estudante->status) == 'Concluído' ? 'selected' : '' }}>
                                    Concluído
                                </option>
                                <option value="Desistente"
                                    {{ old('status', $estudante->status) == 'Desistente' ? 'selected' : '' }}>
                                    Desistente
                                </option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="data_nascimento"><i class="fas fa-birthday-cake mr-1"></i>Data de Nascimento</label>
                            <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror"
                                id="data_nascimento" name="data_nascimento"
                                value="{{ old('data_nascimento', $estudante->data_nascimento) }}">
                            @error('data_nascimento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="genero"><i class="fas fa-venus-mars mr-1"></i>Gênero</label>
                            <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                                name="genero">
                                <option value="Masculino"
                                    {{ old('genero', $estudante->genero) == 'Masculino' ? 'selected' : '' }}>
                                    Masculino
                                </option>
                                <option value="Feminino"
                                    {{ old('genero', $estudante->genero) == 'Feminino' ? 'selected' : '' }}>
                                    Feminino
                                </option>
                                <option value="Outro"
                                    {{ old('genero', $estudante->genero) == 'Outro' ? 'selected' : '' }}>
                                    Outro
                                </option>
                            </select>
                            @error('genero')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="turno"><i class="fas fa-clock mr-1"></i>Turno</label>
                            <select class="form-control @error('turno') is-invalid @enderror" id="turno"
                                name="turno">
                                <option value="Diurno"
                                    {{ old('turno', $estudante->turno) == 'Diurno' ? 'selected' : '' }}>
                                    Diurno
                                </option>
                                <option value="Noturno"
                                    {{ old('turno', $estudante->turno) == 'Noturno' ? 'selected' : '' }}>
                                    Noturno
                                </option>
                            </select>
                            @error('turno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i>Salvar Alterações
                </button>
                <a href="{{ route('admin.estudantes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Voltar
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        // Atualizar o nome do arquivo selecionado na label do input file
        document.getElementById('foto_perfil').addEventListener('change', function(e) {
            var fileName = e.target.files[0]?.name || 'Nenhum arquivo selecionado';
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@stop