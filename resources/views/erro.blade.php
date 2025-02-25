@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Erro</div>
                    <div class="card-body">
                        <h2>Estudante não encontrado</h2>
                        <p>{{ session('mensagem') }}</p>
                        <a href="{{ route('login') }}">Voltar para a página de login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection