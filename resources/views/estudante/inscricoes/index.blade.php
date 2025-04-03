@extends('adminlte::page')

@section('title', 'Inscrições')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="m-0 text-dark">
                <i class="fas fa-clipboard-list mr-2"></i>
                Minhas Inscrições
            </h1>
            <a href="{{ route('estudante.inscricoes.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus-circle mr-2"></i> Nova Inscrição
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Quick Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-card info-card-warning">
                            <div class="info-card-icon">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="info-card-content">
                                <h3>{{ $inscricoesPendentes->count() }}</h3>
                                <p>Inscrições Pendentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card info-card-success">
                            <div class="info-card-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="info-card-content">
                                <h3>{{ $inscricoesConfirmadas->count() }}</h3>
                                <p>Inscrições Confirmadas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card info-card-info">
                            <div class="info-card-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="info-card-content">
                                <h3>{{ $inscricoesConfirmadas->count() + $inscricoesPendentes->count() }}</h3>
                                <p>Total de Inscrições</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Tabs -->
                <div class="card card-outline card-primary">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs nav-fill" id="inscricoes-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pendentes-tab" data-toggle="tab" href="#pendentes" role="tab">
                                    <i class="fas fa-hourglass-half mr-2"></i> 
                                    Pendentes 
                                    <span class="badge badge-light ml-2">{{ $inscricoesPendentes->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="confirmadas-tab" data-toggle="tab" href="#confirmadas" role="tab">
                                    <i class="fas fa-check-circle mr-2"></i> 
                                    Confirmadas 
                                    <span class="badge badge-light ml-2">{{ $inscricoesConfirmadas->count() }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="inscricoes-tabs-content">
                            <!-- Pending Registrations Tab -->
                            <div class="tab-pane fade show active" id="pendentes" role="tabpanel">
                                @include('estudante.inscricoes.partials.pendentes-table')
                            </div>
                            
                            <!-- Confirmed Registrations Tab -->
                            <div class="tab-pane fade" id="confirmadas" role="tabpanel">
                                @include('estudante.inscricoes.partials.confirmadas-table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Info Cards */
        .info-card {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }

        .info-card-icon {
            font-size: 3rem;
            margin-right: 20px;
            opacity: 0.7;
            color: #6c757d;
        }

        .info-card-warning .info-card-icon { color: #ffc107; }
        .info-card-success .info-card-icon { color: #28a745; }
        .info-card-info .info-card-icon { color: #17a2b8; }

        .info-card-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #343a40;
        }

        .info-card-content p {
            margin: 0;
            color: #6c757d;
            font-size: 1rem;
        }

        /* Tabs */
        .nav-tabs .nav-link {
            color: #6c757d;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border-bottom-color: #007bff;
            font-weight: 600;
        }

        .nav-tabs .badge {
            background-color: rgba(0,123,255,0.1);
            color: #007bff;
        }

        /* Table Enhancements */
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
            transition: background-color 0.2s ease;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .info-card-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Tab Management
            $('#inscricoes-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                const targetTab = $(e.target).attr('href');
                localStorage.setItem('activeInscricoesTab', targetTab);
            });

            // Restore Active Tab
            const activeTab = localStorage.getItem('activeInscricoesTab');
            if (activeTab) {
                $('#inscricoes-tabs a[href="' + activeTab + '"]').tab('show');
            }

            // Cancellation Confirmation
            $('.btn-cancel-inscricao').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Confirmar Cancelamento',
                    text: "Tem certeza que deseja cancelar esta inscrição? Esta ação não pode ser desfeita.",
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