@extends('adminlte::page')

@section('title', 'Editar Notas de Exame')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-2"></i> Notas de Exame</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i>
        <strong>Turma:</strong> {{ $turma->classe->nome ?? '' }} {{ $turma->nome }}
        &nbsp;·&nbsp;
        <strong>Disciplina:</strong> {{ $disciplina->nome }}
        &nbsp;·&nbsp;
        <strong>Ano Lectivo:</strong> {{ $anoLectivo->ano ?? '—' }}
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Notas de Exame dos Alunos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
                <thead class="thead-primary">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th style="width:180px;text-align:center;">Nota Exame Atual</th>
                        <th style="width:200px;text-align:center;">Nova Nota</th>
                        <th style="width:100px;text-align:center;">Salvar</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($alunos as $idx => $aluno)
                    @php $nota = $aluno->notasExame->first(); @endphp
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
                        <td><code>{{ $aluno->matricula }}</code></td>
                        <td style="text-align:center;">
                            <strong class="{{ $nota && $nota->nota >= 10 ? 'text-success' : 'text-danger' }}">
                                {{ $nota?->nota ?? '—' }}
                            </strong>
                        </td>
                        <td style="text-align:center;">
                            <form method="POST" action="{{ route('admin.notas.update_exame', $nota?->id ?? 'novo') }}" style="display:inline-flex;">
                                @csrf @method('PUT')
                                <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                                <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                                <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivo->id }}">
                                <input type="number" name="nota" class="form-control form-control-sm"
                                    style="max-width:120px;display:inline;text-align:center;" min="0" max="20" step="0.5"
                                    value="{{ $nota?->nota ?? '' }}" required>
                            </form>
                        </td>
                        <td style="text-align:center;">
                            <button type="submit" formmethod="POST" formaction="{{ $nota ? route('admin.notas.update_exame', $nota) : '#' }}"
                                    class="btn btn-sm btn-success">
                                <i class="fas fa-save"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Nenhum aluno nesta turma.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
