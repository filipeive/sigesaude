@extends('adminlte::page')

@section('title', 'Dashboard do Estudante')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-graduate mr-1"></i>
                {{ Auth::user()->name }}
                @if($estudante && $estudante->turma)
                    — {{ $estudante->turma->classe->nome ?? '' }} {{ $estudante->turma->nome ?? '' }}
                    · {{ $estudante->anoLectivo->ano ?? 'N/A' }}
                @endif
            </p>
        </div>
        <div class="btn-group mt-2 mt-sm-0">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bolt mr-1"></i> Ações Rápidas
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('estudante.perfil.index') }}">
                    <i class="fas fa-user-edit mr-2"></i> Editar Perfil
                </a>
                <a class="dropdown-item" href="{{ route('estudante.pagamentos') }}">
                    <i class="fas fa-money-bill-wave mr-2"></i> Pagar Propinas
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
    @php
        $mediaGeral   = is_array($estatisticas) && isset($estatisticas['media_geral'])
                        ? number_format($estatisticas['media_geral'], 1) : '—';
        $presenca     = is_array($estatisticas) ? ($estatisticas['presenca'] ?? '—') : '—';
        $pagPendentes = is_array($estatisticas) ? ($estatisticas['pagamentos_pendentes'] ?? 0) : 0;
        $totalPend    = is_array($estatisticas)
                        ? number_format($estatisticas['total_pendente'] ?? 0, 0, ',', '.')
                        : '0';
        $totalPg      = is_array($estatisticas)
                        ? number_format($estatisticas['total_pago'] ?? 0, 0, ',', '.')
                        : '0';
    @endphp

    <!-- Banner de boas-vindas -->
    <div class="welcome-banner mb-4">
        <div class="alert bg-gradient-primary text-white alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="mr-3"><i class="fas fa-bullhorn fa-2x"></i></div>
                <div>
                    <h5 class="alert-heading mb-1">Olá, {{ Auth::user()->name }}!</h5>
                    <p class="mb-0">
                        @if($estudante && $estudante->turma)
                            Turma: <strong>{{ $estudante->turma->classe->nome }} — {{ $estudante->turma->nome }}</strong> ·
                            Ano Lectivo: <strong>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</strong> ·
                            Turno: <strong>{{ $estudante->turno }}</strong>&nbsp;&nbsp;|&nbsp;&nbsp;
                            Média: <strong>{{ $mediaGeral }}/20</strong> ·
                            Frequência: <strong>{{ $presenca }}</strong>
                        @else
                            Complete o seu cadastro na secretaria para ser alocado em uma turma.
                        @endif
                    </p>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    </div>

    <!-- Cards principais -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-6">
            <div class="small-box bg-gradient-info elevation-3">
                <div class="inner">
                    <h3 style="font-size: 1.2rem; white-space: nowrap;">
                        @if($estudante && $estudante->turma)
                            {{ $estudante->turma->classe->nome }}
                        @else
                            N/A
                        @endif
                    </h3>
                    <p>
                        @if($estudante && $estudante->turma)
                            <strong>{{ $estudante->turma->nome }}</strong><br>
                            <small>{{ $estudante->anoLectivo->ano ?? 'N/A' }} · {{ $estudante->turno ?? '' }}</small>
                        @else
                            <small>Sem turma atribuída</small>
                        @endif
                    </p>
                </div>
                <div class="icon"><i class="fas fa-school"></i></div>
                <a href="{{ route('estudante.matriculas') }}" class="small-box-footer">
                    Guia de matrícula <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
            <div class="small-box bg-gradient-success elevation-3">
                <div class="inner">
                    <h3>{{ $estudante->matricula ?? 'N/A' }}</h3>
                    <p>Nº de Matrícula</p>
                </div>
                <div class="icon"><i class="fas fa-id-card"></i></div>
                <a href="{{ route('estudante.matriculas') }}" class="small-box-footer">
                    Ver documento <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
            <div class="small-box bg-gradient-warning elevation-3">
                <div class="inner">
                    <h3>{{ $pagPendentes }}</h3>
                    <p>
                        Pagamentos Pendentes<br>
                        <small>{{ $totalPend }} MZN em aberto</small>
                    </p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <a href="{{ route('estudante.pagamentos') }}" class="small-box-footer">
                    Pagar agora <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
            <div class="small-box bg-gradient-danger elevation-3">
                <div class="inner">
                    <h3>{{ $totalPg }}</h3>
                    <p>
                        MZN Total Pago<br>
                        <small>{{ $estudante->anoLectivo->ano ?? 'Este ano' }}</small>
                    </p>
                </div>
                <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                <a href="{{ route('estudante.pagamentos') }}" class="small-box-footer">
                    Histórico <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendário · Pagamentos Recentes · Notificações -->
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="dashboard-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-calendar-tab" data-toggle="pill" href="#tab-calendar" role="tab">
                                <i class="fas fa-calendar-alt mr-1"></i> Calendário
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-payments-tab" data-toggle="pill" href="#tab-payments" role="tab">
                                <i class="fas fa-money-bill-wave mr-1"></i> Pagamentos Recentes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-notifications-tab" data-toggle="pill" href="#tab-notifications" role="tab">
                                <i class="fas fa-bell mr-1"></i> Notificações
                                @if(!empty($ultimasNotificacoes) && count($ultimasNotificacoes) > 0)
                                    <span class="badge badge-danger ml-1">{{ count($ultimasNotificacoes) }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dashboard-tabContent">
                        <div class="tab-pane fade show active" id="tab-calendar" role="tabpanel">
                            <div id="calendar"></div>
                        </div>

                        <div class="tab-pane fade" id="tab-payments" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Vencimento</th>
                                            <th>Referência</th>
                                            <th class="text-right">Valor</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ultimosPagamentos as $pagamento)
                                            <tr>
                                                <td><i class="fas fa-receipt mr-1 text-muted"></i> {{ $pagamento->descricao ?? 'Pagamento' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                                                <td><code>{{ $pagamento->referencia }}</code></td>
                                                <td class="text-right font-weight-bold">{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                                <td class="text-center">
                                                    @if($pagamento->status == 'pago')
                                                        <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Pago</span>
                                                    @else
                                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Pendente</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">
                                                    <i class="fas fa-info-circle mr-1"></i> Nenhum pagamento registrado.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('estudante.pagamentos') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-list mr-1"></i> Ver Todos os Pagamentos
                                </a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-notifications" role="tabpanel">
                            <div class="timeline timeline-inverse px-2">
                                @forelse($ultimasNotificacoes as $notificacao)
                                    <div class="time-label">
                                        <span class="bg-primary">{{ \Carbon\Carbon::parse($notificacao['data'])->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-bell bg-primary"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($notificacao['data'])->format('H:i') }}</span>
                                            <h3 class="timeline-header">{{ $notificacao['titulo'] ?? 'Notificação' }}</h3>
                                            <div class="timeline-body">{{ $notificacao['mensagem'] }}</div>
                                            @if(isset($notificacao['link']))
                                                <div class="timeline-footer">
                                                    <a href="{{ $notificacao['link'] }}" class="btn btn-primary btn-sm">Mais detalhes</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="text-muted mb-0">Nenhuma notificação recente.</p>
                                    </div>
                                @endforelse
                                @if(!empty($ultimasNotificacoes))<div><i class="far fa-clock bg-gray"></i></div>@endif
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('estudante.notificacoes') }}" class="btn btn-primary">
                                    <i class="fas fa-bell mr-1"></i> Ver Todas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Resumo Académico -->
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Resumo Académico</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <tbody>
                                <tr>
                                    <th>Média Geral</th>
                                    <td class="text-right">
                                        <span class="badge badge-{{ (float)$mediaGeral >= 10 ? 'success' : 'warning' }}">
                                            {{ $mediaGeral }}/20
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Frequência</th>
                                    <td class="text-right">{{ $presenca }}</td>
                                </tr>
                                <tr>
                                    <th>Turno</th>
                                    <td class="text-right">{{ $estudante->turno ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Pagamentos Pendentes</th>
                                    <td class="text-right">{{ $pagPendentes }}</td>
                                </tr>
                                <tr>
                                    <th>Total Pago</th>
                                    <td class="text-right text-success">{{ $totalPg }} MZN</td>
                                </tr>
                                <tr>
                                    <th>Total Pendente</th>
                                    <td class="text-right text-warning">{{ $totalPend }} MZN</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <small class="d-block text-muted">Legenda de Notas:</small>
                        <div class="d-flex flex-wrap mt-2">
                            <span class="badge badge-success mr-1 mb-1">≥ 14 Excelente</span>
                            <span class="badge badge-primary mr-1 mb-1">≥ 10 Aprovado</span>
                            <span class="badge badge-warning mr-1 mb-1">≥ 8 Recuperação</span>
                            <span class="badge badge-danger font-weight-bold">&lt; 8 Reprovado</span>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url('estudante/notas_frequencia') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chart-bar mr-1"></i> Ver Boletim Completo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Próximos Prazos -->
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clock mr-2"></i>Próximos Prazos</h3>
                </div>
                <div class="card-body p-0">
                    @if(!empty($proximosPrazos))
                        <ul class="todo-list" data-widget="todo-list">
                            @foreach($proximosPrazos as $p)
                                <li>
                                    <span class="handle"><i class="fas fa-ellipsis-v"></i></span>
                                    <span class="text">
                                        <a href="{{ $p['url'] }}" style="color: inherit; text-decoration: none;">
                                            <i class="far fa-file-alt mr-1"></i>{{ $p['descricao'] }}
                                        </a>
                                    </span>
                                    <small class="badge {{ $p['badge'] }}"><i class="far fa-clock"></i> {{ $p['badge_text'] }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <p class="mb-0">Nenhum prazo pendente. Tudo em dia!</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('estudante.pagamentos') }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-money-bill-wave mr-1"></i> Gerir Pagamentos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Atalhos Rápidos -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-th mr-2"></i>Atalhos Rápidos</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.matriculas') }}" class="btn-app-custom">
                                <div class="icon-box bg-info"><i class="fas fa-file-invoice"></i></div>
                                <span>Minha Matrícula</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.pagamentos') }}" class="btn-app-custom">
                                <div class="icon-box bg-warning"><i class="fas fa-wallet"></i></div>
                                <span>Propinas</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ url('estudante/notas_frequencia') }}" class="btn-app-custom">
                                <div class="icon-box bg-primary"><i class="fas fa-chart-bar"></i></div>
                                <span>Notas</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ url('estudante/notas_exame') }}" class="btn-app-custom">
                                <div class="icon-box bg-danger"><i class="fas fa-file-signature"></i></div>
                                <span>Exames</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.perfil.index') }}" class="btn-app-custom">
                                <div class="icon-box bg-secondary"><i class="fas fa-user-cog"></i></div>
                                <span>Perfil</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.notificacoes') }}" class="btn-app-custom">
                                <div class="icon-box bg-purple"><i class="fas fa-bell"></i></div>
                                <span>Notificações</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.relatorios') }}" class="btn-app-custom">
                                <div class="icon-box bg-dark"><i class="fas fa-chart-pie"></i></div>
                                <span>Relatórios</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 col-6 text-center mb-3">
                            <a href="{{ route('estudante.configuracoes') }}" class="btn-app-custom">
                                <div class="icon-box bg-teal"><i class="fas fa-cog"></i></div>
                                <span>Configurações</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de informações da Turma -->
    <div class="modal fade" id="classeModal" tabindex="-1" aria-labelledby="classeModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="classeModalLabel">
                        <i class="fas fa-users-class mr-1"></i>Informações da Turma
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @if($estudante && $estudante->turma)
                        <table class="table table-bordered">
                            <tr><th>Classe</th><td>{{ $estudante->turma->classe->nome ?? 'N/A' }}</td></tr>
                            <tr><th>Turma</th><td>{{ $estudante->turma->nome ?? 'N/A' }}</td></tr>
                            <tr><th>Ano Lectivo</th><td>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</td></tr>
                            <tr><th>Turno</th><td>{{ $estudante->turno ?? 'N/A' }}</td></tr>
                            <tr><th>Nº de Matrícula</th><td>{{ $estudante->matricula }}</td></tr>
                            <tr><th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $estudante->status == 'Ativo' ? 'success' : 'warning' }}">
                                        {{ $estudante->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted mb-0">Sem turma atribuída. Dirija-se à secretaria.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <a href="{{ route('estudante.matriculas') }}" class="btn btn-success">
                        <i class="fas fa-file-invoice mr-1"></i> Ver Guia de Matrícula
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <style>
        .welcome-banner { animation: fadeInDown 0.5s ease-out; }
        .small-box { transition: all 0.3s ease; overflow: hidden; }
        .small-box:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.19); }
        .btn-app-custom { display:block; text-decoration:none; color:#37474f; transition: all 0.3s ease; }
        .btn-app-custom:hover { transform: translateY(-4px); text-decoration: none; color:#37474f; }
        .icon-box { width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 8px; transition: all 0.3s; }
        .icon-box i { font-size:22px; color:white; }
        .btn-app-custom:hover .icon-box { transform: scale(1.1); }
        @keyframes fadeInDown { from { opacity:0; transform: translateY(-20px); } to { opacity:1; transform: translateY(0); } }
        .card, .small-box { border-radius: 0.5rem; }
        .card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .badge { font-weight: 500; padding: 0.35em 0.6em; }
        @media (max-width:768px) {
            .small-box .inner h3 { font-size: 1.5rem; }
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
    <script>
        $(function() {
            $('.knob').knob();

            var calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', locale: 'pt-br',
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listWeek' },
                    height: 400,
                    events: @json($eventosCalendario ?? []),
                    eventClick: function(info) {
                        Swal.fire({ title: info.event.title, html: '<div class="text-left"><p><strong>Data:</strong> ' + moment(info.event.start).format("DD/MM/YYYY") + '</p></div>', icon: 'info' });
                    }
                });
                calendar.render();
                $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                    if (e.target.id === 'tab-calendar-tab') { calendar.updateSize(); }
                });
            }

            $('.card, .small-box').each(function(i) {
                $(this).css({ opacity:0, transform:'translateY(15px)' });
                setTimeout(() => $(this).animate({ opacity:1, transform:'translateY(0)' }, 500), i * 80);
            });

            $('[data-toggle="tooltip"]').tooltip();

            setTimeout(function() {
                toastr.info('Confira suas atividades pendentes.', 'Bem-vindo ao Dashboard!');
            }, 1500);
        });
    </script>
@stop
