{{-- resources/views/admin/notificacoes/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Nova Notificação')

@section('content_header')
<h1>Nova Notificação</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('admin.notificacoes.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                               id="titulo" name="titulo" value="{{ old('titulo') }}" required>
                        @error('titulo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mensagem">Mensagem</label>
                        <textarea class="form-control @error('mensagem') is-invalid @enderror" 
                                  id="mensagem" name="mensagem" rows="3" required>{{ old('mensagem') }}</textarea>
                        @error('mensagem')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo da Notificação</label>
                        <select class="form-control @error('tipo') is-invalid @enderror" 
                                id="tipo" name="tipo" required>
                            <option value="">Selecione...</option>
                            <option value="academico" {{ old('tipo') == 'academico' ? 'selected' : '' }}>Acadêmico</option>
                            <option value="financeiro" {{ old('tipo') == 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                            <option value="administrativo" {{ old('tipo') == 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                            <option value="geral" {{ old('tipo') == 'geral' ? 'selected' : '' }}>Geral</option>
                        </select>
                        @error('tipo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="modo_envio">Modo de Envio</label>
                        <select class="form-control @error('modo_envio') is-invalid @enderror" 
                                id="modo_envio" name="modo_envio" required>
                            <option value="">Selecione...</option>
                            <option value="todos" {{ old('modo_envio') == 'todos' ? 'selected' : '' }}>Todos os Usuários</option>
                            <option value="tipos" {{ old('modo_envio') == 'tipos' ? 'selected' : '' }}>Por Tipo de Usuário</option>
                            <option value="especificos" {{ old('modo_envio') == 'especificos' ? 'selected' : '' }}>Usuários Específicos</option>
                        </select>
                        @error('modo_envio')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="tipos_section" class="form-group" style="display: none;">
                        <label for="tipos_usuario">Tipos de Usuário</label>
                        <select class="form-control select2 @error('tipos_usuario') is-invalid @enderror" 
                                id="tipos_usuario" name="tipos_usuario[]" multiple>
                            @foreach($tiposUsuario as $valor => $label)
                                <option value="{{ $valor }}" 
                                    {{ in_array($valor, old('tipos_usuario', [])) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipos_usuario')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="usuarios_section" class="form-group" style="display: none;">
                        <label for="usuarios">Usuários</label>
                        <select class="form-control select2 @error('usuarios') is-invalid @enderror" 
                                id="usuarios" name="usuarios[]" multiple>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario['id'] }}" 
                                    {{ in_array($usuario['id'], old('usuarios', [])) ? 'selected' : '' }}>
                                    {{ $usuario['text'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('usuarios')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="link">Link (opcional)</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror" 
                               id="link" name="link" value="{{ old('link') }}">
                        <small class="form-text text-muted">
                            URL completa para mais informações (ex: http://exemplo.com/pagina)
                        </small>
                        @error('link')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Salvar
                    </button>
                    <a href="{{ route('admin.notificacoes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecione...',
        allowClear: true
    });

    // Controlar visibilidade das seções baseado no modo de envio
    $('#modo_envio').on('change', function() {
        const modo = $(this).val();
        
        // Esconder todas as seções
        $('#tipos_section, #usuarios_section').hide();
        
        // Mostrar seção apropriada
        if (modo === 'tipos') {
            $('#tipos_section').show();
        } else if (modo === 'especificos') {
            $('#usuarios_section').show();
        }
        
        // Limpar seleções quando mudar o modo
        if (modo !== 'tipos') {
            $('#tipos_usuario').val(null).trigger('change');
        }
        if (modo !== 'especificos') {
            $('#usuarios').val(null).trigger('change');
        }
    });

    // Trigger inicial para mostrar seção correta se houver valor no old()
    $('#modo_envio').trigger('change');
});
</script>
@stop