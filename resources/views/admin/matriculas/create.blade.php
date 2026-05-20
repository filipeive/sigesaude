@extends('adminlte::page')

@section('title', 'Nova Matrícula')

@section('content_header')
    <h1><i class="fas fa-plus-circle mr-2"></i>Nova Matrícula</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.matriculas.index') }}">Matrículas</a></li>
        <li class="breadcrumb-item active">Nova</li>
    </ol>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-info" style="margin-bottom: 20px;">
                <h5><i class="fas fa-info-circle mr-2"></i> Informacoes sobre Pagamento</h5>
                <p class="mb-1">Apos criar a matricula, sera gerada uma <strong>referencia ATM</strong> para o estudante.</p>
                <ul class="mb-0">
                    <li>A matricula anual pode ser paga de uma unica vez ou parcelada.</li>
                    <li>Metodos disponiveis: <strong>ATM</strong>, <strong>Internet Banking</strong>, <strong>Deposito Bancario</strong>.</li>
                    <li>Entidade para pagamento: <strong>11151</strong> — a referencia é gerada automaticamente.</li>
                    <li>Apos o pagamento, o encarregado deve enviar o <strong>comprovativo</strong> pela plataforma.</li>
                    <li>As <strong>propinas mensais</strong> sao geradas separadamente e devem ser saldadas ate ao dia 10 de cada mes.</li>
                </ul>
            </div>
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados da Matrícula</h3>
                </div>
                <form action="{{ route('admin.matriculas.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estudante_id">Estudante <span class="text-danger">*</span></label>
                                    <select name="estudante_id" id="estudante_id" class="form-control select2 @error('estudante_id') is-invalid @enderror" required>
                                        <option value="">Selecione o estudante</option>
                                        @foreach($estudantes as $estudante)
                                            <option value="{{ $estudante->id }}" {{ old('estudante_id') == $estudante->id ? 'selected' : '' }}>
                                                {{ $estudante->user->name }} ({{ $estudante->matricula }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estudante_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turma_id">Turma <span class="text-danger">*</span></label>
                                    <select name="turma_id" id="turma_id" class="form-control @error('turma_id') is-invalid @enderror" required>
                                        <option value="">Selecione a turma</option>
                                        @foreach($turmas as $turma)
                                            <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>
                                                {{ $turma->nome }} ({{ $turma->classe->nome ?? '' }}) - {{ $turma->anoLectivo->ano ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('turma_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ano_lectivo_id">Ano Letivo <span class="text-danger">*</span></label>
                                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control @error('ano_lectivo_id') is-invalid @enderror" required>
                                        <option value="">Selecione o ano letivo</option>
                                        @foreach($anosLectivos as $ano)
                                            <option value="{{ $ano->id }}" {{ old('ano_lectivo_id') == $ano->id ? 'selected' : '' }}>
                                                {{ $ano->ano }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="valor">Valor da Matrícula (MZN)</label>
                                <input type="number" step="0.01" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', 1500) }}">
                                <small class="text-muted">Valor padrão: MZN 1.500,00 (taxa de pré-inscrição / matrícula anual)</small>

                                    @error('valor')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_matricula">Data da Matrícula</label>
                                    <input type="date" name="data_matricula" id="data_matricula" class="form-control @error('data_matricula') is-invalid @enderror" value="{{ old('data_matricula', date('Y-m-d')) }}">
                                    @error('data_matricula')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status Inicial</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="Pendente" {{ old('status') == 'Pendente' ? 'selected' : '' }}>Pendente (Aguardando Pagamento)</option>
                                        <option value="Ativo" {{ old('status') == 'Ativo' ? 'selected' : '' }}>Ativo (Pago)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observacoes">Observações</label>
                            <textarea name="observacoes" id="observacoes" rows="3" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Confirmar Matrícula</button>
                        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-default">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@stop