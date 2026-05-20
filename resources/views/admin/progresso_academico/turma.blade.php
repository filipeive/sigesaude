@extends('adminlte::page')

@section('title', 'Desempenho da Turma')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chalkboard mr-2"></i>{{ $turma->classe->nome ?? '' }} {{ $turma->nome }} — Desempenho por Disciplina</h1>
        <a href="{{ route('admin.progresso_academico.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <div class="card card-info mb-3">
        <div class="card-body">
            <p class="mb-0"><i class="fas fa-info-circle mr-1"></i>
                <strong>{{ $turma->classe->nome ?? '' }} {{ $turma->nome }}</strong> —
                Ano Lectivo: {{ $anoId ? AnoLectivo::find($anoId)?->ano ?? 'N/A' : 'N/A' }} —
                {{ $turma->estudantes->count() }} aluno(s) matriculado(s) —
                {{ $disciplinas->count() }} disciplina(s)
            </p>
        </div>
    </div>

    @if($matriz->isEmpty())
    <div class="alert alert-warning">Nenhum aluno matriculado nesta turma.</div>
    @else
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Matriz de Notas — {{ $turma->nome }}</h3>
        </div>
        <div class="card-body table-responsive p-0" style="max-height:600px;overflow-y:auto;">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-light" style="position:sticky;top:0;z-index:10;">
                    <tr>
                        <th style="width:30px;position:sticky;top:0;background:#f8f9fa;z-index:11;">#</th>
                        <th style="min-width:180px;position:sticky;top:0;background:#f8f9fa;z-index:11;">Nome</th>
                        <th style="width:100px;text-align:center;position:sticky;top:0;background:#f8f9fa;z-index:11;">Matrícula</th>
                        @foreach($disciplinas as $d)
                            <th style="min-width:95px;text-align:center;position:sticky;top:0;background:#f8f9fa;z-index:11;font-size:11px;" title="{{ $d->docente->user->name ?? '—' }}">
                                {{ $d->nome }}
                            </th>
                        @endforeach
                        <th style="width:90px;text-align:center;background:#0056b3;color:white;position:sticky;top:0;z-index:11;">Média Geral</th>
                        <th style="width:70px;text-align:center;background:#0056b3;color:white;position:sticky;top:0;z-index:11;">Sit.</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($matriz as $estId => $dados)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $dados['aluno'] }}</strong></td>
                    <td style="text-align:center;"><code>{{ $dados['matricula'] }}</code></td>
                    @foreach($disciplinas as $d)
                        <td style="text-align:center;">
                            @php $mf = $dados['medias'][$d->id] ?? null; @endphp
                            <span class="{{ $mf !== null && $mf >= 10 ? 'text-success' : ($mf !== null ? 'text-danger' : 'text-muted') }}">
                                <strong>{{ $mf !== null ? number_format($mf,1) : '—' }}</strong>
                            </span>
                        </td>
                    @endforeach
                    <td style="text-align:center;background:#eef4ff;">
                        <strong class="{{ $dados['media_geral'] >= 10 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($dados['media_geral'],1) }}
                        </strong>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge badge-{{ $dados['reprovacoes'] == 0 ? 'success' : ($dados['reprovacoes'] >= 2 ? 'danger' : 'warning') }}">
                            {{ $dados['reprovacoes'] == 0 ? 'Aprov.' : "{$dados['reprovacoes']} Reprov." }}
                        </span>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@stop
