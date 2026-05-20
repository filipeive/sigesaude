@extends('adminlte::page')

@section('title', 'Boletim de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-chart-line mr-2"></i>
            Boletim: {{ $disc->nome ?? 'Selecione Disciplina' }}
            @if($turma) — {{ $turma->classe?->nome ?? '' }} {{ $turma->nome }} @endif
            @if($ano) — {{ $ano->ano }} @endif
        </h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Seleção -->
    <div class="card card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Selecionar Turma e Disciplina</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.show') }}" class="form-inline">
                <input type="hidden" name="tipo_nota" value="{{ $tipoNota }}">
                <div class="form-group mr-2 mb-2">
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Ano Lectivo --</option>
                        @foreach($anosLectivos as $ano)
                            <option value="{{ $ano->id }}" {{ $ano && $ano->id == $ano->id ? 'selected' : '' }}>{{ $ano->ano }} ({{ $ano->status }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="turma_id" class="form-control" onchange="this.form.submit()" id="selectTurma">
                        <option value="">-- Turma --</option>
                        @foreach($classes as $c)
                            @php $turmasDaClasse = $c->turmas; @endphp
                            @if($turmasDaClasse->count())
                                <optgroup label="{{ $c->nome }}">
                                @foreach($turmasDaClasse as $t)
                                    <option value="{{ $t->id }}" {{ $turma && $turma->id == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                                @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="disciplina_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Disciplina --</option>
                        @foreach($disciplinas as $d)
                            <option value="{{ $d->id }}" {{ $disc && $disc->id == $d->id ? 'selected' : '' }}>
                                {{ $d->nome }} ({{ $d->docente->user->name ?? '—' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search mr-1"></i> Ver</button>
                @if($turma && $disc)
                <a href="{{ route('admin.notas.pdf_boletim', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$ano?->id,'disciplina_id'=>$disc->id]) }}"
                   class="btn btn-outline-danger mb-2 ml-1" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> Baixar PDF
                </a>
                @endif
            </form>
        </div>
    </div>

    @if($turma && $disc)
    <!-- Boletim -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-1"></i>
                Boletim de Notas — {{ $disc->nome }}
                <span class="badge badge-light ml-2">{{ $turma->nome }} ({{ $turma->classe?->nome }})</span>
                <span class="badge badge-light">{{ $ano?->ano }}</span>
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nome do Aluno</th>
                        <th style="width:130px;text-align:center;">Matrícula</th>
                        <th style="width:110px;text-align:center;">Média Freq.</th>
                        <th style="width:110px;text-align:center;">Nota Exame</th>
                        <th style="width:110px;text-align:center;">Média Final</th>
                        <th style="width:100px;text-align:center;">Resultado</th>
                        @if($tipoNota !== 'frequencia')
                        <th style="width:150px;text-align:center;">Ações</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @forelse($alunos as $idx => $aluno)
                    @php
                        $nf = $aluno->notasFrequencia->first();
                        $ne = $aluno->notasExame->first();
                        $mf = $medias[$aluno->id] ?? null;
                        $mediaFinal = $mf?->media_final
                            ?? (($nf?->nota ?? 0) + ($ne?->nota ?? 0)) / 2;
                    @endphp
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
                        <td style="text-align:center;"><code>{{ $aluno->matricula }}</code></td>
                        <td style="text-align:center;">
                            <span class="{{ $nf && $nf->nota >= 10 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ $nf?->nota ?? '—' }}</strong>
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span class="{{ $ne && $ne->nota >= 10 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ $ne?->nota ?? '—' }}</strong>
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <strong class="text-primary">{{ number_format($mediaFinal, 1) }}</strong>
                        </td>
                        <td style="text-align:center;">
                            @if($mf)
                                <span class="badge badge-{{ $mf->resultado == 'Aprovado' ? 'success' : 'danger' }}">
                                    {{ $mf->resultado }}
                                </span>
                            @else
                                <span class="badge badge-secondary">—</span>
                            @endif
                        </td>
                        @if($tipoNota !== 'frequencia')
                        <td style="text-align:center;">
                            @if($ne)
                                <a href="{{ route('admin.notas.edit_exame', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$ano?->id,'disciplina_id'=>$disc->id]) }}"
                                   class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                            @endif
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tipoNota == 'frequencia' ? 7 : 8 }}" class="text-center text-muted py-4">
                            Nenhum aluno nesta turma.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @if($alunos->isNotEmpty())
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total: {{ $alunos->count() }} aluno(s)
                        @php
                            $aprovados = $alunos->filter(fn($a) => $medias[$a->id]?->resultado == 'Aprovado')->count();
                        @endphp
                        · Aprovados: {{ $aprovados }} · Reprovados: {{ $alunos->count() - $aprovados }}
                    </small>
                    <a href="{{ route('admin.notas.edit_frequencia', ['turma_id'=>$turma->id,'ano_lectivo_id'=>$ano?->id,'disciplina_id'=>$disc->id]) }}"
                       class="btn btn-warning">
                        <i class="fas fa-edit mr-1"></i> Editar Frequência
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif
@stop
