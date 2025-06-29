{{-- filepath: /home/filipe/Filipe/Projectos/GestaoInstituto/resources/views/docente/perfil/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Perfil do Docente')

@section('content_header')
    <h1><i class="fas fa-user-circle text-primary"></i> Meu Perfil</h1>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Cards de Resumo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['total_disciplinas'] }}</h3>
                    <p>Disciplinas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_estudantes'] }}</h3>
                    <p>Estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['anos_experiencia'] }}</h3>
                    <p>Anos de Experiência</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $stats['disciplinas_ativas'] }}</h3>
                    <p>Disciplinas Ativas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Perfil -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->foto_perfil)
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ Storage::url('fotos_perfil/' . $user->foto_perfil) }}"
                                 alt="Foto do perfil">
                        @else
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset('vendor/adminlte/dist/img/user2-160x160.jpg') }}"
                                 alt="Foto padrão">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">{{ $docente->formacao }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope mr-1"></i> Email</b>
                            <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-phone mr-1"></i> Telefone</b>
                            <a class="float-right">{{ $user->telefone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-building mr-1"></i> Departamento</b>
                            <a class="float-right">{{ $docente->departamento->nome }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-clock mr-1"></i> Anos de Experiência</b>
                            <a class="float-right">{{ $docente->anos_experiencia }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulário de Edição -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Editar Informações</h3>
                </div>
                <form action="{{ route('docente.perfil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome Completo</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $user->telefone) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Formação</label>
                                    <input type="text" name="formacao" class="form-control" value="{{ old('formacao', $docente->formacao) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Anos de Experiência</label>
                                    <input type="number" name="anos_experiencia" class="form-control" value="{{ old('anos_experiencia', $docente->anos_experiencia) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Departamento</label>
                                    <select name="departamento_id" class="form-control" required>
                                        @foreach($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}" 
                                                {{ old('departamento_id', $docente->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                                {{ $departamento->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nova Senha (opcional)</label>
                                    <input type="password" name="password" class="form-control">
                                    <small class="form-text text-muted">
                                        Deixe em branco para manter a senha atual
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Foto de Perfil</label>
                            <div class="custom-file">
                                <input type="file" name="foto_perfil" class="custom-file-input" id="foto_perfil">
                                <label class="custom-file-label" for="foto_perfil">Escolher arquivo</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.profile-user-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.small-box {
    transition: all 0.3s ease;
}
.small-box:hover {
    transform: translateY(-5px);
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    bsCustomFileInput.init();
});
</script>
@stop