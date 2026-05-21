@extends('adminlte::page')

@section('title', 'Pauta Anual - ' . $disciplina->nome)

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Pauta Anual: {{ $disciplina->nome }}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.notas_frequencia.index') }}">Notas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}">Lançamento</a></li>
            <li class="breadcrumb-item active">Pauta Completa</li>
        </ol>

        <div class="card mb-4 card-success card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    PAUTA ANUAL DE NOTAS - {{ $anoLectivoAtual->ano ?? '' }}
                </div>
                <div>
                    <a href="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Lançar Notas
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped mb-0" style="font-size:0.85em;">
                        <thead>
                            <tr class="bg-dark text-white text-center">
                                <th rowspan="2" style="vertical-align:middle;width:35px;">Nº</th>
                                <th rowspan="2" style="vertical-align:middle;min-width:180px;text-align:left;">Nome do Aluno</th>
                                <th colspan="6" class="bg-primary">1º Trimestre</th>
                                <th colspan="6" class="bg-info">2º Trimestre</th>
                                <th colspan="6" class="bg-secondary">3º Trimestre</th>
                                <th colspan="4" class="bg-success">Resultado Final</th>
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
                                <th class="bg-secondary text-white">ACS1</th>
                                <th class="bg-secondary text-white">ACS2</th>
                                <th class="bg-secondary text-white">ACS3</th>
                                <th class="bg-secondary text-white">ACP</th>
                                <th class="bg-secondary text-white">ACF</th>
                                <th class="bg-secondary text-white">MT3</th>
                                {{-- Final --}}
                                <th class="bg-success text-white">MF</th>
                                <th class="bg-success text-white">Exame</th>
                                <th class="bg-success text-white">CF</th>
                                <th class="bg-success text-white">Classif.</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($estudantes as $idx => $aluno)
                            @php
                                $t1 = $aluno->t1;
                                $t2 = $aluno->t2;
                                $t3 = $aluno->t3;
                                $res = $aluno->resultado;
                                $exame = $aluno->exame;

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
                                <td>{{ $idx + 1 }}</td>
                                <td class="text-left"><strong>{{ $aluno->user->name ?? 'N/A' }}</strong></td>
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
                                {{-- Final --}}
                                <td class="font-weight-bold {{ $res?->media_frequencia !== null && $res->media_frequencia < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $res?->media_frequencia !== null ? number_format($res->media_frequencia, 1) : '—' }}
                                </td>
                                <td>{{ $exame?->nota !== null ? number_format($exame->nota, 1) : '—' }}</td>
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
                <strong>Legenda:</strong>
                ACS = Avaliação Contínua Sistemática | ACP = Teste Parcial | ACF = Avaliação Final/Exame Trimestral |
                MT = Média Trimestral | MF = Média de Frequência | CF = Classificação Final |
                <span class="badge badge-success">Dispensado</span> MF ≥ 14 |
                <span class="badge badge-warning">Admitido</span> 10 ≤ MF < 14 |
                <span class="badge badge-danger">Excluído</span> MF < 10
            </div>
        </div>
    </div>
@endsection
