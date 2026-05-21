@extends('adminlte::page')

@section('title', 'Boletim de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-clipboard-list mr-2"></i>Boletim de Notas</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-graduate mr-1"></i>
                {{ $estudante->user->name ?? 'N/A' }}
                @if($estudante->turma)
                    — {{ $estudante->turma->classe->nome ?? '' }} ({{ $estudante->turma->nome }})
                @endif
            </p>
        </div>
        <a href="{{ route('estudante.dashboard') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    {{-- Filtro Ano Lectivo --}}
    <div class="card card-primary card-outline mb-3">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i> Ano Lectivo</h3></div>
        <div class="card-body">
            <form method="GET" action="{{ route('estudante.notas_frequencia.notas') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label class="mr-2">Ano Lectivo:</label>
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        @foreach($anosLectivos as $a)
                            <option value="{{ $a->id }}" {{ $anoSelecionado && $anoSelecionado->id == $a->id ? 'selected' : '' }}>{{ $a->ano }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if(count($boletim) > 0)
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-graduation-cap mr-1"></i>
                <strong>BOLETIM TRIMESTRAL</strong> — {{ $anoSelecionado->ano }}
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped mb-0" style="font-size:0.85em;">
                    <thead>
                        <tr class="bg-dark text-white text-center">
                            <th rowspan="2" style="vertical-align:middle;min-width:180px;text-align:left;">Disciplina</th>
                            <th colspan="6" class="bg-primary">1º Trimestre</th>
                            <th colspan="6" class="bg-info">2º Trimestre</th>
                            <th colspan="6" style="background-color:#546e7a;color:white;">3º Trimestre</th>
                            <th colspan="3" class="bg-success">Resultado Final</th>
                        </tr>
                        <tr class="text-center" style="font-size:0.85em;">
                            {{-- T1 --}}
                            <th class="bg-primary text-white">ACS1</th>
                            <th class="bg-primary text-white">ACS2</th>
                            <th class="bg-primary text-white">ACS3</th>
                            <th class="bg-primary text-white">ACP</th>
                            <th class="bg-primary text-white">ACF</th>
                            <th class="bg-primary text-white">MT1</th>
                            {{-- T2 --}}
                            <th class="bg-info text-white">ACS1</th>
                            <th class="bg-info text-white">ACS2</th>
                            <th class="bg-info text-white">ACS3</th>
                            <th class="bg-info text-white">ACP</th>
                            <th class="bg-info text-white">ACF</th>
                            <th class="bg-info text-white">MT2</th>
                            {{-- T3 --}}
                            <th style="background-color:#546e7a;color:white;">ACS1</th>
                            <th style="background-color:#546e7a;color:white;">ACS2</th>
                            <th style="background-color:#546e7a;color:white;">ACS3</th>
                            <th style="background-color:#546e7a;color:white;">ACP</th>
                            <th style="background-color:#546e7a;color:white;">ACF</th>
                            <th style="background-color:#546e7a;color:white;">MT3</th>
                            {{-- Final --}}
                            <th class="bg-success text-white">MF</th>
                            <th class="bg-success text-white">CF</th>
                            <th class="bg-success text-white">Classif.</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($boletim as $item)
                        @php
                            $t1 = $item['t1'];
                            $t2 = $item['t2'];
                            $t3 = $item['t3'];
                            $res = $item['resultado'];

                            $classColor = match($res?->classificacao_final) {
                                'Dispensado' => 'badge-success',
                                'Aprovado'   => 'badge-primary',
                                'Admitido'   => 'badge-warning',
                                'Excluído'   => 'badge-danger',
                                'Reprovado'  => 'badge-danger',
                                default      => 'badge-secondary',
                            };
                        @endphp
                        <tr class="text-center">
                            <td class="text-left font-weight-bold">
                                {{ $item['disciplina']->nome }}
                                @if($item['disciplina']->docente?->user)
                                    <br><small class="text-muted">{{ $item['disciplina']->docente->user->name }}</small>
                                @endif
                            </td>
                            {{-- T1 --}}
                            <td>{{ $t1?->acs1 !== null ? number_format($t1->acs1, 1) : '—' }}</td>
                            <td>{{ $t1?->acs2 !== null ? number_format($t1->acs2, 1) : '—' }}</td>
                            <td>{{ $t1?->acs3 !== null ? number_format($t1->acs3, 1) : '—' }}</td>
                            <td>{{ $t1?->acp !== null ? number_format($t1->acp, 1) : '—' }}</td>
                            <td>{{ $t1?->acf !== null ? number_format($t1->acf, 1) : '—' }}</td>
                            <td class="font-weight-bold {{ $t1?->media_trimestral !== null && $t1->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $t1?->media_trimestral !== null ? number_format($t1->media_trimestral, 1) : '—' }}
                            </td>
                            {{-- T2 --}}
                            <td>{{ $t2?->acs1 !== null ? number_format($t2->acs1, 1) : '—' }}</td>
                            <td>{{ $t2?->acs2 !== null ? number_format($t2->acs2, 1) : '—' }}</td>
                            <td>{{ $t2?->acs3 !== null ? number_format($t2->acs3, 1) : '—' }}</td>
                            <td>{{ $t2?->acp !== null ? number_format($t2->acp, 1) : '—' }}</td>
                            <td>{{ $t2?->acf !== null ? number_format($t2->acf, 1) : '—' }}</td>
                            <td class="font-weight-bold {{ $t2?->media_trimestral !== null && $t2->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $t2?->media_trimestral !== null ? number_format($t2->media_trimestral, 1) : '—' }}
                            </td>
                            {{-- T3 --}}
                            <td>{{ $t3?->acs1 !== null ? number_format($t3->acs1, 1) : '—' }}</td>
                            <td>{{ $t3?->acs2 !== null ? number_format($t3->acs2, 1) : '—' }}</td>
                            <td>{{ $t3?->acs3 !== null ? number_format($t3->acs3, 1) : '—' }}</td>
                            <td>{{ $t3?->acp !== null ? number_format($t3->acp, 1) : '—' }}</td>
                            <td>{{ $t3?->acf !== null ? number_format($t3->acf, 1) : '—' }}</td>
                            <td class="font-weight-bold {{ $t3?->media_trimestral !== null && $t3->media_trimestral < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $t3?->media_trimestral !== null ? number_format($t3->media_trimestral, 1) : '—' }}
                            </td>
                            {{-- Resultado Final --}}
                            <td class="font-weight-bold {{ $res?->media_frequencia !== null && $res->media_frequencia < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $res?->media_frequencia !== null ? number_format($res->media_frequencia, 1) : '—' }}
                            </td>
                            <td class="font-weight-bold {{ $res?->media_final !== null && $res->media_final < 10 ? 'text-danger' : 'text-success' }}">
                                {{ $res?->media_final !== null ? number_format($res->media_final, 1) : '—' }}
                            </td>
                            <td>
                                @if($res?->classificacao_final)
                                    <span class="badge {{ $classColor }}">{{ $res->classificacao_final }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted small">
            <strong>Legenda SNE:</strong>
            ACS = Avaliação Contínua Sistemática |
            ACP = Avaliação Contínua Parcial |
            ACF = Avaliação Contínua Final |
            MT = Média Trimestral |
            MF = Média de Frequência |
            CF = Classificação Final
            <br>
            <span class="badge badge-success">Dispensado</span> MF ≥ 14 |
            <span class="badge badge-warning">Admitido</span> 10 ≤ MF < 14 (faz exame) |
            <span class="badge badge-danger">Excluído</span> MF < 10
        </div>
    </div>
    @else
        <div class="callout callout-info">
            <h5><i class="fas fa-info-circle mr-2"></i>Sem Dados</h5>
            <p>Nenhuma disciplina encontrada para a sua turma neste ano lectivo. Confirme a sua matrícula na secretaria.</p>
        </div>
    @endif
@stop

@section('css')
<style>
    .table th, .table td { vertical-align: middle !important; }
    .badge { font-weight: 500; padding: 0.35em 0.6em; }
</style>
@stop