@extends('adminlte::page')

@section('title', 'Nova Transação')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-plus-circle text-primary mr-2"></i> Nova Transação</h1>
        <a href="{{ route('admin.financeiro.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Informações da Transação</h3>
                </div>
                <form action="{{ route('admin.financeiro.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo de Transação <span class="text-danger">*</span></label>
                                    <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                        <label class="btn btn-outline-success active">
                                            <input type="radio" name="tipo" id="tipo_entrada" value="entrada" checked> 
                                            <i class="fas fa-arrow-down mr-1"></i> Entrada
                                        </label>
                                        <label class="btn btn-outline-danger">
                                            <input type="radio" name="tipo" id="tipo_saida" value="saida"> 
                                            <i class="fas fa-arrow-up mr-1"></i> Saída
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="descricao">Descrição <span class="text-danger">*</span></label>
                                    <input type="text" name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" value="{{ old('descricao') }}" required>
                                    @error('descricao')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select name="categoria" id="categoria" class="form-control @error('categoria') is-invalid @enderror">
                                        <option value="">Selecione uma categoria</option>
                                        <optgroup label="Entradas">
                                            <option value="vendas">Vendas</option>
                                            <option value="servicos">Serviços</option>
                                            <option value="investimentos">Investimentos</option>
                                            <option value="outras_entradas">Outras entradas</option>
                                        </optgroup>
                                        <optgroup label="Saídas">
                                            <option value="fornecedores">Fornecedores</option>
                                            <option value="funcionarios">Funcionários</option>
                                            <option value="impostos">Impostos</option>
                                            <option value="infraestrutura">Infraestrutura</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="outras_saidas">Outras saídas</option>
                                        </optgroup>
                                    </select>
                                    @error('categoria')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valor">Valor (MZN) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">MZN</span>
                                        </div>
                                        <input type="number" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor') }}" step="0.01" min="0.01" required>
                                        @error('valor')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="data">Data <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" name="data" id="data" class="form-control @error('data') is-invalid @enderror" value="{{ old('data', date('Y-m-d')) }}" required>
                                        @error('data')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="metodo_pagamento">Método de Pagamento</label>
                                    <select name="metodo_pagamento" id="metodo_pagamento" class="form-control @error('metodo_pagamento') is-invalid @enderror">
                                        <option value="">Selecione um método</option>
                                        <option value="dinheiro">Dinheiro</option>
                                        <option value="transferencia">Transferência Bancária</option>
                                        <option value="cartao">Cartão de Crédito/Débito</option>
                                        <option value="mpesa">M-Pesa</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                    @error('metodo_pagamento')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea name="observacoes" id="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="3">{{ old('observacoes') }}</textarea>
                                    @error('observacoes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comprovante">Anexar Comprovante</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="comprovante" name="comprovante">
                                            <label class="custom-file-label" for="comprovante">Escolher arquivo</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Formatos aceitos: PDF, JPG, PNG (máx. 2MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Salvar Transação
                        </button>
                        <a href="{{ route('admin.financeiro.index') }}" class="btn btn-default ml-2">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            // Atualiza a label do input file ao selecionar um arquivo
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $('.custom-file-label').html(fileName);
            });
            
            // Ajusta as categorias com base no tipo de transação selecionado
            $('input[name="tipo"]').change(function() {
                var tipo = $('input[name="tipo"]:checked').val();
                var categoriaSelect = $('#categoria');
                categoriaSelect.val('');
                
                if (tipo === 'entrada') {
                    categoriaSelect.find('optgroup[label="Entradas"] option').prop('disabled', false);
                    categoriaSelect.find('optgroup[label="Saídas"] option').prop('disabled', true);
                } else {
                    categoriaSelect.find('optgroup[label="Entradas"] option').prop('disabled', true);
                    categoriaSelect.find('optgroup[label="Saídas"] option').prop('disabled', false);
                }
            });
            
            // Dispara evento na carga inicial para configurar corretamente
            $('input[name="tipo"]:checked').trigger('change');
        });
    </script>
@stop