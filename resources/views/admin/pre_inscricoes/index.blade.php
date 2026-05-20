@extends('adminlte::page')

@section('title', 'Gestão de Pré-Inscrições')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-plus mr-2"></i>Pré-Inscrições Recebidas</h1>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Lista de Candidatos</h3>
            <div class="card-tools">
                <form action="{{ route('admin.pre-inscricoes.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Nome ou código..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome do Candidato</th>
                            <th>Telefone / Email</th>
                            <th>Classe Desejada</th>
                            <th>Data Submissão</th>
                            <th>Data Limite</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preInscricoes as $pre)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $pre->codigo_pre_inscricao }}</span></td>
                                <td><strong>{{ $pre->nome_completo }}</strong></td>
                                <td>
                                    {{ $pre->telefone }} <br>
                                    <small class="text-muted">{{ $pre->email ?? 'Sem email' }}</small>
                                </td>
                                <td>{{ $pre->classe->nome }} ({{ $pre->anoLectivo->ano }})</td>
                                <td>{{ $pre->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="{{ $pre->data_limite->isPast() ? 'text-danger font-weight-bold' : '' }}">
                                        {{ $pre->data_limite->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Pendente' => 'badge-warning',
                                            'Confirmada' => 'badge-success',
                                            'Expirada' => 'badge-danger',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClass[$pre->status] ?? 'badge-secondary' }}">
                                        {{ $pre->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('pre-inscricao.pdf', $pre->codigo_pre_inscricao) }}" class="btn btn-info btn-sm" title="Baixar PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        {{-- Aqui poderíamos adicionar um botão para converter em Matrícula Real --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Nenhuma pré-inscrição encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $preInscricoes->links() }}
        </div>
    </div>
@stop
