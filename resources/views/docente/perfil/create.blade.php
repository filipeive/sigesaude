@extends('adminlte::page')

@section('title', 'Criar Perfil de Docente')

@section('content_header')
    <h1>
        <i class="fas fa-user-plus"></i> Criar Perfil de Docente
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Complete seu Perfil</h3>
        </div>
        <form action="{{ route('docente.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Formação Acadêmica</label>
                            <input type="text" name="formacao" class="form-control" required 
                                   value="{{ old('formacao') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Anos de Experiência</label>
                            <input type="number" name="anos_experiencia" class="form-control" required 
                                   value="{{ old('anos_experiencia') }}" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label>Departamento</label>
                            <select name="departamento_id" class="form-control" required>
                                <option value="">Selecione...</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" 
                                        {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                        {{ $departamento->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="telefone" class="form-control" required 
                                   value="{{ old('telefone', auth()->user()->telefone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Gênero</label>
                            <select name="genero" class="form-control" required>
                                <option value="">Selecione...</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>
                                    Masculino
                                </option>
                                <option value="Feminino" {{ old('genero') == 'Feminino' ? 'selected' : '' }}>
                                    Feminino
                                </option>
                                <option value="Outro" {{ old('genero') == 'Outro' ? 'selected' : '' }}>
                                    Outro
                                </option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Foto de Perfil</label>
                            <div class="custom-file">
                                <input type="file" name="foto_perfil" class="custom-file-input" id="foto_perfil">
                                <label class="custom-file-label" for="foto_perfil">Escolher arquivo</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Salvar Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
.custom-file-input:lang(en)~.custom-file-label::after {
    content: "Procurar";
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