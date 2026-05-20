@extends('adminlte::page')

@section('title', 'Lançar Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-pen mr-2"></i> Lançar Notas</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Formulário de Seleção -->
    <div class="card card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-search-location mr-1"></i> Selecione Turma e Disciplina</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.create') }}" id="formSelecao">
                <div class="row">
                    <div class="col-md-3">
                        <label>Ano Lectivo</label>
                        <select name="ano_lectivo_id" class="form-control" onchange="document.getElementById('formSelecao').submit()">
                            <option value="">-- Ano Lectivo --</option>
                            @foreach($anosLectivos as $ano)
                                <option value="{{ $ano->id }}" {{ ($anoId == $ano->id) ? 'selected' : '' }}>{{ $ano->ano }} {{ $ano->status == 'Ativo' ? '(Ativo)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Classe</label>
                        <select name="classe_id" id="selectClasse" class="form-control">
                            <option value="">-- Classe --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $turma && $turma->classe_id == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Turma</label>
                        <select name="turma_id" id="selectTurma" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Turma --</option>
                            @if($turma)
                                <option value="{{ $turma->id }}" selected>{{ $turma->nome }} ({{ $turma->classe->nome ?? '' }})</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block mt-0"><i class="fas fa-search mr-1"></i> Selecionar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($turma)
    <!-- Seleção de Disciplina e Tipo de Nota -->
    <div class="card card-success mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-book mr-1"></i> Disciplina e Tipo de Nota</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.create') }}" id="formDisciplina">
                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                <input type="hidden" name="ano_lectivo_id" value="{{ $anoAtivo?->id ?? $anoId }}">

                <div class="row">
                    <div class="col-md-6">
                        <label>Disciplina</label>
                        <select name="disciplina_id" id="selectDisciplina" class="form-control" onchange="document.getElementById('formDisciplina').submit()">
                            <option value="">-- Escolha a Disciplina --</option>
                            @foreach($disciplinas as $d)
                                <option value="{{ $d->id }}" {{ $request->get('disciplina_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nome }} — Docente: {{ $d->docente->user->name ?? 'Não alocado' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Tipo de Nota</label>
                        <select name="tipo_nota" id="selectTipoNota" class="form-control" onchange="document.getElementById('formDisciplina').submit()">
                            <option value="frequencia" {{ $tipoNota == 'frequencia' ? 'selected' : '' }}>Nota de Frequência</option>
                            <option value="exame" {{ $tipoNota == 'exame' ? 'selected' : '' }}>Nota de Exame</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <div>
                            <a href="{{ route('admin.notas.show', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$anoAtivo?->id ?? $anoId,'disciplina_id'=>$request->disciplina_id,'tipo_nota'=>$tipoNota]) }}"
                               class="btn btn-sm btn-success mt-0 mr-1"><i class="fas fa-chart-line"></i> Ver Notas</a>
                            <a href="{{ route('admin.notas.calcular_medias', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$anoAtivo?->id ?? $anoId,'disciplina_id'=>$request->disciplina_id]) }}"
                               class="btn btn-sm btn-info mt-0"><i class="fas fa-calculator"></i> Médias</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($turma && $disciplinaSelecionada && $alunosComNotas->isNotEmpty())
    <!-- Formulário de Lançamento -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-pen mr-1"></i>
                Lançar Notas — {{ $disciplinaSelecionada->nome }}
                <span class="badge badge-light ml-2">{{ $turma->nome }}</span>
                <span class="badge badge-light">{{ $turma->classe?->nome }}</span>
                @if($turma->anoLectivo)<span class="badge badge-light">{{ $turma->anoLectivo->ano }}</span>@endif
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Insira as notas de <strong>0 a 20</strong>. Deixe em branco para não lançar ainda.
                <strong>Tipo:</strong> {{ $tipoNota == 'frequencia' ? 'Nota de Frequência' : 'Nota de Exame' }}.
            </div>

            <form method="POST" action="{{ route('admin.notas.store') }}">
                @csrf
                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                <input type="hidden" name="disciplina_id" value="{{ $disciplinaSelecionada->id }}">
                <input type="hidden" name="ano_lectivo_id" value="{{ $turma->ano_lectivo_id }}">
                <input type="hidden" name="tipo_nota" value="{{ $tipoNota }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-primary">
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Nome do Aluno</th>
                                <th>Nº Matrícula</th>
                                <th style="width:150px;text-align:center;">Nota (0-20)</th>
                                <th style="width:100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($alunosComNotas as $idx => $aluno)
                            @php
                                $notaAtual = $tipoNota === 'frequencia'
                                    ? ($aluno->notasFrequencia->first()?->nota ?? null)
                                    : ($aluno->notasExame->first()?->nota ?? null);
                            @endphp
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
                                <td><code>{{ $aluno->matricula }}</code></td>
                                <td style="text-align:center;">
                                    <input type="hidden" name="notas[{{ $idx }}][estudante_id]" value="{{ $aluno->id }}">
                                    <input type="number"
                                           name="notas[{{ $idx }}][nota]"
                                           class="form-control form-control-sm text-center"
                                           style="max-width:120px;display:inline-block;"
                                           min="0" max="20" step="0.5"
                                           value="{{ $notaAtual }}">
                                </td>
                                <td style="text-align:center;">
                                    @if($notaAtual !== null)
                                        <span class="badge badge-success">Lançada</span>
                                    @else
                                        <span class="badge badge-secondary">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group text-center mt-3">
                    <button type="submit" class="btn btn-lg btn-success">
                        <i class="fas fa-save mr-1"></i> Salvar Notas
                    </button>
                    <a href="{{ route('admin.notas.index') }}" class="btn btn-lg btn-default">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    @endif

@endsection
