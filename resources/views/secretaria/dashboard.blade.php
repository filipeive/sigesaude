@extends('adminlte::page')

@section('title', 'Dashboard Secretaria')

@section('content_header')
    <h1>Dashboard Secretaria</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Matrículas</h3>
                    <p>Novas Matrículas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ url('secretaria/matriculas/create') }}" class="small-box-footer">
                    Ir para Matrículas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Pagamentos</h3>
                    <p>Confirmar Mensalidades</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ url('secretaria/pagamentos') }}" class="small-box-footer">
                    Ver Pagamentos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>Estudantes</h3>
                    <p>Lista de Alunos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ url('secretaria/estudantes') }}" class="small-box-footer">
                    Ver Estudantes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Relatórios</h3>
                    <p>Documentos e Listas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <a href="{{ url('secretaria/relatorios') }}" class="small-box-footer">
                    Gerar Relatórios <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Avisos da Secretaria</h3>
                </div>
                <div class="card-body">
                    <p>Bem-vindo ao painel da secretaria. Utilize os menus laterais para gerir estudantes, matrículas e pagamentos.</p>
                    <ul>
                        <li>Verifique as novas inscrições pendentes diariamente.</li>
                        <li>Confirme os comprovantes de pagamento para libertar o acesso às notas.</li>
                        <li>Actualize os dados dos estudantes sempre que necessário.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop