{{-- resources/views/components/disciplinas-filtro.blade.php --}}
@props(['cursos' => [], 'niveis' => []])

<div class="card card-outline card-info mb-4 collapsed-card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-2"></i> Filtros</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: none;">
        <form method="GET" action="{{ request()->url() }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Curso</label>
                        <select class="form-control select2" name="curso_id" data-placeholder="Selecione um curso">
                            <option value="">Todos os Cursos</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                    {{ $curso->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nível</label>
                        <select class="form-control select2" name="nivel_id" data-placeholder="Selecione um nível">
                            <option value="">Todos os Níveis</option>
                            @foreach($niveis as $nivel)
                                <option value="{{ $nivel->id }}" {{ request('nivel_id') == $nivel->id ? 'selected' : '' }}>
                                    {{ $nivel->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pesquisar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Buscar disciplina...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                    <a href="{{ request()->url() }}" class="btn btn-default">
                        <i class="fas fa-broom mr-1"></i> Limpar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>