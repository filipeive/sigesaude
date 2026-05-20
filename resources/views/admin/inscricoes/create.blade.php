@extends('adminlte::page')

@section('title', 'Nova Inscrição Semestral')

@section('content_header')
    <h1><i class="fas fa-user-plus mr-2"></i>Nova Inscrição Semestral</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.inscricoes.index') }}">Inscrições Semestrais</a></li>
        <li class="breadcrumb-item active">Nova</li>
    </ol>
@stop

@section('content')
    <div class="card shadow-lg">
        <div class="card-body">
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle mr-1"></i>
                A inscrição semestral confirma o estudante para o semestre/ano lectivo selecionado.
                As matérias/disciplinas são geridas directamente no módulo de <strong>Disciplinas</strong> por Turma e Classe.
            </div>

            <form action="{{ route('admin.inscricoes.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="estudante_id"><i class="fas fa-user mr-1"></i> Estudante</label>
                            <select name="estudante_id" id="estudante_id" class="form-control select2" required>
                                <option value="">Selecione um estudante</option>
                                @foreach($estudantes as $estudante)
                                    <option value="{{ $estudante->id }}">
                                        {{ $estudante->user->name }} — {{ $estudante->turma?->classe?->nome ?? 'Sem turma' }} ({{ $estudante->turma?->nome ?? 'N/T' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ano_lectivo_id"><i class="fas fa-calendar mr-1"></i> Ano Lectivo</label>
                            <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control" required>
                                @foreach(\App\Models\AnoLectivo::orderBy('ano', 'desc')->get() as $ano)
                                    <option value="{{ $ano->id }}" {{ $ano->status == 'Ativo' ? 'selected' : '' }}>
                                        {{ $ano->ano }} {{ $ano->status == 'Ativo' ? '(Activo)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="semestre"><i class="fas fa-calendar-alt mr-1"></i> Semestre</label>
                            <select name="semestre" id="semestre" class="form-control" required>
                                <option value="1">1º Semestre</option>
                                <option value="2">2º Semestre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor"><i class="fas fa-money-bill mr-1"></i> Valor da Inscrição (MZN)</label>
                            <input type="number" step="0.01" name="valor" id="valor" class="form-control" value="{{ old('valor', 3750) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="referencia"><i class="fas fa-hashtag mr-1"></i> Referência ATM (Entidade 11151)</label>
                            <div class="input-group">
                                <input type="text" name="referencia" id="referencia" class="form-control"
                                    value="{{ 'INS-' . strtoupper(Str::random(8)) }}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText(this.previousElementSibling.value); this.innerHTML='<i class=\'fas fa-check\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fas fa-copy\'></i>', 1500)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">A referência é usada para pagamento da taxa de inscrição.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="observacoes"><i class="fas fa-sticky-note mr-1"></i> Observações</label>
                    <textarea name="observacoes" id="observacoes" class="form-control" rows="2"
                        placeholder="Instruções adicionais ou justificativa de aprovação/rejeição...">{{ old('observacoes') }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Criar Inscrição</button>
                    <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop
