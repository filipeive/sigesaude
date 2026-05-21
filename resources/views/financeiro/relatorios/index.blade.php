@extends('adminlte::page')

@section('title', 'Financeiro - Relatórios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-bar mr-2"></i>Relatórios Financeiros</h1>
        <a href="{{ route('financeiro.dashboard') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Resumo por Período</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('financeiro.relatorios.index') }}" class="form-row align-items-end mb-4">
                <div class="col-md-4">
                    <label>Data inicial</label>
                    <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio', $dataInicio->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label>Data final</label>
                    <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim', $dataFim->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Aplicar
                    </button>
                </div>
            </form>

            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-info"><div class="inner"><h3>{{ $resumo['pagamentos'] }}</h3><p>Pagamentos</p></div></div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-primary"><div class="inner"><h3>{{ number_format($resumo['total_emitido'], 2, ',', '.') }}</h3><p>Total Emitido</p></div></div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success"><div class="inner"><h3>{{ number_format($resumo['total_pago'], 2, ',', '.') }}</h3><p>Total Pago</p></div></div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning"><div class="inner"><h3>{{ number_format($resumo['total_pendente'], 2, ',', '.') }}</h3><p>Total Pendente</p></div></div>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porCategoria as $linha)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $linha->tipo)) }}</td>
                            <td>{{ $linha->total }}</td>
                            <td>{{ number_format($linha->valor, 2, ',', '.') }} MZN</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Sem dados no período.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
