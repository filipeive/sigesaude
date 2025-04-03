@extends('adminlte::page')

@section('title', 'Pagamentos')

@section('content_header')
    <h1>Pagamentos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Referência</th>
                        <th>Estudante</th>
                        <th>Valor</th>
                        <th>Data de Vencimento</th>
                        <th>Status</th>
                        <th style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagamentos as $pagamento)
                        <tr>
                            <td>{{ $pagamento->referencia }}</td>
                            <td>{{ $pagamento->estudante->user->name }}</td>
                            <td>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</td>
                            <td>{{ \Carbon\Carbon::parse($pagamento->data_vencimento)->format('d/m/Y')}}</td>
                            <td>
                                <span class="badge {{ $pagamento->status == 'pago' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($pagamento->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pagamentos.show', $pagamento->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <form action="{{ route('admin.pagamentos.updateStatus', $pagamento->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $pagamento->status == 'pago' ? 'pendente' : 'pago' }}">
                                    <button type="submit" class="btn btn-sm {{ $pagamento->status == 'pago' ? 'btn-warning' : 'btn-success' }}">
                                        {{ $pagamento->status == 'pago' ? 'Marcar como Pendente' : 'Marcar como Pago' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.pagamentos.destroy', $pagamento->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum pagamento registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop