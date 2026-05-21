@extends('adminlte::page')

@section('title', 'Pauta de Notas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-table mr-2"></i> Pauta de Notas</h1>
        <a href="{{ route('admin.notas.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Filtros -->
    <div class="card card-info mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Selecione Turma e Disciplina</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notas.show') }}" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <select name="ano_lectivo_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Ano Lectivo --</option>
                        @foreach($anosLectivos as $a)
                            <option value="{{ $a->id }}" {{ $ano && $ano->id == $a->id ? 'selected' : '' }}>{{ $a->ano }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="turma_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Turma --</option>
                        @if($turma)
                            <option value="{{ $turma->id }}" selected>{{ $turma->classe?->nome }} — {{ $turma->nome }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <select name="disciplina_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Disciplina --</option>
                        @foreach($disciplinas as $d)
                            <option value="{{ $d->id }}" {{ $disc && $disc->id == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search mr-1"></i> Carregar</button>
            </form>
        </div>
    </div>

    @if($turma && $disc && $ano && $alunos->isNotEmpty())
    <!-- Cabeçalho da Pauta -->
    <div class="card card-success">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list mr-1"></i>
                PAUTA ANUAL — {{ $disc->nome }}
                <span class="badge badge-light ml-1">{{ $turma->classe?->nome }} — {{ $turma->nome }}</span>
                <span class="badge badge-light">{{ $ano->ano }}</span>
                <span class="badge badge-info ml-1">Docente: {{ $disc->docente?->user?->name ?? 'N/A' }}</span>
            </h3>
            <div>
                <form action="{{ route('admin.notas.calcular_medias') }}" method="POST" class="d-inline mr-1">
                    @csrf
                    <input type="hidden" name="turma_id" value="{{ $turma->id }}">
                    <input type="hidden" name="ano_lectivo_id" value="{{ $ano->id }}">
                    <input type="hidden" name="disciplina_id" value="{{ $disc->id }}">
                    <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-calculator mr-1"></i> Calcular Resultados</button>
                </form>
                <a href="{{ route('admin.notas.pdf_pauta', ['turma_id'=>$turma->id,'disciplina_id'=>$disc->id,'ano_lectivo_id'=>$ano->id]) }}"
                   class="btn btn-sm btn-danger"><i class="fas fa-file-pdf mr-1"></i> Exportar PDF</a>
                <a href="{{ route('admin.notas.create', ['turma_id'=>$turma->id,'disciplina_id'=>$disc->id,'ano_lectivo_id'=>$ano->id]) }}"
                   class="btn btn-sm btn-primary"><i class="fas fa-pen mr-1"></i> Lançar Notas</a>
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
                            {{-- 1º Trimestre --}}
                            <th class="bg-primary text-white">ACS1</th>
                            <th class="bg-primary text-white">ACS2</th>
                            <th class="bg-primary text-white">ACS3</th>
                            <th class="bg-primary text-white">ACP</th>
                            <th class="bg-primary text-white">ACF</th>
                            <th class="bg-primary text-white">MT1</th>
                            {{-- 2º Trimestre --}}
                            <th class="bg-info text-white">ACS1</th>
                            <th class="bg-info text-white">ACS2</th>
                            <th class="bg-info text-white">ACS3</th>
                            <th class="bg-info text-white">ACP</th>
                            <th class="bg-info text-white">ACF</th>
                            <th class="bg-info text-white">MT2</th>
                            {{-- 3º Trimestre --}}
                            <th class="bg-secondary text-white">ACS1</th>
                            <th class="bg-secondary text-white">ACS2</th>
                            <th class="bg-secondary text-white">ACS3</th>
                            <th class="bg-secondary text-white">ACP</th>
                            <th class="bg-secondary text-white">ACF</th>
                            <th class="bg-secondary text-white">MT3</th>
                            {{-- Resultado --}}
                            <th class="bg-success text-white">MF</th>
                            <th class="bg-success text-white">Exame</th>
                            <th class="bg-success text-white">CF</th>
                            <th class="bg-success text-white">Classif.</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($alunos as $idx => $aluno)
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
                            {{-- Resultado Final --}}
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
            ACS = Avaliação Contínua Sistemática |
            ACP = Avaliação Contínua Parcial (Teste) |
            ACF = Avaliação Contínua Final (Exame Trimestral) |
            MT = Média Trimestral |
            MF = Média de Frequência |
            CF = Classificação Final |
            <span class="badge badge-success">Dispensado</span> MF ≥ 14 |
            <span class="badge badge-warning">Admitido</span> 10 ≤ MF < 14 |
            <span class="badge badge-danger">Excluído</span> MF < 10
        </div>
    </div>
    @elseif($turma && $disc && $ano)
    <div class="callout callout-warning">
        <h5><i class="fas fa-exclamation-triangle mr-1"></i> Sem Dados</h5>
        <p>Não há estudantes nesta turma ou ainda não foram lançadas notas.</p>
    </div>
    @endif
@stop
