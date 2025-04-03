@extends('adminlte::page')

@section('title', 'Dashboard do Estudante')

@section('content_header')
    <h1>Bem-vindo, {{ Auth::user()->name }}</h1>
@stop

@section('content')
    <div class="row">
        {{-- alerts de sucesso ou erro --}}
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Olá, {{ Auth::user()->name }}!</strong> Bem-vindo ao seu painel de estudante.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalDisciplinas }}</h3>
                    <p>Disciplinas Matriculadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('estudante.matriculas') }}" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estudante->curso->nome ?? 'N/A' }}</h3>
                    <p>Meu Curso</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</h3>
                    <p>Ano Letivo Atual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estudante->turno }}</h3>
                    <p>Turno</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimos Pagamentos</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosPagamentos as $pagamento)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($pagamento->data_pagamento)) }}</td>
                                    <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                    <td>{{ $pagamento->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Nenhum pagamento registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('estudante.pagamentos') }}" class="btn btn-sm btn-info float-right">Ver Todos</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Calendário Acadêmico</h3>
                </div>
                <div class="card-body" style="height: 350px; overflow-y: auto;">
                    <div id="calendar" style="height: 250px;"></div>
                </div>
            </div>
        </div>        

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notificações Recentes</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Mensagem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasNotificacoes as $notificacao)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($notificacao->created_at)) }}</td>
                                    <td>{{ $notificacao->mensagem }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Nenhuma notificação.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('estudante.notificacoes') }}" class="btn btn-sm btn-info float-right">Ver Todas</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Atalhos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('estudante.inscricoes.index') }}" class="btn btn-app">
                                <i class="fas fa-graduation-cap"></i> Minhas Inscricoes
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('estudante.pagamentos') }}" class="btn btn-app">
                                <i class="fas fa-dollar-sign"></i> Pagamentos
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ url('estudante/notas_frequencia') }}" class="btn btn-app">
                                <i class="fas fa-book-open"></i> Notas
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('estudante.perfil.index') }}" class="btn btn-app">
                                <i class="fas fa-user"></i> Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    // Adicione eventos do calendário acadêmico aqui
                ]
            });
            calendar.render();
            console.log('Dashboard do Estudante!');
        });
    </script>
@stop
