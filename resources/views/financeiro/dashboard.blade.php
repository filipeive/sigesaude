@extends('adminlte::page')

@section('title', 'Dashboard Financeiro')

@section('content_header')
    <h1>Dashboard Financeiro</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Pagamentos</h3>
                    <p>Validar Mensalidades</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ url('financeiro/pagamentos') }}" class="small-box-footer">
                    Ver Pagamentos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Relatórios</h3>
                    <p>Fluxo de Caixa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="{{ url('financeiro/relatorios') }}" class="small-box-footer">
                    Gerar Relatórios <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Perfil</h3>
                    <p>Meu Cadastro</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <a href="{{ url('financeiro/perfil') }}" class="small-box-footer">
                    Ver Perfil <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <h3 class="card-title">Resumo Financeiro</h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h5>Atenção</h5>
                        <p>O fecho de caixa mensal deve ser realizado até ao 5º dia útil de cada mês.</p>
                    </div>
                    <p>Bem-vindo ao painel financeiro. Aqui poderá gerir todas as transações, validar comprovativos de pagamento e gerar relatórios de receitas por curso.</p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Financeiro Dashboard Loaded'); </script>
@stop
