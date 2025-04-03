@extends('adminlte::page')

@section('title', 'Inscrições')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-clipboard-list mr-2"></i>Minhas Inscrições</h1>
        <a href="{{ route('estudante.inscricoes.create') }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i> Nova Inscrição
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Estatísticas rápidas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $inscricoesPendentes->count() }}</h3>
                            <p>Inscrições Pendentes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $inscricoesConfirmadas->count() }}</h3>
                            <p>Inscrições Confirmadas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $inscricoesConfirmadas->count() + $inscricoesPendentes->count() }}</h3>
                            <p>Total de Inscrições</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Abas para alternar entre as inscrições -->
            <div class="card">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" id="inscricoes-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pendentes-tab" data-toggle="tab" href="#pendentes" role="tab" aria-controls="pendentes" aria-selected="true">
                                <i class="fas fa-hourglass-half mr-1"></i> Pendentes 
                                <span class="badge badge-warning ml-1">{{ $inscricoesPendentes->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="confirmadas-tab" data-toggle="tab" href="#confirmadas" role="tab" aria-controls="confirmadas" aria-selected="false">
                                <i class="fas fa-check-circle mr-1"></i> Confirmadas 
                                <span class="badge badge-success ml-1">{{ $inscricoesConfirmadas->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="inscricoes-tabs-content">
                        <!-- Tab Inscrições Pendentes -->
                        <div class="tab-pane fade show active" id="pendentes" role="tabpanel" aria-labelledby="pendentes-tab">
                            @if($inscricoesPendentes->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                    <p class="mb-0">Nenhuma inscrição pendente no momento.</p>
                                    <a href="{{ route('estudante.inscricoes.create') }}" class="btn btn-outline-success mt-3">
                                        <i class="fas fa-plus mr-1"></i> Criar Nova Inscrição
                                    </a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Ano Lectivo</th>
                                                <th>Semestre</th>
                                                <th>Data</th>
                                                <th>Referência</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inscricoesPendentes as $inscricao)
                                                <tr>
                                                    <td>{{ $inscricao->anoLectivo->ano }}</td>
                                                    <td>{{ $inscricao->semestre }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($inscricao->referencia)
                                                            <span class="text-monospace">{{ $inscricao->referencia }}</span>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inscricao->valor)
                                                            <span class="font-weight-bold">{{ number_format($inscricao->valor, 2) }} MT</span>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning">Pendente</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <a href="{{ route('estudante.inscricoes.show', $inscricao->id) }}" class="btn btn-sm btn-primary" title="Ver detalhes">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <form action="{{ route('estudante.inscricoes.cancelar', $inscricao->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('POST')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Cancelar inscrição" 
                                                                    onclick="return confirm('Tem certeza que deseja cancelar esta inscrição? Esta ação não pode ser desfeita.')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Tab Inscrições Confirmadas -->
                        <div class="tab-pane fade" id="confirmadas" role="tabpanel" aria-labelledby="confirmadas-tab">
                            @if($inscricoesConfirmadas->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                    <p class="mb-0">Nenhuma inscrição confirmada no momento.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Ano Lectivo</th>
                                                <th>Semestre</th>
                                                <th>Data</th>
                                                <th>Referência</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inscricoesConfirmadas as $inscricao)
                                                <tr>
                                                    <td>{{ $inscricao->anoLectivo->ano }}</td>
                                                    <td>{{ $inscricao->semestre }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($inscricao->data_inscricao)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($inscricao->referencia)
                                                            <span class="text-monospace">{{ $inscricao->referencia }}</span>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inscricao->valor)
                                                            <span class="font-weight-bold">{{ number_format($inscricao->valor, 2) }} MT</span>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">Confirmada</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('estudante.inscricoes.show', $inscricao->id) }}" class="btn btn-sm btn-primary" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Estilização geral */
        .small-box {
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            position: relative;
            display: block;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .small-box .inner {
            padding: 15px;
        }
        
        .small-box .icon {
            color: rgba(0, 0, 0, 0.15);
            z-index: 0;
            font-size: 70px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: transform .3s linear;
        }
        
        .small-box:hover .icon {
            transform: scale(1.1);
        }
        
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        
        .small-box p {
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        /* Estilos da tabela */
        .table thead th {
            border-bottom-width: 1px;
            font-weight: 600;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }
        
        /* Formatação de texto e badges */
        .text-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
        }
        
        /* Estilização das abas */
        .nav-tabs .nav-link {
            color: #495057;
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #fff;
            border-radius: 0.25rem 0.25rem 0 0;
            padding: 0.75rem 1.25rem;
        }
        
        .nav-tabs .nav-link.active {
            font-weight: 600;
            background-color: #fff;
        }
        
        /* Botões e ações */
        .btn-group > .btn {
            margin-right: 2px;
        }
        
        /* Mensagens vazias */
        .text-muted {
            color: #6c757d !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            console.log('Página de inscrições carregada.');
            
            // Mantém a aba ativa ao recarregar a página
            var activeTab = localStorage.getItem('activeInscricoesTab');
            if (activeTab) {
                $('#inscricoes-tabs a[href="' + activeTab + '"]').tab('show');
            }
            
            // Salva a aba ativa quando alterada
            $('#inscricoes-tabs a').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeInscricoesTab', $(e.target).attr('href'));
            });
            
            // Confirmação personalizada para cancelar inscrição
            $('.btn-danger').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Confirmar Cancelamento',
                    text: "Esta ação não poderá ser revertida. Deseja continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, cancelar',
                    cancelButtonText: 'Não'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop