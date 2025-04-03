@extends('adminlte::page')

@section('title', 'Situação Financeira')

@section('content_header')
    <h1>Situação Financeira</h1>
@stop

@section('content')
    <!-- Notificações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check mr-1"></i> Sucesso:</h5>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban mr-1"></i> Erro:</h5>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Extracto Financeiro</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dívida Actual</span>
                                    <span class="info-box-number">{{ number_format($totalPendente, 2, '.', ',') }} MZN</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo dos Adiantamentos</span>
                                    <span class="info-box-number">0.00 MZN</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <form action="{{ route('estudante.pagamentos') }}" method="GET">
                                <div class="form-group">
                                    <label for="ano_letivo">Ano:</label>
                                    <div class="input-group">
                                        <select name="ano_letivo" id="ano_letivo" class="form-control">
                                            @foreach ($anosLetivos as $ano)
                                                <option value="{{ $ano->id }}" {{ $anoLetivo && $ano->id == $anoLetivo->id ? 'selected' : '' }}>
                                                    {{ $ano->ano }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Mostrar Extracto</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 text-right">
                            <a href="#" class="btn btn-success">Referências Bancárias</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                Para proceder com o pagamento usando referências, dirija-se a qualquer ATM dos Bancos indicados na parte
                inferior desta página, procure pela opção PAGAMENTOS e SELECIONE depois a opção PAGAMENTO DE SERVIÇOS.
                Digite a ENTIDADE, REFERÊNCIA e o VALOR para cada pagamento conforme a sua ficha de pagamentos. Poderá
                também fazer o pagamento através da INTERNET, TELEFONE (E-Banking, Mobile) ou por DEPÓSITO DIRECTO em
                qualquer BALCÃO dos bancos indicados na parte inferior desta página, usando entidade, referência e o valor
                exacto constante na sua ficha.
                <br><strong>NB: PARA O PAGAMENTO DE PROPINAS, USE SOMENTE AS REDES INDICADAS NA PARTE INFERIOR DESTA
                    PÁGINA!</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Propinas Pendentes</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar...">
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
                                <th>Propina</th>
                                <th>Data Limite</th>
                                <th>Entidade</th>
                                <th>Referência</th>
                                <th>Montante</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($propinas as $propina)
                                <tr>
                                    <td>{{ $propina->descricao }}</td>
                                    <td>{{ date('d-m-Y', strtotime($propina->data_vencimento)) }}</td>
                                    <td>11151</td>
                                    <td>{{ $propina->referencia }}</td>
                                    <td>{{ number_format($propina->valor, 2, '.', ',') }} MZN</td>
                                    <td>
                                        @if ($propina->status == 'pendente')
                                            <span class="badge bg-danger">NÃO PAGO</span>
                                        @else
                                            <span class="badge bg-success">PAGO</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm">Ver Detalhes</a>
                                        <a href="#" class="btn btn-success btn-sm">Emitir Recibo</a>
                                        <a href="#" class="btn btn-primary btn-sm">Efetuar Pagamento</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhuma propina pendente para este período.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
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
                            <span class="info-box-number">{{ number_format($totalPago, 2, '.', ',') }} MZN</span>
                        </div>
                    </div>

                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Último Pagamento</span>
                            <span class="info-box-number">
                                @if ($pagamentos->where('status', 'pago')->count() > 0)
                                    {{ date('d/m/Y', strtotime($pagamentos->where('status', 'pago')->sortByDesc('data_pagamento')->first()->data_pagamento)) }}
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
                            <span class="info-box-number">
                                @if ($proximoVencimento)
                                    {{ date('d/m/Y', strtotime($proximoVencimento->data_vencimento)) }}
                                @else
                                    Nenhum vencimento próximo
                                @endif
                            </span>
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
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> Informação!</h5>
                                Para realizar pagamentos, utilize um dos métodos abaixo ou dirija-se ao setor financeiro da
                                instituição.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5>Redes disponíveis para pagamentos:</h5>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="#" alt="BCI" class="img-fluid"
                                style="max-height: 60px;">
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="#"
                                alt="Ponto24" class="img-fluid" style="max-height: 60px;">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Enviar comprovante de pagamento:</h5>
                            <form action="{{ route('estudante.registrar.pagamento') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="referencia">Referência do Pagamento</label>
                                    <input type="text" class="form-control" id="referencia" name="referencia"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="comprovante">Upload do Comprovante</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="comprovante"
                                                name="comprovante" required>
                                            <label class="custom-file-label" for="comprovante">Escolher arquivo</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Comprovante</button>
                            </form>
                        </div>
                    </div>
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
            $('#ano_letivo').change(function() {
                $(this).closest('form').submit();
            });

            // Atualizar o nome do arquivo no upload
            $(document).on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@stop