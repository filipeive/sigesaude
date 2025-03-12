@extends('adminlte::page')

@section('title', 'Editar Pagamento')

@section('content_header')
    <h1><i class="fas fa-money-bill-wave text-primary"></i> Editar Pagamento</h1>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="{{ route('admin.pagamentos.update', $pagamento) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Estudante</label>
                            <select name="estudante_id" class="form-control" required>
                                @foreach ($estudantes as $estudante)
                                    <option value="{{ $estudante->id }}" {{ $pagamento->estudante_id == $estudante->id ? 'selected' : '' }}>
                                        {{ $estudante->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Valor</label>
                            <input type="number" name="valor" class="form-control" step="0.01" min="0" value="{{ $pagamento->valor }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Data de Vencimento</label>
                            <input type="date" name="data_vencimento" class="form-control" value="{{ $pagamento->data_vencimento }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="pendente" {{ $pagamento->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ $pagamento->status == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ $pagamento->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Observação</label>
                    <textarea name="observacao" class="form-control" rows="2">{{ $pagamento->observacao }}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection