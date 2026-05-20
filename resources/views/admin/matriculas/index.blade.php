@extends('adminlte::page')

@section('title', 'Matrículas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-graduation-cap mr-2"></i>Matrículas</h1>
        <a href="{{ route('admin.matriculas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Criar Matrícula
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $matriculas->total() }}</h3>
                            <p>Total de Matrículas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $matriculas->pluck('estudante_id')->unique()->count() }}</h3>
                            <p>Estudantes Matriculados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $matriculas->pluck('turma_id')->unique()->count() }}</h3>
                            <p>Turmas com Matrículas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users-class"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucoes de Pagamento para Matricula -->
            <div class="alert alert-info" style="margin-bottom: 20px;">
                <h5><i class="fas fa-info-circle mr-2"></i> Instrucoes de Pagamento da Matricula</h5>
                <p class="mb-1">O valor da matrícula anual é cobrado uma unica vez e gera uma <strong>referencia ATM propria</strong> (prefixo <strong>922</strong>).</p>
                <p class="mb-0">
                    Para efectuar o pagamento, o estudante ou encarregado deve dirigir-se a qualquer ATM dos
                    <strong>Bancos Parceiros</strong> (BCI, Ponto24), escolher 
                    <strong>Pagamentos &gt; Pagamento de Servicos</strong>, inserir a 
                    <strong>Entidade: 11151</strong> e a <strong>Referencia</strong> da matrícula.
                    Apos o pagamento, envie o <strong>comprovativo</strong> pela plataforma para confirmacao.
                </p>
            </div>

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Lista de Matrículas</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.matriculas.index') }}" method="GET">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="Pesquisar matrículas..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($matriculas->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="mb-0">Nenhuma matrícula registrada.</p>
                            <a href="{{ route('admin.matriculas.create') }}" class="btn btn-outline-primary mt-3">
                                <i class="fas fa-plus mr-1"></i> Criar Nova Matrícula
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th><i class="fas fa-user mr-1"></i> Estudante</th>
                                        <th><i class="fas fa-users mr-1"></i> Turma</th>
                                        <th><i class="fas fa-calendar-alt mr-1"></i> Ano Letivo</th>
                                        <th><i class="fas fa-money-bill mr-1"></i> Valor</th>
                                        <th><i class="fas fa-clipboard-check mr-1"></i> Status</th>
                                        <th class="text-center"><i class="fas fa-cogs mr-1"></i> Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matriculas as $matricula)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2">
                                                        <img class="profile-user-img img-fluid img-circle" 
                                                            src="{{ $matricula->estudante?->user?->foto_perfil ? Storage::url($matricula->estudante->user->foto_perfil) : asset('vendor/adminlte/dist/img/user.jpg') }}" 
                                                            alt="Foto" style="width: 40px; height: 40px;">
                                                    </div>
                                                    <div>
                                                        <strong>{{ $matricula->estudante?->user?->name ?? 'N/A' }}</strong>
                                                        <p class="text-muted mb-0">{{ $matricula->estudante?->matricula ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $matricula->turma?->nome ?? 'N/A' }}</strong>
                                                <p class="text-muted mb-0">{{ $matricula->turma?->ano_serie ?? 'N/A' }}º Ano</p>
                                            </td>
                                            <td>{{ $matricula->anoLectivo->ano ?? 'N/A' }}</td>
                                            <td>{{ $matricula->valor ? number_format($matricula->valor, 2, ',', '.') . ' MZN' : 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-success">{{ $matricula->status ?? 'Ativa' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.matriculas.show', $matricula->id) }}" class="btn btn-info btn-sm" title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.matriculas.pdf', $matricula->id) }}" class="btn btn-primary btn-sm" title="Baixar Guia PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                    <a href="{{ route('admin.matriculas.edit', $matricula->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.matriculas.destroy', $matricula->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Excluir matrícula">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $matriculas->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            position: relative;
            display: block;
            margin-bottom: 20px;
            color: #fff;
        }
        
        .small-box .inner {
            padding: 15px;
        }
        
        .small-box .icon {
            color: rgba(0, 0, 0, 0.15);
            z-index: 0;
            font-size: 70px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: transform .3s linear;
        }
        
        .small-box:hover .icon {
            transform: scale(1.1);
        }
        
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        
        .table thead th {
            border-bottom-width: 1px;
            font-weight: 600;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Confirmar Exclusão',
                    text: "Esta ação não poderá ser revertida. Deseja continuar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop