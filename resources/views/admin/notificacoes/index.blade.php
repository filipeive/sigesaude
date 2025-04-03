{{-- resources/views/admin/notificacoes/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Gerenciar Notificações')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Gerenciar Notificações</h1>
    </div>
    <div class="col-sm-6">
        <a href="{{ route('admin.notificacoes.create') }}" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Nova Notificação
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Notificações</h3>
        <div class="card-tools">
            @if($notificacoes->count() > 0)
                <button type="button" class="btn btn-danger btn-sm delete-selected" disabled>
                    <i class="fas fa-trash"></i> Excluir Selecionados
                </button>
            @endif
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th width="40">
                        <div class="icheck-primary">
                            <input type="checkbox" id="select-all">
                            <label for="select-all"></label>
                        </div>
                    </th>
                    <th>Título</th>
                    <th>Destinatário</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th width="100">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notificacoes as $notificacao)
                    <tr>
                        <td>
                            <div class="icheck-primary">
                                <input type="checkbox" class="notification-check" value="{{ $notificacao->id }}" id="check{{ $notificacao->id }}">
                                <label for="check{{ $notificacao->id }}"></label>
                            </div>
                        </td>
                        <td>{{ $notificacao->titulo }}</td>
                        <td>{{ $notificacao->user->name }}</td>
                        <td>
                            <span class="badge badge-{{ $notificacao->tipo === 'academico' ? 'primary' : 
                                                    ($notificacao->tipo === 'financeiro' ? 'success' : 
                                                    ($notificacao->tipo === 'administrativo' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($notificacao->tipo) }}
                            </span>
                        </td>
                        <td>
                            @if($notificacao->lida)
                                <span class="badge badge-success">Lida</span>
                            @else
                                <span class="badge badge-warning">Não lida</span>
                            @endif
                        </td>
                        <td>{{ $notificacao->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete-notification" data-id="{{ $notificacao->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhuma notificação encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notificacoes->hasPages())
        <div class="card-footer clearfix">
            {{ $notificacoes->links() }}
        </div>
    @endif
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
@stop

@section('js')
<script>
$(function () {
    // Select all checkboxes
    $('#select-all').on('change', function() {
        $('.notification-check').prop('checked', $(this).is(':checked'));
        updateDeleteSelectedButton();
    });

    // Update delete selected button state
    $('.notification-check').on('change', function() {
        updateDeleteSelectedButton();
    });

    function updateDeleteSelectedButton() {
        const selectedCount = $('.notification-check:checked').length;
        $('.delete-selected').prop('disabled', selectedCount === 0);
    }

    // Delete single notification
    $('.delete-notification').on('click', function() {
        const id = $(this).data('id');
        if (confirm('Tem certeza que deseja excluir esta notificação?')) {
            $.ajax({
                url: `{{ url('admin/notificacoes') }}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function() {
                    toastr.error('Erro ao excluir notificação');
                }
            });
        }
    });

    // Delete selected notifications
    $('.delete-selected').on('click', function() {
        const selectedIds = $('.notification-check:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length && confirm('Tem certeza que deseja excluir as notificações selecionadas?')) {
            $.ajax({
                url: '{{ route("admin.notificacoes.destroy-multiple") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { ids: selectedIds },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function() {
                    toastr.error('Erro ao excluir notificações');
                }
            });
        }
    });
});
</script>
@stop