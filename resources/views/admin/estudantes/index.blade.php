{{-- resources/views/admin/estudantes/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Gestão de Estudantes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark"><i class="fas fa-users"></i> Gestão de Estudantes</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Estudantes</li>
            </ol>
        </div>
        <a href="{{ route('admin.estudantes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Novo Estudante
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <h3 class="card-title" style="padding-left:15px; padding-top:15px ">
                    <i class="fas fa-users mr-2"></i> Lista de Estudantes
                </h3>
                <div class="card-body">
                    {{-- Filtros --}}
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.estudantes.index') }}" method="GET" class="form-inline">
                                <div class="input-group mb-2 mr-sm-2">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Buscar por nome ou matrícula...">
                                </div>
                                <div class="input-group mb-2 mr-sm-2">
                                    <select name="curso" class="form-control">
                                        <option value="">Todos os Cursos</option>
                                        @foreach ($cursos as $id => $nome)
                                            <option value="{{ $id }}">{{ $nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group mb-2 mr-sm-2">
                                    <select name="status" class="form-control">
                                        <option value="">Todos os Status</option>
                                        <option value="Ativo">Ativo</option>
                                        <option value="Inativo">Inativo</option>
                                        <option value="Concluído">Concluído</option>
                                        <option value="Desistente">Desistente</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default mb-2">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tabela --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Estudante</th>
                                    <th>Matrícula</th>
                                    <th>Curso</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estudantes as $estudante)
                                    <tr>
                                        <td>
                                            <div class="user-block">
                                                @if ($estudante->user->foto_perfil)
                                                    <img class="img-circle img-bordered-sm"
                                                        src="{{ Storage::url($estudante->user->foto_perfil) }}"
                                                        alt="Foto">
                                                @else
                                                    <img class="img-circle img-bordered-sm"
                                                        src="{{ asset('vendor/adminlte/dist/img/user.jpg') }}"
                                                        alt="Foto">
                                                @endif
                                                <span class="username">
                                                    <a href="#">{{ $estudante->user->name }}</a>
                                                </span>
                                                <span class="description">{{ $estudante->user->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $estudante->matricula }}</td>
                                        <td>{{ $estudante->curso->nome }}</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'Ativo' => 'badge badge-success',
                                                    'Inativo' => 'badge badge-danger',
                                                    'Concluído' => 'badge badge-info',
                                                    'Desistente' => 'badge badge-warning',
                                                ];
                                            @endphp
                                            <span
                                                class="{{ $statusClasses[$estudante->status] ?? 'badge badge-secondary' }}">
                                                {{ $estudante->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.estudantes.show', $estudante->id) }}"
                                                    class="btn btn-info btn-sm" title="Ver Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.estudantes.edit', $estudante->id) }}"
                                                    class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $estudante->id }})" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum estudante encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    <div class="mt-3">
                        {{ $estudantes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Confirmação de Exclusão --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir este estudante?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function confirmDelete(id) {
            $('#deleteForm').attr('action', `/admin/estudantes/${id}`);
            $('#deleteModal').modal('show');
        }

        $(document).ready(function() {
            // Inicializa os selects com Select2
            $('.select2').select2();

            // Toast para mensagens de sucesso/erro
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            // Mostra mensagem de sucesso se existir
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Mostra mensagem de erro se existir
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif
        });
    </script>
@stop
