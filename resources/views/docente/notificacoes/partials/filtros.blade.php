<form id="filtroForm" class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label><i class="fas fa-filter mr-1"></i>Tipo</label>
            <select name="tipo" class="form-control select2">
                <option value="">Todos os Tipos</option>
                @foreach($porTipo as $tipo)
                    <option value="{{ $tipo->tipo }}" {{ request('tipo') == $tipo->tipo ? 'selected' : '' }}>
                        {{ ucfirst($tipo->tipo) }} ({{ $tipo->total }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><i class="fas fa-check-circle mr-1"></i>Status</label>
            <select name="lida" class="form-control select2">
                <option value="">Todos</option>
                <option value="0" {{ request('lida') === '0' ? 'selected' : '' }}>Não lidas</option>
                <option value="1" {{ request('lida') === '1' ? 'selected' : '' }}>Lidas</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><i class="fas fa-calendar mr-1"></i>Data Início</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><i class="fas fa-calendar mr-1"></i>Data Fim</label>
            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
        </div>
    </div>
</form>