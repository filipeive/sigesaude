@extends('adminlte::page')

@section('title', 'Lançar Notas Trimestrais')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-pen mr-2"></i> Lançar Notas Trimestrais</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Formulário de Seleção -->
    <div class="card card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-search-location mr-1"></i> Selecione Turma, Disciplina e Trimestre</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.create') }}" id="formSelecao">
                <div class="row">
                    <div class="col-md-2">
                        <label>Ano Lectivo</label>
                        <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Ano --</option>
                            @foreach($anosLectivos as $ano)
                                <option value="{{ $ano->id }}" {{ ($anoId == $ano->id) ? 'selected' : '' }}>{{ $ano->ano }} {{ $ano->status == 'Ativo' ? '✓' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Classe</label>
                        <select name="classe_id" id="selectClasse" class="form-control">
                            <option value="">-- Classe --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $turma && $turma->classe_id == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Turma</label>
                        <select name="turma_id" id="selectTurma" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Turma --</option>
                            @if($turma)
                                <option value="{{ $turma->id }}" selected>{{ $turma->nome }} ({{ $turma->classe->nome ?? '' }})</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Disciplina</label>
                        <select name="disciplina_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Disciplina --</option>
                            @foreach($disciplinas as $d)
                                <option value="{{ $d->id }}" {{ request('disciplina_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Trimestre</label>
                        <select name="trimestre" class="form-control" onchange="this.form.submit()">
                            <option value="1" {{ $trimestre == 1 ? 'selected' : '' }}>1º Trimestre</option>
                            <option value="2" {{ $trimestre == 2 ? 'selected' : '' }}>2º Trimestre</option>
                            <option value="3" {{ $trimestre == 3 ? 'selected' : '' }}>3º Trimestre</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($turma && $disciplinaSelecionada)
    <!-- Ações rápidas -->
    <div class="row mb-3">
        <div class="col-md-12 d-flex">
            <a href="{{ route('admin.notas.show', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$anoAtivo?->id ?? $anoId,'disciplina_id'=>request('disciplina_id')]) }}"
               class="btn btn-success mr-2"><i class="fas fa-table mr-1"></i> Ver Pauta Completa</a>
            <form action="{{ route('admin.notas.calcular_medias') }}" method="POST" class="d-inline mr-2">
                @csrf
                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                <input type="hidden" name="ano_lectivo_id" value="{{ $anoAtivo?->id ?? $anoId }}">
                <input type="hidden" name="disciplina_id" value="{{ request('disciplina_id') }}">
                <button type="submit" class="btn btn-warning"><i class="fas fa-calculator mr-1"></i> Calcular Resultados Finais</button>
            </form>
        </div>
    </div>
    @endif

    @if($turma && $disciplinaSelecionada && $alunosComNotas->isNotEmpty())
    <!-- Pauta de Lançamento Trimestral -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-1"></i>
                Pauta — {{ $trimestre }}º Trimestre
                <span class="badge badge-light ml-2">{{ $disciplinaSelecionada->nome }}</span>
                <span class="badge badge-light">{{ $turma->classe?->nome }} — {{ $turma->nome }}</span>
                @if($turma->anoLectivo)<span class="badge badge-light">{{ $turma->anoLectivo->ano }}</span>@endif
                <span class="badge badge-info ml-1">Docente: {{ $disciplinaSelecionada->docente?->user?->name ?? 'Não alocado' }}</span>
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                <strong>SNE Moçambique:</strong> Notas de <strong>0 a 20</strong>.
                ACS = Avaliação Contínua Sistemática |
                ACP = Avaliação Contínua Parcial (Teste) |
                ACF = Avaliação Contínua Final (Exame Trimestral).
                <br><strong>MT = MAC×0,4 + ACP×0,2 + ACF×0,4</strong> onde MAC = (ACS1+ACS2+ACS3)/3.
            </div>

            <form method="POST" action="{{ route('admin.notas.store') }}">
                @csrf
                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                <input type="hidden" name="disciplina_id" value="{{ $disciplinaSelecionada->id }}">
                <input type="hidden" name="ano_lectivo_id" value="{{ $anoAtivo?->id ?? $anoId }}">
                <input type="hidden" name="trimestre" value="{{ $trimestre }}">

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width:40px;" class="text-center">Nº</th>
                                <th>Nome do Aluno</th>
                                <th style="width:90px;" class="text-center">Nº Mat.</th>
                                <th style="width:70px;" class="text-center bg-info">ACS1</th>
                                <th style="width:70px;" class="text-center bg-info">ACS2</th>
                                <th style="width:70px;" class="text-center bg-info">ACS3</th>
                                <th style="width:70px;" class="text-center bg-warning">ACP</th>
                                <th style="width:70px;" class="text-center bg-danger text-white">ACF</th>
                                <th style="width:70px;" class="text-center bg-success">MT</th>
                                <th style="width:90px;" class="text-center">Comp.</th>
                                <th style="width:60px;" class="text-center">Faltas</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($alunosComNotas as $idx => $aluno)
                            @php
                                $nota = $aluno->nota_trimestre;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
                                <td class="text-center"><code>{{ $aluno->matricula }}</code></td>
                                <td class="text-center">
                                    <input type="hidden" name="notas[{{ $idx }}][estudante_id]" value="{{ $aluno->id }}">
                                    <input type="number" name="notas[{{ $idx }}][acs1]" class="form-control form-control-sm text-center"
                                           min="0" max="20" step="0.5" value="{{ $nota?->acs1 }}" style="width:60px;margin:auto;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="notas[{{ $idx }}][acs2]" class="form-control form-control-sm text-center"
                                           min="0" max="20" step="0.5" value="{{ $nota?->acs2 }}" style="width:60px;margin:auto;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="notas[{{ $idx }}][acs3]" class="form-control form-control-sm text-center"
                                           min="0" max="20" step="0.5" value="{{ $nota?->acs3 }}" style="width:60px;margin:auto;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="notas[{{ $idx }}][acp]" class="form-control form-control-sm text-center"
                                           min="0" max="20" step="0.5" value="{{ $nota?->acp }}" style="width:60px;margin:auto;">
                                </td>
                                <td class="text-center">
                                    <input type="number" name="notas[{{ $idx }}][acf]" class="form-control form-control-sm text-center"
                                           min="0" max="20" step="0.5" value="{{ $nota?->acf }}" style="width:60px;margin:auto;">
                                </td>
                                <td class="text-center">
                                    @if($nota?->media_trimestral)
                                        <span class="badge badge-{{ $nota->media_trimestral >= 10 ? 'success' : 'danger' }} font-weight-bold" style="font-size:0.9em;">
                                            {{ number_format($nota->media_trimestral, 1) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <select name="notas[{{ $idx }}][comportamento]" class="form-control form-control-sm" style="width:80px;margin:auto;">
                                        <option value="">—</option>
                                        <option value="Bom" {{ $nota?->comportamento == 'Bom' ? 'selected' : '' }}>Bom</option>
                                        <option value="Razoável" {{ $nota?->comportamento == 'Razoável' ? 'selected' : '' }}>Razoável</option>
                                        <option value="Mau" {{ $nota?->comportamento == 'Mau' ? 'selected' : '' }}>Mau</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="notas[{{ $idx }}][faltas]" class="form-control form-control-sm text-center"
                                           min="0" step="1" value="{{ $nota?->faltas ?? 0 }}" style="width:50px;margin:auto;">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group text-center mt-3">
                    <button type="submit" class="btn btn-lg btn-success">
                        <i class="fas fa-save mr-1"></i> Salvar Notas do {{ $trimestre }}º Trimestre
                    </button>
                    <a href="{{ route('admin.notas.index') }}" class="btn btn-lg btn-default ml-2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    @elseif($turma && $disciplinaSelecionada && $alunosComNotas->isEmpty())
    <div class="callout callout-warning">
        <h5><i class="fas fa-exclamation-triangle mr-1"></i> Sem Alunos</h5>
        <p>Não há estudantes matriculados nesta turma.</p>
    </div>
    @endif

@endsection
