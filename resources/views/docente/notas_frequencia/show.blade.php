@extends('adminlte::page')

@section('title', 'Gerenciamento de Notas - ' . $disciplina->nome)


@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Notas e Frequência: {{ $disciplina->nome }}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.disciplinas') }}">Disciplinas</a></li>
            <li class="breadcrumb-item active">Notas e Frequência</li>
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

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-list-alt me-1"></i>
                    Lançamento de Notas - Ano Letivo: {{ $anoLectivoAtual->ano ?? 'N/A' }}
                </div>
                <div>
                    <!-- Botão type="submit" dentro do formulário -->
                    <button type="submit" class="btn btn-primary" form="formNotas">
                        <i class="fas fa-save me-1"></i> Salvar Notas
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="formNotas" action="{{ route('docente.notas_frequencia.store', $disciplina->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                    <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivoAtual->id ?? '' }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="notasTable">
                            <thead>
                                <tr>
                                    {{-- <th class="align-middle" rowspan="2">Matrícula</th> --}}
                                    <th class="align-middle" rowspan="2">Nome do Estudante</th>
                                    <th class="text-center" colspan="3">Testes</th>
                                    <th class="text-center" colspan="3">Trabalhos</th>
                                    <th class="align-middle" rowspan="2">Nota Final</th>
                                    <th class="align-middle" rowspan="2">Status</th>
                                </tr>
                                <tr>
                                    <th>Teste 1</th>
                                    <th>Teste 2</th>
                                    <th>Teste 3</th>
                                    <th>Trabalho 1</th>
                                    <th>Trabalho 2</th>
                                    <th>Trabalho 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $item)
                                    @php
                                        $estudante = $item['estudante'];
                                        $notaFreq = $item['notas_frequencia'];
                                        $notasDetalhadas = $item['notas_detalhadas'];

                                        // Organizar notas detalhadas por tipo
                                        $notas = [
                                            'Teste 1' => null,
                                            'Teste 2' => null,
                                            'Teste 3' => null,
                                            'Trabalho 1' => null,
                                            'Trabalho 2' => null,
                                            'Trabalho 3' => null,
                                        ];

                                        foreach ($notasDetalhadas as $nota) {
                                            $notas[$nota->tipo] = $nota->nota;
                                        }
                                    @endphp
                                    <tr>
                                        {{-- <td>{{ $estudante->matricula }}</td> --}}
                                        <td>{{ $estudante->user->name ?? 'N/A' }}</td>

                                        <input type="hidden" name="estudante_id[]" value="{{ $estudante->id }}">

                                        <!-- Teste 1 -->
                                        {{-- <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input" name="notas[{{ $estudante->id }}][Teste 1]"
                                                value="{{ $notas['Teste 1'] }}" data-estudante="{{ $estudante->id }}">
                                        </td> --}}
                                        <!-- Teste 1 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input" name="notas[{{ $estudante->id }}][Teste 1]"
                                                value="{{ $notas['Teste 1'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>
                                        <!-- Teste 2 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input" name="notas[{{ $estudante->id }}][Teste 2]"
                                                value="{{ $notas['Teste 2'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>

                                        <!-- Teste 3 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input" name="notas[{{ $estudante->id }}][Teste 3]"
                                                value="{{ $notas['Teste 3'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>

                                        <!-- Trabalho 1 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input"
                                                name="notas[{{ $estudante->id }}][Trabalho 1]"
                                                value="{{ $notas['Trabalho 1'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>

                                        <!-- Trabalho 2 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input"
                                                name="notas[{{ $estudante->id }}][Trabalho 2]"
                                                value="{{ $notas['Trabalho 2'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>

                                        <!-- Trabalho 3 -->
                                        <td>
                                            <input type="number" step="0.01" min="0" max="20"
                                                class="form-control nota-input"
                                                name="notas[{{ $estudante->id }}][Trabalho 3]"
                                                value="{{ $notas['Trabalho 3'] }}" data-estudante="{{ $estudante->id }}">
                                        </td>

                                        <!-- Nota Final (calculada) -->
                                        <td class="text-center nota-final" data-estudante="{{ $estudante->id }}">
                                            {{ $notaFreq ? number_format($notaFreq->nota, 2) : '-' }}
                                        </td>

                                        <!-- Status (versão compacta) -->
                                        <td class="text-center">
                                            @if ($notaFreq)
                                                <span class="badge 
                                                    {{ $notaFreq->status == 'Admitido' ? 'bg-success' : 
                                                    ($notaFreq->status == 'Excluído' ? 'bg-danger' : 
                                                    ($notaFreq->status == 'Dispensado' ? 'bg-info' : 'bg-warning')) }}">
                                                    {{ $notaFreq->status }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Não avaliado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i>
                Informações sobre Cálculo de Notas
            </div>
            <div class="card-body">
                <p>O cálculo da nota final de frequência é feito da seguinte forma:</p>
                <ul>
                    <li><strong>Média dos Testes:</strong> 60% da nota final</li>
                    <li><strong>Média dos Trabalhos:</strong> 40% da nota final</li>
                    <li><strong>Critério de Admissão:</strong> Nota final igual ou superior a 10 valores</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var table = $('#notasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [2, 3, 4, 5, 6, 7]
                }]
            });

            // Manipulador para botão salvar - versão corrigida
            $('#btnSalvarNotas').on('click', function(e) {
                e.preventDefault(); // Previne comportamento padrão do botão
                console.log('Botão salvar clicado');

                // Verificar se o formulário existe
                if ($('#formNotas').length > 0) {
                    console.log('Formulário encontrado, enviando...');
                    document.getElementById('formNotas').submit();
                } else {
                    console.error('Formulário com ID formNotas não encontrado!');
                }
            });
            // Adicione este trecho ao seu JavaScript existente
            $('#formNotas').on('submit', function(e) {
                console.log('Formulário está sendo enviado');

                // Logue os dados que estão sendo enviados
                const formData = new FormData(this);
                const entries = [...formData.entries()];
                console.log('Dados do formulário:', entries);

                // Remova esta linha para permitir o envio do formulário
                // e.preventDefault();
            });

            // Validação de valores
            $('.nota-input').on('input', function() {
                let valor = parseFloat($(this).val());
                if (valor < 0) $(this).val(0);
                if (valor > 20) $(this).val(20);
            });
        });
    </script>
@endsection

@section('styles')
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .nota-input {
            width: 70px;
        }

        #notasTable th,
        #notasTable td {
            vertical-align: middle;
        }
    </style>
@endsection
