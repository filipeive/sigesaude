@extends('adminlte::page')

@section('title', 'Detalhes da Notificação')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-bell text-primary"></i> Detalhes da Notificação
        </h1>
        <a href="{{ route('docente.notificacoes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-{{ $notificacao->cor }}">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="badge badge-{{ $notificacao->cor }} mr-2">
                            <i class="fas {{ $notificacao->iconeClass }}"></i>
                            {{ ucfirst($notificacao->tipo) }}
                        </span>
                        {{ $notificacao->titulo }}
                    </h3>
                </div>
                <div class="card-body">
                    <p class="card-text" style="font-size: 1.1rem;">{{ $notificacao->mensagem }}</p>

                    <hr>

                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="200"><i class="fas fa-tag mr-1"></i> Tipo</th>
                            <td>
                                <span class="badge badge-{{ $notificacao->cor }}">
                                    {{ ucfirst($notificacao->tipo) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-info-circle mr-1"></i> Status</th>
                            <td>
                                @if($notificacao->lida)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check mr-1"></i> Lida
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock mr-1"></i> Não lida
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user mr-1"></i> Destinatário</th>
                            <td>{{ $notificacao->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar-plus mr-1"></i> Criada em</th>
                            <td>{{ $notificacao->created_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock mr-1"></i> Atualizada em</th>
                            <td>{{ $notificacao->updated_at->format('d/m/Y \à\s H:i') }}</td>
                        </tr>
                        @if($notificacao->agendada_para)
                            <tr>
                                <th><i class="fas fa-calendar-alt mr-1"></i> Agendada para</th>
                                <td>{{ $notificacao->agendada_para->format('d/m/Y \à\s H:i') }}</td>
                            </tr>
                        @endif
                        @if($notificacao->link)
                            <tr>
                                <th><i class="fas fa-link mr-1"></i> Link</th>
                                <td>
                                    <a href="{{ $notificacao->link }}" target="_blank" class="text-primary">
                                        <i class="fas fa-external-link-alt mr-1"></i> Abrir link
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('docente.notificacoes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Voltar para a lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
