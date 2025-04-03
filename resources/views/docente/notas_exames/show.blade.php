@extends('adminlte::page')

@section('title', 'Gerenciamento de Exames - ' . $disciplina->nome)

@section('content_header')
    <h1>Lançamento de Exames: {{ $disciplina->nome }}</h1>
@stop

@section('content')
    <div class="container-fluid px-4">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('docente.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.disciplinas') }}">Disciplinas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('docente.notas_exames.index') }}">Exames</a></li>
            <li class="breadcrumb-item active">Lançamento de Exames</li>
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
                    <i class="fas fa-pen-alt me-1"></i>
                    Lançamento de Exames - Ano Letivo: {{ $anoLectivoAtual->ano ?? 'N/A' }}
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" form="formExames">
                        <i class="fas fa-save me-1"></i> Salvar Notas de Exames
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Tabs para alternar entre as diferentes visualizações -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="admitidos-tab" data-toggle="tab" href="#admitidos" 
                            role="tab" aria-controls="admitidos" aria-selected="true">
                            <i class="fas fa-pencil-alt me-1"></i> Estudantes Admitidos para Exame
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="dispensados-tab" data-toggle="tab" href="#dispensados" 
                            role="tab" aria-controls="dispensados" aria-selected="false">
                            <i class="fas fa-check-circle me-1"></i> Estudantes Dispensados
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="excluidos-tab" data-toggle="tab" href="#excluidos" 
                            role="tab" aria-controls="excluidos" aria-selected="false">
                            <i class="fas fa-times-circle me-1"></i> Estudantes Excluídos
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- Tab para estudantes admitidos a exame -->
                    <div class="tab-pane fade show active" id="admitidos" role="tabpanel" aria-labelledby="admitidos-tab">
                        @if(count($estudantes) > 0)
                            <form id="formExames" action="{{ route('docente.notas_exames.salvar') }}" method="POST">
                                @csrf
                                <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                                <input type="hidden" name="ano_lectivo_id" value="{{ $anoLectivoAtual->id ?? '' }}">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped datatable">
                                        <thead>
                                            <tr>
                                                <th class="align-middle" rowspan="2">Nome do Estudante</th>
                                                <th class="align-middle" rowspan="2">Nota de Frequência</th>
                                                <th class="text-center" colspan="2">Exames</th>
                                                <th class="align-middle" rowspan="2">Média Final</th>
                                                <th class="align-middle" rowspan="2">Status</th>
                                            </tr>
                                            <tr>
                                                <th>Normal</th>
                                                <th>Recorrência</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($estudantes as $item)
                                                <tr>
                                                    <td>{{ $item['estudante']->user->name ?? 'N/A' }}</td>
                                                    
                                                    <input type="hidden" name="estudante_id[]" value="{{ $item['estudante']->id }}">
                                                    
                                                    <!-- Nota de Frequência (só exibição) -->
                                                    <td class="text-center">
                                                        {{ number_format($item['nota_frequencia'], 2) }}
                                                    </td>
                                                    
                                                    <!-- Exame Normal -->
                                                    <td>
                                                        <input type="number" step="0.01" min="0" max="20"
                                                            class="form-control nota-input" 
                                                            name="notas[{{ $item['estudante']->id }}][Normal]"
                                                            value="{{ $item['nota_exame_normal'] }}" 
                                                            data-estudante="{{ $item['estudante']->id }}">
                                                    </td>
                                                    
                                                    <!-- Exame de Recorrência -->
                                                    <td>
                                                        <input type="number" step="0.01" min="0" max="20"
                                                            class="form-control nota-input" 
                                                            name="notas[{{ $item['estudante']->id }}][Recorrência]"
                                                            value="{{ $item['nota_exame_recorrencia'] }}" 
                                                            data-estudante="{{ $item['estudante']->id }}">
                                                    </td>
                                                    
                                                    <!-- Média Final (calculada) -->
                                                    <td class="text-center">
                                                        {{ $item['media_final'] ? number_format($item['media_final'], 2) : '-' }}
                                                    </td>
                                                    
                                                    <!-- Status -->
                                                    <td class="text-center">
                                                        <span class="badge 
                                                            {{ $item['status'] == 'Aprovado' ? 'bg-success' : 
                                                            ($item['status'] == 'Reprovado' ? 'bg-danger' : 'bg-warning') }}">
                                                            {{ $item['status'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Não há estudantes admitidos para exame nesta disciplina.
                            </div>
                        @endif
                    </div>

                    <!-- Tab para estudantes dispensados -->
                    <div class="tab-pane fade" id="dispensados" role="tabpanel" aria-labelledby="dispensados-tab">
                        @if(isset($estudantesDispensados) && count($estudantesDispensados) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>Nome do Estudante</th>
                                            <th>Nota de Frequência</th>
                                            <th>Média Final</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estudantesDispensados as $item)
                                            <tr>
                                                <td>{{ $item['estudante']->user->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ number_format($item['nota_frequencia'], 2) }}</td>
                                                <td class="text-center">{{ number_format($item['media_final'], 2) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">Dispensado</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Não há estudantes dispensados nesta disciplina.
                            </div>
                        @endif
                    </div>

                    <!-- Tab para estudantes excluídos -->
                    <div class="tab-pane fade" id="excluidos" role="tabpanel" aria-labelledby="excluidos-tab">
                        @if(isset($estudantesExcluidos) && count($estudantesExcluidos) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>Nome do Estudante</th>
                                            <th>Nota de Frequência</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estudantesExcluidos as $item)
                                            <tr>
                                                <td>{{ $item['estudante']->user->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ number_format($item['nota_frequencia'], 2) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger">Excluído</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Não há estudantes excluídos nesta disciplina.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-1"></i>
                Informações sobre Cálculo de Notas de Exames
            </div>
            <div class="card-body">
                <p>O cálculo da média final é feito da seguinte forma:</p>
                <ul>
                    <li><strong>Média Final:</strong> 50% da nota de frequência + 50% da melhor nota de exame (normal ou recorrência)</li>
                    <li><strong>Critério de Aprovação:</strong> Média final igual ou superior a 10 valores</li>
                    <li><strong>Estudantes Dispensados:</strong> Automaticamente aprovados com a nota de frequência</li>
                    <li><strong>Estudantes Excluídos:</strong> Automaticamente reprovados com a nota de frequência</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .nota-input {
            width: 80px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
        
        /* Cores de status customizadas */
        .badge.bg-success {
            background-color: #28a745 !important;
        }
        
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }
        
        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }
        
        .badge.bg-info {
            background-color: #17a2b8 !important;
        }

        /* Melhorias de usabilidade para as tabs */
        .nav-tabs .nav-link {
            color: #495057;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid #007bff;
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTables para todas as tabelas com a classe datatable
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [2, 3]
                }],
                "order": [[0, 'asc']] // Ordena por nome do estudante
            });

            // Validação de valores
            $('.nota-input').on('input', function() {
                let valor = parseFloat($(this).val());
                if (isNaN(valor)) return;
                if (valor < 0) $(this).val(0);
                if (valor > 20) $(this).val(20);
            });

            // Confirmação de envio do formulário
            $('#formExames').on('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Confirmar lançamento de notas',
                    text: "Tem certeza que deseja salvar as notas de exames?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, salvar notas',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Enviando formulário...');
                        this.submit();
                    }
                });
            });
        });
    </script>
@stop
