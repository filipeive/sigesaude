@extends('adminlte::page')

@section('title', 'Dashboard do Estudante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</h1>
            <p class="text-muted"><i class="fas fa-user-graduate mr-1"></i> Bem-vindo, {{ Auth::user()->name }}</p>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog mr-1"></i> Ações Rápidas
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('estudante.perfil.index') }}">
                    <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                </a>
                <a class="dropdown-item" href="{{ route('estudante.pagamentos') }}">
                    <i class="fas fa-money-bill-wave mr-2"></i> Ver Pagamentos
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('estudante/notas_frequencia') }}">
                    <i class="fas fa-chart-line mr-2"></i> Consultar Notas
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <!-- Mensagem de boas-vindas com animação -->
    <div class="welcome-banner mb-4">
        <div class="alert bg-gradient-primary text-white alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <i class="fas fa-bullhorn fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">Olá, {{ Auth::user()->name }}!</h5>
                    <p class="mb-0">Bem-vindo ao seu painel acadêmico. Aqui você pode acompanhar suas atividades,
                        pagamentos e informações do curso.</p>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <!-- Status Cards com animação hover -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info elevation-3 position-relative overflow-hidden">
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-primary">
                        Atual
                    </div>
                </div>
                <div class="inner">
                    <h3>{{ $totalDisciplinas }}</h3>
                    <p>Disciplinas Matriculadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('estudante.matriculas') }}" class="small-box-footer">
                    Ver detalhes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success elevation-3">
                <div class="inner">
                    <h3 style="font-size: 1.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $estudante->curso->nome ?? 'N/A' }}
                    </h3>
                    <p>Meu Curso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#cursoModal">
                    Informações do curso <i class="fas fa-info-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning elevation-3">
                <div class="inner">
                    <h3>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</h3>
                    <p>Ano Letivo Atual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#calendarioModal">
                    Ver calendário <i class="fas fa-calendar"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger elevation-3">
                <div class="inner">
                    <h3>{{ $estudante->turno }}</h3>
                    <p>Turno</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="#" class="small-box-footer" data-toggle="modal" data-target="#horarioModal">
                    Ver horário <i class="fas fa-list"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Próximos Eventos e Progresso -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="dashboard-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-calendar-tab" data-toggle="pill" href="#tab-calendar"
                                role="tab" aria-controls="tab-calendar" aria-selected="true">
                                <i class="fas fa-calendar-alt mr-1"></i> Calendário Acadêmico
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-payments-tab" data-toggle="pill" href="#tab-payments" role="tab"
                                aria-controls="tab-payments" aria-selected="false">
                                <i class="fas fa-money-bill-wave mr-1"></i> Pagamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-notifications-tab" data-toggle="pill" href="#tab-notifications"
                                role="tab" aria-controls="tab-notifications" aria-selected="false">
                                <i class="fas fa-bell mr-1"></i> Notificações
                                <span class="badge badge-danger ml-1">{{ count($ultimasNotificacoes) }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dashboard-tabContent">
                        <!-- Tab Calendário -->
                        <div class="tab-pane fade show active" id="tab-calendar" role="tabpanel"
                            aria-labelledby="tab-calendar-tab">
                            <div id="calendar"></div>
                        </div>

                        <!-- Tab Pagamentos -->
                        <div class="tab-pane fade" id="tab-payments" role="tabpanel" aria-labelledby="tab-payments-tab">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th class="text-right">Valor</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ultimosPagamentos as $pagamento)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($pagamento->data_pagamento)) }}</td>
                                                <td>{{ $pagamento->descricao ?? 'Pagamento' }}</td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                                <td class="text-center">
                                                    @if ($pagamento->status == 'pago')
                                                        <span class="badge badge-success"><i
                                                                class="fas fa-check-circle mr-1"></i> Pago</span>
                                                    @elseif($pagamento->status == 'pendente')
                                                        <span class="badge badge-warning"><i
                                                                class="fas fa-clock mr-1"></i> Pendente</span>
                                                    @elseif($pagamento->status == 'em_analise')
                                                        <span class="badge badge-info"><i class="fas fa-search mr-1"></i>
                                                            Em Análise</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary">{{ $pagamento->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-3">
                                                    <div class="text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i> Nenhum pagamento
                                                        registrado.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('estudante.pagamentos') }}" class="btn btn-primary">
                                    <i class="fas fa-list mr-1"></i> Ver Todos os Pagamentos
                                </a>
                            </div>
                        </div>

                        <!-- Tab Notificações -->
                        <div class="tab-pane fade" id="tab-notifications" role="tabpanel"
                            aria-labelledby="tab-notifications-tab">
                            <div class="timeline timeline-inverse">
                                @forelse($ultimasNotificacoes as $notificacao)
                                    <div class="time-label">
                                        <span class="bg-primary">
                                            {{ date('d/m/Y', strtotime($notificacao['data'])) }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-envelope bg-primary"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i>
                                                {{ date('H:i', strtotime($notificacao['data'])) }}</span>
                                            <h3 class="timeline-header">{{ $notificacao['titulo'] ?? 'Notificação' }}</h3>
                                            <div class="timeline-body">
                                                {{ $notificacao['mensagem'] }}
                                            </div>
                                            @if (isset($notificacao['link']))
                                                <div class="timeline-footer">
                                                    <a href="{{ $notificacao['link'] }}"
                                                        class="btn btn-primary btn-sm">Mais detalhes</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Nenhuma notificação recente.</p>
                                    </div>
                                @endforelse
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('estudante.notificacoes') }}" class="btn btn-primary">
                                    <i class="fas fa-bell mr-1"></i> Ver Todas as Notificações
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Progresso Acadêmico -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Progresso Acadêmico</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <input type="text" class="knob" value="{{ $progressoCurso ?? 0 }}" data-width="120"
                            data-height="120" data-fgColor="#28a745" data-readonly="true">
                        <div class="knob-label text-success">Progresso do Curso</div>
                    </div>

                    <h5 class="text-center mt-4">Disciplinas Atuais</h5>

                    @if (isset($notasFrequencia) && $notasFrequencia->count() > 0)
                        @foreach ($notasFrequencia as $nota)
                            <div class="progress-group">
                                <span class="progress-text">{{ $nota['disciplina'] }}</span>
                                <span class="float-right"><b>{{ $nota['media'] }}</b>/20</span>
                                <div class="progress">
                                    <div class="progress-bar bg-{{ $nota['cor'] }}"
                                        style="width: {{ min(($nota['media'] / 20) * 100, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <p>Nenhuma disciplina em andamento.</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        <small class="d-block text-muted">Legenda:</small>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="badge badge-success">≥ 14 Excelente</span>
                            <span class="badge badge-primary">≥ 10 Aprovado</span>
                            <span class="badge badge-warning">≥ 8 Recuperação</span>
                            <span class="badge badge-danger">
                                < 8 Reprovado</span>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('estudante.notas_frequencia.notas') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-chart-bar mr-1"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
            <!-- Próximos Prazos -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Próximos Prazos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="todo-list" data-widget="todo-list">
                        <li>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo1" id="todoCheck1">
                                <label for="todoCheck1"></label>
                            </div>
                            <span class="text">Pagamento de Propina</span>
                            <small class="badge badge-danger"><i class="far fa-clock"></i> 2 dias</small>
                        </li>
                        <li>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo2" id="todoCheck2">
                                <label for="todoCheck2"></label>
                            </div>
                            <span class="text">Entrega de Trabalho</span>
                            <small class="badge badge-warning"><i class="far fa-clock"></i> 5 dias</small>
                        </li>
                        <li>
                            <span class="handle">
                                <i class="fas fa-ellipsis-v"></i>
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                            <div class="icheck-primary d-inline ml-2">
                                <input type="checkbox" value="" name="todo3" id="todoCheck3">
                                <label for="todoCheck3"></label>
                            </div>
                            <span class="text">Inscrição para Exames</span>
                            <small class="badge badge-info"><i class="far fa-clock"></i> 1 semana</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Atalhos Rápidos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-th mr-2"></i>Atalhos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.inscricoes.index') }}" class="btn-app-custom">
                                <div class="icon-box bg-primary">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <span>Minhas Inscrições</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.pagamentos') }}" class="btn-app-custom">
                                <div class="icon-box bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <span>Pagamentos</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ url('estudante/notas_frequencia') }}" class="btn-app-custom">
                                <div class="icon-box bg-warning">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <span>Notas</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.perfil.index') }}" class="btn-app-custom">
                                <div class="icon-box bg-danger">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>Perfil</span>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="#" class="btn-app-custom">
                                <div class="icon-box bg-info">
                                    <i class="fas fa-book"></i>
                                </div>
                                <span>Biblioteca</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="#" class="btn-app-custom">
                                <div class="icon-box bg-secondary">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <span>Exames</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="#" class="btn-app-custom">
                                <div class="icon-box bg-dark">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <span>Suporte</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="#" class="btn-app-custom">
                                <div class="icon-box bg-purple">
                                    <i class="fas fa-file-download"></i>
                                </div>
                                <span>Documentos</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modais -->
    <div class="modal fade" id="cursoModal" tabindex="-1" role="dialog" aria-labelledby="cursoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="cursoModalLabel">Informações do Curso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-graduation-cap fa-4x text-success mb-3"></i>
                        <h4>{{ $estudante->curso->nome ?? 'N/A' }}</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Coordenador:</th>
                                <td>{{ $estudante->curso->coordenador ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Duração:</th>
                                <td>{{ $estudante->curso->duracao ?? 'N/A' }} anos</td>
                            </tr>
                            <tr>
                                <th>Grau:</th>
                                <td>{{ $estudante->curso->grau ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Departamento:</th>
                                <td>{{ $estudante->curso->departamento ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <a href="#" class="btn btn-success">Ver Plano Curricular</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <style>
        /* Animações e estilos personalizados */
        .welcome-banner {
            animation: fadeInDown 0.5s ease-out;
        }

        .small-box {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        }

        .btn-app-custom {
            display: block;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .btn-app-custom:hover {
            transform: translateY(-5px);
            text-decoration: none;
            color: #333;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
        }

        .icon-box i {
            font-size: 24px;
            color: white;
        }

        .btn-app-custom:hover .icon-box {
            transform: scale(1.1);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .timeline-item {
            border-radius: 0.5rem;
        }

        .badge {
            font-weight: 500;
            padding: 0.4em 0.6em;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .todo-list>li {
            padding: 12px 15px;
        }

        .ribbon-wrapper {
            height: 70px;
            overflow: hidden;
            position: absolute;
            right: -2px;
            top: -2px;
            width: 70px;
            z-index: 10;
        }

        .ribbon {
            box-shadow: 0 0 3px rgba(0, 0, 0, .3);
            font-size: 0.8rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -2px;
            text-align: center;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, .4);
            text-transform: uppercase;
            top: 10px;
            transform: rotate(45deg);
            width: 90px;
        }

        #calendar {
            height: 400px;
        }

        .fc-event {
            cursor: pointer;
            border-radius: 3px;
        }

        .nav-tabs .nav-link {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
    <script>
        $(function() {
            // Inicializar knob
            $('.knob').knob();

            // Inicializar FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                height: 400,
                events: [{
                        title: 'Início das Aulas',
                        start: '2023-02-15',
                        backgroundColor: '#28a745',
                        borderColor: '#28a745'
                    },
                    {
                        title: 'Prazo de Inscrição',
                        start: '2023-03-10',
                        end: '2023-03-15',
                        backgroundColor: '#ffc107',
                        borderColor: '#ffc107'
                    },
                    {
                        title: 'Exames 1º Semestre',
                        start: '2023-06-20',
                        end: '2023-07-05',
                        backgroundColor: '#dc3545',
                        borderColor: '#dc3545'
                    },
                    {
                        title: 'Pagamento de Propina',
                        start: '2023-03-05',
                        backgroundColor: '#17a2b8',
                        borderColor: '#17a2b8'
                    }
                ],
                eventClick: function(info) {
                    // Mostrar detalhes do evento quando clicado
                    Swal.fire({
                        title: info.event.title,
                        html: `
                            <div class="text-left">
                                <p><strong>Data:</strong> ${moment(info.event.start).format('DD/MM/YYYY')}</p>
                                <p><strong>Descrição:</strong> Detalhes sobre ${info.event.title}</p>
                            </div>
                        `,
                        icon: 'info'
                    });
                }
            });
            calendar.render();

            // Animações para os cards
            $('.card').each(function(index) {
                $(this).delay(index * 100).animate({
                    opacity: 1
                }, 500);
            });

            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Checkbox para tarefas (continuação)
            $('.todo-list').on('change', 'input:checkbox', function() {
                var $this = $(this);
                var $parent = $this.closest('li');

                if ($this.prop('checked')) {
                    $parent.find('.text').css('text-decoration', 'line-through');
                    $parent.fadeOut(500, function() {
                        $parent.appendTo('.todo-list').fadeIn(500);
                    });
                } else {
                    $parent.find('.text').css('text-decoration', 'none');
                }
            });

            // Notificações Toast para demonstração
            function showWelcomeNotification() {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                setTimeout(function() {
                    toastr.info(
                        'Confira suas atividades pendentes e prazos importantes.',
                        'Bem-vindo ao seu Dashboard!'
                    );
                }, 1500);
            }

            showWelcomeNotification();

            // Contador de notificações não lidas
            function updateNotificationCount() {
                var count = $('.timeline-item.unread').length;
                if (count > 0) {
                    $('#notification-count').text(count).show();
                } else {
                    $('#notification-count').hide();
                }
            }

            // Atualizar progresso das disciplinas
            $('.progress-bar').each(function() {
                var $this = $(this);
                var width = $this.css('width');
                $this.css('width', '0').animate({
                    width: width
                }, 1000);
            });

            // Efeito hover para os atalhos rápidos
            $('.btn-app-custom').hover(
                function() {
                    $(this).find('.icon-box').addClass('pulse');
                },
                function() {
                    $(this).find('.icon-box').removeClass('pulse');
                }
            );

            // Adicionar classe pulse
            @keyframes pulse {
                0 % {
                    transform: scale(1);
                }
                50 % {
                    transform: scale(1.1);
                }
                100 % {
                    transform: scale(1);
                }
            }

            // Inicializar todos os popovers
            $('[data-toggle="popover"]').popover({
                trigger: 'hover',
                placement: 'top'
            });

            // Atualizar dados em tempo real (simulação)
            function updateDashboardData() {
                setInterval(function() {
                    // Simular atualização de dados
                    var randomProgress = Math.floor(Math.random() * 10) + 90;
                    $('.knob')
                        .val(randomProgress)
                        .trigger('change');
                }, 10000);
            }

            // Inicializar gráficos e animações
            function initCharts() {
                // Aqui você pode adicionar mais gráficos usando Chart.js ou outra biblioteca
                // Exemplo com Chart.js (se estiver incluído):
                if (typeof Chart !== 'undefined') {
                    var ctx = document.getElementById('progressChart');
                    if (ctx) {
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                                datasets: [{
                                    label: 'Progresso Acadêmico',
                                    data: [65, 70, 75, 72, 80, 85],
                                    borderColor: '#28a745',
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    }
                }
            }

            // Manipulador de eventos para tabs
            $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                if ($(e.target).attr('href') === '#tab-calendar') {
                    calendar.updateSize();
                }
            });

            // Função para carregar mais notificações
            function loadMoreNotifications() {
                var $loadMore = $('#load-more-notifications');
                $loadMore.html('<i class="fas fa-spinner fa-spin"></i> Carregando...');

                // Simular carregamento
                setTimeout(function() {
                    // Aqui você faria uma chamada AJAX real
                    $loadMore.html('Carregar mais');
                }, 1000);
            }

            // Manipulador de scroll infinito para notificações
            $('#tab-notifications').on('scroll', function() {
                if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    loadMoreNotifications();
                }
            });

            // Inicializar todos os componentes
            function initDashboard() {
                updateDashboardData();
                initCharts();
                updateNotificationCount();
            }

            // Chamar inicialização
            initDashboard();

            // Atualizar layout quando a janela for redimensionada
            $(window).resize(function() {
                calendar.updateSize();
            });

            // Adicionar interatividade aos cards
            $('.card').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );

            // Manipular cliques nos eventos do calendário
            $('.fc-event').click(function() {
                // Adicionar sua lógica aqui
            });
        });

        // Função para exportar dados
        function exportData(type) {
            Swal.fire({
                title: 'Exportando dados...',
                text: 'Por favor, aguarde enquanto preparamos seu arquivo.',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    Swal.fire(
                        'Sucesso!',
                        'Seus dados foram exportados com sucesso.',
                        'success'
                    );
                }
            });
        }
    </script>
@stop
