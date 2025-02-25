@extends('adminlte::page')

@section('title', 'Matrículas')

@section('content_header')
    <h1>Minhas Matrículas</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Disciplinas Matriculadas</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Pesquisar">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Disciplina</th>
                            <th>Curso</th>
                            <th>Professor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriculas as $matricula)
                            <tr>
                                <td>{{ $matricula->disciplina->id }}</td>
                                <td>{{ $matricula->disciplina->nome }}</td>
                                <td>{{ $estudante->curso->nome }}</td>
                                <td>{{ $matricula->disciplina->docente->user->name ?? 'Não Atribuído' }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nenhuma disciplina matriculada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informações do Curso</h3>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Curso:</dt>
                    <dd class="col-sm-8">{{ $estudante->curso->nome }}</dd>
                    
                    <dt class="col-sm-4">Matrícula:</dt>
                    <dd class="col-sm-8">{{ $estudante->matricula }}</dd>
                    
                    <dt class="col-sm-4">Ano de Ingresso:</dt>
                    <dd class="col-sm-8">{{ $estudante->ano_ingresso }}</dd>
                    
                    <dt class="col-sm-4">Turno:</dt>
                    <dd class="col-sm-8">{{ $estudante->turno }}</dd>
                    
                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        <span class="badge 
                            @if($estudante->status == 'Ativo') 
                                bg-success 
                            @elseif($estudante->status == 'Inativo') 
                                bg-danger 
                            @elseif($estudante->status == 'Concluído') 
                                bg-primary 
                            @else 
                                bg-warning 
                            @endif
                        ">
                            {{ $estudante->status }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Calendário Acadêmico</h3>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
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
        });
    </script>
@stop