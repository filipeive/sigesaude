@extends('adminlte::page')

@section('title', 'Pagamentos')

@section('content_header')
    <h1>Meus Pagamentos</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Pagamentos</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right"
                                placeholder="Pesquisar">
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
                                <th>ID</th>
                                <th>Data de Pagamento</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagamentos as $pagamento)
                                <tr>
                                    <td>{{ $pagamento->id }}</td>
                                    <td>{{ date('d/m/Y', strtotime($pagamento->data_pagamento)) }}</td>
                                    <td>Pagamento de Mensalidade</td>
                                    <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                                    <td><span class="badge bg-success">Confirmado</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum pagamento registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Resumo Financeiro</h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pago</span>
                            <span class="info-box-number">{{ number_format($totalPago, 2, ',', '.') }} MZN</span>
                        </div>
                    </div>

                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Último Pagamento</span>
                            <span class="info-box-number">
                                @if ($pagamentos->count() > 0)
                                    {{ date('d/m/Y', strtotime($pagamentos->first()->data_pagamento)) }}
                                @else
                                    Nenhum pagamento
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Próximo Vencimento</span>
                            <span class="info-box-number">05/03/2023</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Métodos de Pagamento</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Informação!</h5>
                                Para realizar pagamentos, utilize um dos métodos abaixo ou dirija-se ao setor financeiro da
                                instituição.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Transferência Bancária</h5>
                                    <p class="card-text">
                                        <strong>Banco:</strong> Banco Nacional<br>
                                        <strong>Conta:</strong> 12345-6<br>
                                        <strong>Titular:</strong> Instituto de Saúde<br>
                                        <strong>NIB:</strong> 0000 0000 0000 0000 0000 0
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">M-Pesa</h5>
                                    <p class="card-text">
                                        <strong>Número:</strong> 84 123 4567<br>
                                        <strong>Titular:</strong> Instituto de Saúde<br>
                                        <strong>Código:</strong> 12345
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Instruções para Pagamento</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                        Certifique-se de incluir o seu número de matrícula como referência no pagamento para que possamos
                        identificar a sua transação.
                    </div>
                    <p>Para qualquer dúvida ou problema com o pagamento, entre em contato com o setor financeiro:</p>
                    <ul>
                        <li><strong>Email:</strong> financeiro@instituto.com</li>
                        <li><strong>Telefone:</strong> +258 84 123 4567</li>
                        <li><strong>Horário de Atendimento:</strong> Segunda a Sexta, das 8h às 17h</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }}">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
