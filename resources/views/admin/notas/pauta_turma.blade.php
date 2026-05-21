@extends('adminlte::page')

@section('title', 'Pauta da Turma')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-table mr-2"></i> Pauta da Turma - {{ $turma->classe->nome }} {{ $turma->nome }}</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-1"></i>
                Pauta Anual - {{ $turma->anoLectivo->ano ?? '—' }}
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped mb-0" style="font-size:0.8em;">
                    <thead>
                        <tr class="bg-dark text-white text-center">
                            <th rowspan="2" style="vertical-align:middle;width:35px;">Nº</th>
                            <th rowspan="2" style="vertical-align:middle;min-width:150px;text-align:left;">Nome do Aluno</th>
                            @foreach($disciplinas as $d)
                                <th colspan="5" class="text-center">{{ $d->nome }}</th>
                            @endforeach
                        </tr>
                        <tr class="text-center" style="font-size:0.7em;">
                            @foreach($disciplinas as $d)
                                <th class="bg-primary text-white">MT1</th>
                                <th class="bg-info text-white">MT2</th>
                                <th class="bg-secondary text-white">MT3</th>
                                <th class="bg-success text-white">MF</th>
                                <th class="bg-warning text-white">Class.</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunos as $idx => $aluno)
                            <tr class="text-center">
                                <td>{{ $idx + 1 }}</td>
                                <td class="text-left"><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
                                @foreach($disciplinas as $d)
                                    @php
                                        $m = $aluno->mediasPorDisciplina[$d->id] ?? null;
                                        $classColor = match($m['resultado'] ?? null) {
                                            'Dispensado' => 'badge-success',
                                            'Aprovado'   => 'badge-primary',
                                            'Admitido'   => 'badge-warning',
                                            'Excluído'   => 'badge-danger',
                                            'Reprovado'  => 'badge-danger',
                                            default      => 'badge-secondary',
                                        };
                                    @endphp
                                    <td class="{{ $m && $m['t1'] !== null && $m['t1'] < 10 ? 'text-danger' : 'text-success' }}">{{ $m['t1'] !== null ? number_format($m['t1'], 1) : '—' }}</td>
                                    <td class="{{ $m && $m['t2'] !== null && $m['t2'] < 10 ? 'text-danger' : 'text-success' }}">{{ $m['t2'] !== null ? number_format($m['t2'], 1) : '—' }}</td>
                                    <td class="{{ $m && $m['t3'] !== null && $m['t3'] < 10 ? 'text-danger' : 'text-success' }}">{{ $m['t3'] !== null ? number_format($m['t3'], 1) : '—' }}</td>
                                    <td class="font-weight-bold {{ $m && $m['mf'] !== null && $m['mf'] < 10 ? 'text-danger' : 'text-success' }}">{{ $m['mf'] !== null ? number_format($m['mf'], 1) : '—' }}</td>
                                    <td>
                                        @if($m && $m['resultado'])
                                            <span class="badge {{ $classColor }}">{{ $m['resultado'] }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop