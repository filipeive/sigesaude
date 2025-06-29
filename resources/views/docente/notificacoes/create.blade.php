@extends('adminlte::page')

@section('title', 'Enviar Notificação')

@section('content_header')
    <h1>
        <i class="fas fa-paper-plane text-primary"></i> Enviar Notificação
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Nova Notificação</h3>
                </div>
                
                <form action="{{ route('docente.notificacoes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Destinatários</label>
                            <select name="destinatarios[]" class="form-control select2" multiple required>
                                @foreach($disciplinas as $disciplina)
                                    <optgroup label="{{ $disciplina->nome }}">
                                        @foreach($disciplina->inscricaoDisciplinas as $inscricao)
                                            @if($inscricao->inscricao && $inscricao->inscricao->estudante)
                                                <option value="{{ $inscricao->inscricao->estudante->user_id }}">
                                                    {{ $inscricao->inscricao->estudante->user->name }} 
                                                    ({{ $inscricao->inscricao->estudante->matricula }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="academico">Acadêmico</option>
                                <option value="avaliacao">Avaliação</option>
                                <option value="exame">Exame</option>
                                <option value="presenca">Presença</option>
                                <option value="geral">Geral</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" class="form-control" required 
                                   placeholder="Digite o título da notificação">
                        </div>

                        <div class="form-group">
                            <label>Mensagem</label>
                            <textarea name="mensagem" class="form-control" rows="4" required 
                                      placeholder="Digite a mensagem da notificação"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Link (Opcional)</label>
                            <input type="url" name="link" class="form-control" 
                                   placeholder="https://...">
                        </div>

                        <div class="form-group">
                            <label>Agendar para</label>
                            <input type="datetime-local" name="agendada_para" class="form-control">
                            <small class="form-text text-muted">
                                Deixe em branco para enviar imediatamente
                            </small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Enviar Notificação
                        </button>
                        <a href="{{ route('docente.notificacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css" rel="stylesheet">
<style>
.select2-container--bootstrap4 .select2-selection--multiple {
    min-height: 38px;
}
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Selecione os destinatários',
        allowClear: true
    });

    // Preview da notificação
    $('input[name="titulo"], textarea[name="mensagem"]').on('input', function() {
        let titulo = $('input[name="titulo"]').val();
        let mensagem = $('textarea[name="mensagem"]').val();
        
        if (titulo || mensagem) {
            $('#previewNotificacao').removeClass('d-none');
            $('#previewTitulo').text(titulo);
            $('#previewMensagem').text(mensagem);
        } else {
            $('#previewNotificacao').addClass('d-none');
        }
    });
});
</script>
@stop