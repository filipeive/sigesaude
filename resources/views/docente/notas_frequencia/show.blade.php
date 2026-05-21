@extends('adminlte::page')

@section('title', 'Lançamento de Notas - ' . $disciplina->nome)

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Lançamento de Notas: {{ $disciplina->nome }}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.notas_frequencia.index') }}">Notas</a></li>
            <li class="breadcrumb-item active">Lançamento Trimestral</li>
        </ol>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card mb-4 card-primary card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-edit me-1"></i>
                    Lançamento do <strong>{{ $trimestre }}º Trimestre</strong> - Ano Letivo: {{ $anoLectivoAtual->ano ?? 'N/A' }}
                </div>
                <div>
                    <a href="{{ route('docente.notas_frequencia.pauta', $disciplina->id) }}" class="btn btn-success me-2">
                        <i class="fas fa-table me-1"></i> Ver Pauta Completa
                    </a>
                    <button type="submit" class="btn btn-primary" form="formNotas">
                        <i class="fas fa-save me-1"></i> Salvar Notas
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Seletor de Trimestre -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('docente.notas_frequencia.show', $disciplina->id) }}" id="trimestreForm">
                            <label>Selecione o Trimestre:</label>
                            <select name="trimestre" class="form-control" onchange="this.form.submit()">
                                <option value="1" {{ $trimestre == 1 ? 'selected' : '' }}>1º Trimestre</option>
                                <option value="2" {{ $trimestre == 2 ? 'selected' : '' }}>2º Trimestre</option>
                                <option value="3" {{ $trimestre == 3 ? 'selected' : '' }}>3º Trimestre</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Sistema Nacional de Educação (Moçambique):</strong>
                    Insira notas de <strong>0 a 20</strong>.
                    ACS = Avaliação Contínua Sistemática | ACP = Teste Parcial | ACF = Avaliação Final/Exame Trimestral.
                    <br><strong>MT = MAC×0,4 + ACP×0,2 + ACF×0,4</strong> onde MAC = (ACS1+ACS2+ACS3)/3.
                </div>

                <form id="formNotas" action="{{ route('docente.notas_frequencia.store', $disciplina->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                    <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivoAtual->id ?? '' }}">
                    <input type="hidden" name="trimestre" value="{{ $trimestre }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="notasTable">
                            <thead>
                                <tr>
                                    <th>Turma</th>
                                    <th>Nome do Estudante</th>
                                    <th style="width: 80px;" class="text-center bg-info">ACS 1</th>
                                    <th style="width: 80px;" class="text-center bg-info">ACS 2</th>
                                    <th style="width: 80px;" class="text-center bg-info">ACS 3</th>
                                    <th style="width: 80px;" class="text-center bg-warning">ACP</th>
                                    <th style="width: 80px;" class="text-center bg-danger text-white">ACF</th>
                                    <th style="width: 80px;" class="text-center bg-success">MT</th>
                                    <th style="width: 100px;">Comp.</th>
                                    <th style="width: 80px;">Faltas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $idx => $est)
                                    @php
                                        $nota = $est->nota_trimestre;
                                    @endphp
                                    <tr>
                                        <td><span class="badge badge-info">{{ $est->turma->nome ?? 'N/A' }}</span></td>
                                        <td><strong>{{ $est->user->name ?? 'N/A' }}</strong></td>

                                        <!-- ACS1 -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20"
                                                class="form-control form-control-sm text-center nota-input" name="notas[{{ $est->id }}][acs1]"
                                                value="{{ $nota?->acs1 }}" data-estudante="{{ $est->id }}">
                                        </td>
                                        <!-- ACS2 -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20"
                                                class="form-control form-control-sm text-center nota-input" name="notas[{{ $est->id }}][acs2]"
                                                value="{{ $nota?->acs2 }}" data-estudante="{{ $est->id }}">
                                        </td>
                                        <!-- ACS3 -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20"
                                                class="form-control form-control-sm text-center nota-input" name="notas[{{ $est->id }}][acs3]"
                                                value="{{ $nota?->acs3 }}" data-estudante="{{ $est->id }}">
                                        </td>
                                        <!-- ACP -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20"
                                                class="form-control form-control-sm text-center nota-input" name="notas[{{ $est->id }}][acp]"
                                                value="{{ $nota?->acp }}" data-estudante="{{ $est->id }}">
                                        </td>
                                        <!-- ACF -->
                                        <td>
                                            <input type="number" step="0.5" min="0" max="20"
                                                class="form-control form-control-sm text-center nota-input" name="notas[{{ $est->id }}][acf]"
                                                value="{{ $nota?->acf }}" data-estudante="{{ $est->id }}">
                                        </td>

                                        <!-- Media Trimestral -->
                                        <td class="text-center font-weight-bold">
                                            @if($nota?->media_trimestral)
                                                <span class="badge badge-{{ $nota->media_trimestral >= 10 ? 'success' : 'danger' }}">
                                                    {{ number_format($nota->media_trimestral, 1) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <!-- Comportamento -->
                                        <td>
                                            <select name="notas[{{ $est->id }}][comportamento]" class="form-control form-control-sm">
                                                <option value="">—</option>
                                                <option value="Bom" {{ $nota?->comportamento == 'Bom' ? 'selected' : '' }}>Bom</option>
                                                <option value="Razoável" {{ $nota?->comportamento == 'Razoável' ? 'selected' : '' }}>Razoável</option>
                                                <option value="Mau" {{ $nota?->comportamento == 'Mau' ? 'selected' : '' }}>Mau</option>
                                            </select>
                                        </td>

                                        <!-- Faltas -->
                                        <td>
                                            <input type="number" min="0" step="1"
                                                class="form-control form-control-sm text-center" name="notas[{{ $est->id }}][faltas]"
                                                value="{{ $nota?->faltas ?? 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group text-center mt-3">
                        <button type="submit" class="btn btn-lg btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Notas do {{ $trimestre }}º Trimestre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Validação simples
            $('.nota-input').on('input', function() {
                let val = parseFloat($(this).val());
                if (val < 0) $(this).val(0);
                if (val > 20) $(this).val(20);
            });
        });
    </script>
@endsection
