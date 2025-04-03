@extends('adminlte::page')

@section('title', 'Pagamentos')

@section('content_header')
<h1>Gestão de Pagamentos</h1>
@stop

@section('content')
<div class="row">
    <!-- Card de Resumo -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Pago</span>
                                <span class="info-box-number">{{ number_format($totalPago, 2, ',', '.') }} MT</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Pendente</span>
                                <span class="info-box-number">{{ number_format($totalPendente, 2, ',', '.') }} MT</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Próximo Vencimento</span>
                                <span class="info-box-number">
                                    @if($proximoVencimento)
                                        {{ Carbon\Carbon::parse($proximoVencimento->data_vencimento)->format('d/m/Y') }}
                                        ({{ number_format($proximoVencimento->valor, 2, ',', '.') }} MT)
                                    @else
                                        Nenhum pagamento pendente
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seletor de Ano Letivo -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('estudante.pagamentos') }}" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="ano_letivo" class="mr-2">Ano Letivo:</label>
                        <select name="ano_letivo" id="ano_letivo" class="form-control" onchange="this.form.submit()">
                            @foreach($anosLetivos as $ano)
                                <option value="{{ $ano->id }}" {{ $anoLetivo && $ano->id == $anoLetivo->id ? 'selected' : '' }}>
                                    {{ $ano->ano }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabs de Navegação -->
    <div class="col-12">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#pagamentos" data-toggle="tab">
                            <i class="fas fa-list"></i> Pagamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#referencias" data-toggle="tab">
                            <i class="fas fa-barcode"></i> Referências Bancárias
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab Pagamentos -->
                    <div class="tab-pane active" id="pagamentos">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabela-pagamentos">
                                <thead>
                                    <tr>
                                        <th>Mês</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pagamentosPorMes as $mes => $pagamentosMes)
                                        @foreach($pagamentosMes as $pagamento)
                                            <tr>
                                                <td>{{ Carbon\Carbon::parse($pagamento->data_vencimento)->format('F/Y') }}</td>
                                                <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MT</td>
                                                <td>{{ Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y') }}</td>
                                                <td>
                                                    @switch($pagamento->status)
                                                        @case('pago')
                                                            <span class="badge badge-success">Pago</span>
                                                            @break
                                                        @case('pendente')
                                                            <span class="badge badge-warning">Pendente</span>
                                                            @break
                                                        @case('em_analise')
                                                            <span class="badge badge-info">Em Análise</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">{{ $pagamento->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($pagamento->status == 'pendente')
                                                        <button class="btn btn-sm btn-primary" onclick="mostrarModalPagamento('{{ $pagamento->referencia }}')">
                                                            <i class="fas fa-upload"></i> Enviar Comprovativo
                                                        </button>
                                                    @endif
                                                    @if($pagamento->comprovante)
                                                        <a href="{{ Storage::url($pagamento->comprovante) }}" class="btn btn-sm btn-info" target="_blank">
                                                            <i class="fas fa-eye"></i> Ver Comprovativo
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Referências -->
                    <div class="tab-pane" id="referencias">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tabela-referencias">
                                <thead>
                                    <tr>
                                        <th>Mês</th>
                                        <th>Referência</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($propinas as $propina)
                                        <tr>
                                            <td>{{ Carbon\Carbon::parse($propina->data_vencimento)->format('F/Y') }}</td>
                                            <td><code>{{ $propina->referencia }}</code></td>
                                            <td>{{ number_format($propina->valor, 2, ',', '.') }} MT</td>
                                            <td>{{ Carbon\Carbon::parse($propina->data_vencimento)->format('d/m/Y') }}</td>
                                            <td>
                                                @switch($propina->status)
                                                    @case('pago')
                                                        <span class="badge badge-success">Pago</span>
                                                        @break
                                                    @case('pendente')
                                                        <span class="badge badge-warning">Pendente</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ $propina->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($propina->status == 'pendente')
                                                    <button class="btn btn-sm btn-primary" onclick="copiarReferencia('{{ $propina->referencia }}')">
                                                        <i class="fas fa-copy"></i> Copiar Referência
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- $propinas->links('pagination::bootstrap-5') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Upload de Comprovativo -->
<div class="modal fade" id="modalComprovativo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('estudante.registrar.pagamento') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Enviar Comprovativo de Pagamento</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="referencia" id="referencia_pagamento">
                    <div class="form-group">
                        <label for="comprovante">Comprovativo (PDF, JPG, PNG)</label>
                        <input type="file" class="form-control-file" id="comprovante" name="comprovante" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#tabela-pagamentos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "order": [[2, "asc"]]
    });

    $('#tabela-referencias').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "order": [[3, "asc"]]
    });
});

function mostrarModalPagamento(referencia) {
    $('#referencia_pagamento').val(referencia);
    $('#modalComprovativo').modal('show');
}

function copiarReferencia(referencia) {
    navigator.clipboard.writeText(referencia).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Referência copiada!',
            text: 'A referência foi copiada para a área de transferência.',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(function() {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Não foi possível copiar a referência.'
        });
    });
}
</script>
@stop