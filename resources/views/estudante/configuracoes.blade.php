{{-- resources/views/estudante/configuracoes.blade.php --}}
@extends('adminlte::page')

@section('title', 'Configurações')

@section('content_header')
    <h1><i class="fas fa-cog mr-2"></i>Configurações</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('estudante.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
        <li class="breadcrumb-item active">Configurações</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Informações de Conta -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-lock mr-2"></i>Segurança da Conta</h3>
                </div>
                <div class="card-body">
                    <p>Altere a sua senha e informações de acesso.</p>
                    <a href="{{ route('estudante.perfil.index') }}" class="btn btn-primary">
                        <i class="fas fa-user-edit mr-1"></i> Editar Perfil e Senha
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Preferências -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bell mr-2"></i>Notificações</h3>
                </div>
                <div class="card-body">
                    <p>As notificações são enviadas automaticamente para alertá-lo sobre:</p>
                    <ul>
                        <li>Novas notas e resultados de exame</li>
                        <li>Lembretes de pagamento de propinas</li>
                        <li>Confirmação de inscrições e matrículas</li>
                        <li>Comunicados da escola</li>
                    </ul>
                    <a href="{{ route('estudante.notificacoes') }}" class="btn btn-info">
                        <i class="fas fa-eye mr-1"></i> Ver Notificações
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Académicas (read-only) -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-graduate mr-2"></i>Minhas Informações Académicas</h3>
                </div>
                <div class="card-body">
                    @php
                        $estudante = auth()->user()->estudante;
                    @endphp
                    @if($estudante && $estudante->turma)
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th width="25%">Estudante</th>
                                <td>{{ $estudante->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Número de Matrícula</th>
                                <td>{{ $estudante->matricula }}</td>
                            </tr>
                            <tr>
                                <th>Turma</th>
                                <td>{{ $estudante->turma->nome }}</td>
                            </tr>
                            <tr>
                                <th>Classe</th>
                                <td>{{ $estudante->turma->classe->nome ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Ano Lectivo</th>
                                <td>{{ $estudante->anoLectivo->ano ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Turno</th>
                                <td>{{ $estudante->turno }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $estudante->status == 'Ativo' ? 'success' : ($estudante->status == 'Inativo' ? 'danger' : 'warning') }}">
                                        {{ $estudante->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $estudante->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefone</th>
                                <td>{{ $estudante->user->telefone }}</td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            O seu perfil ainda não está completo. Complete o seu cadastro para poder acessar todas as funcionalidades.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
