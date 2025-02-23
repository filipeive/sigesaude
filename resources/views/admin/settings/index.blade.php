@extends('adminlte::page')
@section('title', 'Configuracoes')
@section('css')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/morris/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
@endsection



@section('content_header')
    <h1>Painel</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/painel') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="breadcrumb-item active">Configuracoes</li>
    </ol>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                <h5>
                    <i class="icon fas fa-ban"></i>
                    Ocorreu um erro...
                </h5>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Notificacao --}}
    @if (session('success'))
        <div class="alert alert-warning">
            <i class="icon fa fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <h1>Gerenciamento de Configurações</h1>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configurações Gerais</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.save') }}" method="post" class="form-horizontal">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Titulo do Site</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ $settings['title'] }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subtitle" class="col-sm-2 col-form-label">Subtitulo do Site</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="subtitle" name="subtitle"
                            value="{{ $settings['subtitle'] }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">E-mail para Contacto</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $settings['email'] }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bgcolor" class="col-sm-2 col-form-label">Cor do Fundo</label>
                    <div class="col-sm-10">
                        <input type="color" class="form-control" id="bgcolor" name="bgcolor"
                            value="{{ $settings['bgcolor'] }}" style="width:70px">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="textcolor" class="col-sm-2 col-form-label">Cor do Texto</label>
                    <div class="col-sm-10">
                        <input type="color" class="form-control" id="textcolor" name="textcolor"
                            value="{{ $settings['textcolor'] }}" style="width:70px">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
