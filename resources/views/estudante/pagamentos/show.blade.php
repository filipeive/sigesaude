@extends('adminlte::page')

@section('title', 'Detalhes do Pagamento')

@section('content_header')
    <h1>Detalhes do Pagamento - {{ $pagamento->referencia }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Seletor de Pagamento -->
            <h3>Informações do Pagamento</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Estudante</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Data de Vencimento</th>
                            <th>Comprovante</th>
                            <th>Data de Pagamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $pagamento->referencia }}</td>
                            <td>{{ $pagamento->estudante->nome }}</td>
                            <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MT</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $pagamento->status == 'pago' ? 'success' : 
                                    ($pagamento->status == 'pendente' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pagamento->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                            <td>
                                @if($pagamento->comprovante)
                                    <a href="{{ asset('storage/'.$pagamento->comprovante) }}" target="_blank">Ver Comprovante</a>
                                @else
                                    Não enviado
                                @endif
                            </td>
                            <td>
                                @if($pagamento->data_pagamento)
                                    {{ \Carbon\Carbon::parse($pagamento->data_pagamento)->format('d/m/Y H:i') }}
                                @else
                                    Não pago
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Legenda de Status -->
            <div class="mt-4">
                <h5>Legenda de Status:</h5>
                <div class="d-flex flex-wrap">
                    <span class="badge badge-success mr-2 mb-2">Pago: Pagamento confirmado</span>
                    <span class="badge badge-warning mr-2 mb-2">Pendente: Pagamento aguardando confirmação</span>
                    <span class="badge badge-danger mr-2 mb-2">Cancelado: Pagamento cancelado</span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .badge {
        font-size: 100%;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Inicializar tooltips se necessário
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
