@extends('adminlte::page')

@section('title', 'Criar Pagamento')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-money-bill-wave text-primary"></i> Criar Pagamento</h1>
        <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <!-- Instruções -->
    <div class="alert alert-info" style="border-left: 4px solid #0056b3; margin-bottom: 20px;">
        <i class="fas fa-info-circle mr-1"></i>
        Preencha os dados abaixo. A <strong>Referência</strong> é gerada automaticamente.
        A <strong>Entidade Bancária</strong> para todos os pagamentos é
        <code style="font-size:1.1em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code>.
    </div>

    <!-- Formulário -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Novo Pagamento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pagamentos.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Estudante -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estudante <span class="text-danger">*</span></label>
                            @if($turmaSelecionada)
                                <select name="estudante_id" id="estudante_id" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    @foreach($turmaSelecionada->estudantes as $est)
                                        <option value="{{ $est->id }}" {{ old('estudante_id') == $est->id ? 'selected' : '' }}>
                                            {{ $est->user->name }} — {{ $est->matricula }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Filtrado por turma <strong>{{ $turmaSelecionada->nome }}</strong></small>
                            @else
                                <select name="estudante_id" id="estudante_id" class="form-control" required>
                                    <option value="">Selecione um estudante</option>
                                    @foreach($estudantes as $est)
                                        <option value="{{ $est->id }}" {{ old('estudante_id') == $est->id ? 'selected' : '' }}>
                                            {{ $est->user->name }} — {{ $est->matricula }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('estudante_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Turma (reservada para quando tipo = matrícula/taxa de turma) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Turma <span class="text-muted">(opcional)</span></label>
                            <select name="turma_id" class="form-control">
                                <option value="">— Sem turma —</option>
                                @foreach($turmas as $t)
                                    <option value="{{ $t->id }}" {{ (old('turma_id') ?? $turmaSelecionada?->id) == $t->id ? 'selected' : '' }}>
                                        {{ $t->classe->nome ?? '' }} {{ $t->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ano Lectivo -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control" required>
                                @foreach($anosLectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ ($anoAtivo && $anoAtivo->id == $ano->id) ? 'selected' : '' }}>
                                        {{ $ano->ano }} @if($ano->status == 'Ativo') <span class="badge badge-success">(Ativo)</span> @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Categoria (Tipo) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Categoria <span class="text-danger">*</span></label>
                            <select name="tipo" id="tipo_pagamento" class="form-control" required>
                                <option value="">Selecione a categoria...</option>
                                <option value="propina" {{ old('tipo') == 'propina' ? 'selected' : '' }}>🠓 Propina Mensal</option>
                                <option value="matricula" {{ old('tipo') == 'matricula' ? 'selected' : '' }}>📄 Matrícula</option>
                                <option value="inscricao" {{ old('tipo') == 'inscricao' ? 'selected' : '' }}>📝 Inscrição</option>
                                <option value="taxa" {{ old('tipo') == 'taxa' ? 'selected' : '' }}>💰 Taxa / Outros</option>
                            </select>
                            <small class="text-muted">Escolha para aplicar regras automáticas.</small>
                            @error('tipo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Valor Sugerido (automático por categoria) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Valor (MZN) <span class="text-danger">*</span></label>
                            <input type="number" name="valor" id="valor_pagamento" class="form-control" step="0.01" min="0"
                                   value="{{ old('valor') ?? ($turmaSelecionada?->valor_matricula ?? '') }}" required>
                            <small class="text-muted" id="valor_hint">
                                @if($turmaSelecionada) Valor da matrícula da turma @endif
                            </small>
                            @error('valor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Data de Vencimento -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Data de Vencimento <span class="text-danger">*</span></label>
                            <input type="date" name="data_vencimento" class="form-control"
                                   value="{{ old('data_vencimento') }}" required>
                            @error('data_vencimento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Método de Pagamento -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Método de Pagamento</label>
                            <select name="metodo_pagamento" class="form-control">
                                <option value="">— Não informado —</option>
                                @foreach($metodosPagamento ?? [] as $k => $v)
                                    <option value="{{ $k }}" {{ old('metodo_pagamento') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('metodo_pagamento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Observação -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea name="observacao" id="observacao" class="form-control" rows="2"
                                      placeholder="Ex: Propina de Janeiro 2026...">{{ old('observacao') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Referência gerada automaticamente -->
                <div class="alert alert-light border">
                    <small><i class="fas fa-barcode mr-1"></i>
                        <strong>Entidade Bancária:</strong>
                        <code style="font-size:1.1em;">{{ \App\Http\Controllers\Admin\PagamentoController::ENTIDADE_BANCARIA }}</code>
                        &nbsp;|&nbsp;
                        <strong>Referência:</strong> Gerada automaticamente após criar o pagamento.
                    </small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save mr-1"></i> Criar Pagamento
                    </button>
                    <a href="{{ route('admin.pagamentos.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
