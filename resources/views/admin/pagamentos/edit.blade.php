@extends('adminlte::page')

@section('title', 'Editar Pagamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-edit mr-2 text-primary"></i> Editar Pagamento — <code>{{ $pagamento->referencia }}</code></h1>
        <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Voltar</a>
    </div>
@stop

@section('content')
    <!-- Card Informação do Pagamento -->
    <div class="card card-outline card-info mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <small class="text-muted">Estudante</small>
                    <h6>{{ $pagamento->estudante->user->name ?? 'N/A' }}</h6>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Turma</small>
                    <h6>{{ $pagamento->turma?->nome ?? $pagamento->estudante?->turma?->nome ?? '—' }}</h6>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Referência</small>
                    <h6><code>{{ $pagamento->referencia }}</code></h6>
                </div>
                <div class="col-md-2">
                    <small class="text-muted">Valor</small>
                    <h6>{{ number_format($pagamento->valor, 2, ',', '.') }} MZN</h6>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Entidade Bancária</small>
                    <h6><code style="font-size:1.1em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code></h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Editar Dados do Pagamento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pagamentos.update', $pagamento) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estudante <span class="text-danger">*</span></label>
                            <select name="estudante_id" class="form-control" required>
                                @foreach ($estudantes as $est)
                                    <option value="{{ $est->id }}" {{ $pagamento->estudante_id == $est->id ? 'selected' : '' }}>
                                        {{ $est->user->name }} — {{ $est->matricula }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estudante_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Turma</label>
                            <select name="turma_id" class="form-control">
                                <option value="">— Sem turma —</option>
                                @foreach($turmas as $t)
                                    <option value="{{ $t->id }}" {{ $pagamento->turma_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->classe->nome ?? '' }} {{ $t->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control" required>
                                @foreach ($anosLectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ $pagamento->ano_lectivo_id == $ano->id ? 'selected' : '' }}>
                                        {{ $ano->ano }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Categoria <span class="text-danger">*</span></label>
                            <select name="tipo" class="form-control" required>
                                @php $tipos = ['propina'=>'Propina Mensal','matricula'=>'Matrícula','taxa'=>'Taxa','inscricao'=>'Inscrição']; @endphp
                                <option value="">Selecione...</option>
                                @foreach($tipos as $k => $v)
                                    <option value="{{ $k }}" {{ $pagamento->tipo == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Valor (MZN) <span class="text-danger">*</span></label>
                            <input type="number" name="valor" class="form-control" step="0.01" min="0"
                                   value="{{ old('valor', $pagamento->valor) }}" required>
                            @error('valor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data de Vencimento <span class="text-danger">*</span></label>
                            <input type="date" name="data_vencimento" class="form-control"
                                   value="{{ old('data_vencimento', $pagamento->data_vencimento) }}" required>
                            @error('data_vencimento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="pendente" {{ $pagamento->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ $pagamento->status == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ $pagamento->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Observação</label>
                    <textarea name="observacao" class="form-control" rows="2">{{ old('observacao', $pagamento->observacao) }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Salvar Alterações
                    </button>
                    <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
