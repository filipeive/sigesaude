<!-- resources/views/estudante/inscricoes/create.blade.php -->
@extends('adminlte::page')

@section('title', 'Nova Inscrição')

@section('content_header')
    <h1>Inscrição Semestral</h1>
@stop

@section('content')
    <div class="alert alert-info">
        <h5><i class="icon fas fa-info"></i> Caro estudante,</h5>
        <ul>
            <li>As disciplinas cujas precedentes não possuem aprovação são automaticamente removidas da lista de disciplinas para a inscrição! Clique <button class="btn btn-xs btn-success"><i class="fas fa-list"></i></button> para consultar a lista de precedências do seu plano curricular.</li>
            <li>Não faça o pagamento se tiver cometido algum erro na escolha das cadeiras. Clique sobre o botão <button class="btn btn-xs btn-danger">Cancelar</button> em frente da sua pré-inscrição para a cancelar.</li>
            <li>Se tiver cadeiras em atraso, faça a escolha das cadeiras clicando sobre a aba <button class="btn btn-xs btn-default">Disciplinas(s) em Atraso</button> ou <button class="btn btn-xs btn-default">Próximo</button> e escolha as cadeiras que quer fazer, antes de pisar em guardar!</li>
        </ul>
    </div>

    <form action="{{ route('estudante.inscricoes.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ano_lectivo">Ano Lectivo:</label>
                    <input type="text" class="form-control" id="ano_lectivo" value="{{ $anoAtual->ano }}" readonly>
                    <input type="hidden" name="ano_lectivo_id" value="{{ $anoAtual->id }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="semestre">Semestre:</label>
                    <input type="text" class="form-control" id="semestre" name="semestre" value="1">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nr_estudante">Nº de Estudante:</label>
                    <input type="text" class="form-control" id="nr_estudante" value="{{ $estudante->matricula }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nome_estudante">Nome do Estudante:</label>
                    <input type="text" class="form-control" id="nome_estudante" value="{{ $estudante->user->name }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="regime">Regime:</label>
                    <input type="text" class="form-control" id="regime" value="{{ $estudante->turno }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="curso">Curso:</label>
                    <input type="text" class="form-control" id="curso" value="{{ $estudante->curso->nome }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="delegacao">Delegação:</label>
                    <input type="text" class="form-control" id="delegacao" value="INSTITUTO DE SAUDE" readonly>
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="nivel">Nível:</label>
                    <input type="text" class="form-control" id="nivel" value="{{ $estudante->nivel()->id }}" readonly>
                </div>
            </div> --}}
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="disciplinas-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="disciplinas-normais-tab" data-toggle="tab" href="#disciplinas-normais" role="tab">Disciplinas Normais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="disciplinas-atraso-tab" data-toggle="tab" href="#disciplinas-atraso" role="tab">Disciplina(s) em Atraso</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="disciplinas-tabContent">
                    <div class="tab-pane fade show active" id="disciplinas-normais" role="tabpanel">
                        <div class="alert alert-danger">
                            <strong>Cadeiras Normais</strong> [Elimine as que não for frequentar fazendo clique sobre o símbolo do lixo]
                        </div>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nome da Disciplina</th>
                                    <th>Ano</th>
                                    <th>Sem.</th>
                                    <th>Tipo</th>
                                    <th>Créditos</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disciplinasNormais as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->nome }}</td>
                                    <td>{{ $disciplina->nivel->id }}</td>
                                    <td>1</td>
                                    <td>Normal</td>
                                    <td>1</td>
                                    <td>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="normal_{{ $disciplina->id }}" name="disciplinas_normais[]" value="{{ $disciplina->id }}" checked>
                                            <label for="normal_{{ $disciplina->id }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane fade" id="disciplinas-atraso" role="tabpanel">
                        <div class="alert alert-danger">
                            <strong>Cadeiras anteriores ao ano que está a frequentar</strong> [escolha as cadeiras que quiser frequentar fazendo clique sobre a disciplina]
                        </div>
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nome da Disciplina</th>
                                    <th>Ano</th>
                                    <th>Sem.</th>
                                    <th>Tipo</th>
                                    <th>Créditos</th>
                                    <th>Escolher</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disciplinasEmAtraso as $disciplina)
                                <tr>
                                    <td>{{ $disciplina->nome }}</td>
                                    <td>{{ $disciplina->nivel->id }}</td>
                                    <td>1</td>
                                    <td>Normal</td>
                                    <td>1</td>
                                    <td>
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="atraso_{{ $disciplina->id }}" name="disciplinas_atraso[]" value="{{ $disciplina->id }}">
                                            <label for="atraso_{{ $disciplina->id }}"></label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ url('estudante/inscricoes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>
@stop

@section('js')
<script>
    $(function () {
        // Initialize iCheck
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    });
</script>
@stop