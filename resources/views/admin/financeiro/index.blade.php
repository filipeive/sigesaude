@extends('adminlte::page')

@section('title', 'Gestão Financeira')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-money-bill-wave text-primary mr-2"></i> Gestão Financeira</h1>
        <div>
            <a href="{{ route('admin.financeiro.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Nova Transação
            </a>
            <a href="{{ route('admin.financeiro.relatorios') }}" class="btn btn-info ml-2">
                <i class="fas fa-chart-line mr-1"></i> Relatórios
            </a>
            <a href="{{ route('admin.financeiro.configuracoes') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-cog mr-1"></i> Configurações
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Card Resumo -->
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Resumo Financeiro</h3>
                    <div class="card-tools">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio', date('Y-m-d', strtotime('-30 days'))) }}">
                                <input type="date" name="data_fim" class="form-control ml-2" value="{{ request('data_fim', date('Y-m-d')) }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Entradas</span>
                                    <span class="info-box-number">{{ number_format($entradas ?? 0, 2, ',', '.') }} MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saídas</span>
                                    <span class="info-box-number">{{ number_format($saidas ?? 0, 2, ',', '.') }} MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo</span>
                                    <span class="info-box-number">{{ number_format(($entradas ?? 0) - ($saidas ?? 0), 2, ',', '.') }} MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Transações</span>
                                    <span class="info-box-number">{{ $total_transacoes ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Transações -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transações Recentes</h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control float-right" placeholder="Buscar transação..." id="searchInput">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap" id="transacoesTable">
                <thead>
                    <tr>
                        <th>
                            <a href="#" class="sort-link" data-sort="descricao">
                                Descrição <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th>
                            <a href="#" class="sort-link" data-sort="valor">
                                Valor <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th>
                            <a href="#" class="sort-link" data-sort="data">
                                Data <i class="fas fa-sort ml-1"></i>
                            </a>
                        </th>
                        <th>Tipo</th>
                        <th>Categoria</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transacoes as $transacao)
                        <tr class="{{ $transacao->tipo == 'entrada' ? 'table-success' : 'table-danger' }}">
                            <td>{{ $transacao->descricao }}</td>
                            <td>{{ number_format($transacao->valor, 2, ',', '.') }} MZN</td>
                            <td>{{ date('d-m-Y', strtotime($transacao->data)) }}</td>
                            <td>
                                <span class="badge {{ $transacao->tipo == 'entrada' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($transacao->tipo) }}
                                </span>
                            </td>
                            <td>{{ $transacao->categoria ?? 'Não categorizado' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.financeiro.show', $transacao->id) }}" 
                                       class="btn btn-info btn-sm" 
                                       data-toggle="tooltip" 
                                       title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.financeiro.edit', $transacao->id) }}" 
                                       class="btn btn-warning btn-sm" 
                                       data-toggle="tooltip" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-btn" 
                                            data-toggle="modal" 
                                            data-target="#deleteModal" 
                                            data-id="{{ $transacao->id }}"
                                            data-toggle="tooltip" 
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhuma transação encontrada</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $transacoes->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .sort-link {
            color: #212529;
            text-decoration: none;
        }
        .sort-link:hover {
            text-decoration: none;
            color: #007bff;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.6rem;
        }
    </style>
@stop

@section('js')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            
            // Configuração do modal de exclusão
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = "{{ route('admin.financeiro.destroy', ':id') }}";
                url = url.replace(':id', id);
                $('#deleteForm').attr('action', url);
            });
            
            // Pesquisa nas transações
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#transacoesTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@stop