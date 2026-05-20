@extends('adminlte::page')

@section('title', 'Desempenho — Disciplina')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-book mr-2"></i>Desempenho — {{ $disciplina->nome }}</h1>
        <a href="{{ route('admin.progresso_academico.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i>
        <strong>Disciplina:</strong> {{ $disciplina->nome }} —
        <strong>Classe:</strong> {{ $disciplina->classe->nome ?? 'N/A' }} —
        <strong>Docente:</strong> {{ $disciplina->docente->user->name ?? 'Não alocado' }}
    </div>

    <!-- Seleção de Turma -->
    @if($turmas->count() > 1)
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.progresso_academico.disciplina', $disciplina) }}">
                <input type="hidden" name="ano_lectivo_id" value="{{ $anoId }}">
                <label>Filtrar por Turma:</label>
                <select name="turma_id" class="form-control" style="max-width:300px;display:inline-block;" onchange="this.form.submit()">
                    <option value="">Todas as Turmas</option>
                    @foreach($turmas as $t)
                        <option value="{{ $t->id }}" {{ $turmaSelecionada && $turmaSelecionada->id == $t->id ? 'selected' : '' }}>
                            {{ $t->nome }} ({{ $t->anoLectivo?->ano ?? '' }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @endif

    @if($alunos->isEmpty())
        <div class="alert alert-warning">Nenhum aluno registrado.</div>
    @else
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users mr-1"></i>Desempenho dos Alunos{{ $turmaSelecionada ? ' — '.$turmaSelecionada->nome : '' }}</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th style="text-align:center;width:120px;">Nota Freq.</th>
                        <th style="text-align:center;width:120px;">Exame</th>
                        <th style="text-align:center;width:120px;background:#0056b3;color:white;">Média Final</th>
                        <th style="text-align:center;width:100px;">Resultado</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($alunos as $i => $a)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $a->nome }}</strong></td>
                        <td><code>{{ $a->matricula }}</code></td>
                        <td style="text-align:center;">
                            <span class="{{ $a->frequencia !== null && $a->frequencia >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ $a->frequencia !== null ? number_format($a->frequencia, 1) : '—' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <span class="{{ $a->exame !== null && $a->exame >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ $a->exame !== null ? number_format($a->exame, 1) : '—' }}
                            </span>
                        </td>
                        <td style="text-align:center;background:#eef4ff;">
                            <strong class="{{ $a->media_final >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($a->media_final, 1) }}
                            </strong>
                        </td>
                        <td style="text-align:center;">
                            <span class="badge badge-{{ $a->resultado == 'Aprovado' ? 'success' : 'danger' }}">
                                {{ $a->resultado }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Nenhum aluno encontrado.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Total: {{ $alunos->count() }} aluno(s) —
                Aprovados: {{ $alunos->where('resultado','Aprovado')->count() }} —
                Reprovados: {{ $alunos->where('resultado','!=Aprovado')->count() }}
            </small>
        </div>
    </div>
    @endif
@stop
