{{-- resources/views/components/notas-frequencia/show/resumo-frequencia.blade.php --}}
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total de Estudantes</span>
                <span class="info-box-number">{{ count($estudantes) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Admitidos</span>
                <span class="info-box-number" id="total-admitidos">
                    {{ collect($estudantes)->filter(function($item) {
                        return optional($item['notas_frequencia'])->status === 'Admitido';
                    })->count() }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Excluídos</span>
                <span class="info-box-number" id="total-excluidos">
                    {{ collect($estudantes)->filter(function($item) {
                        return optional($item['notas_frequencia'])->status === 'Excluído';
                    })->count() }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pendentes</span>
                <span class="info-box-number" id="total-pendentes">
                    {{ collect($estudantes)->filter(function($item) {
                        return !$item['notas_frequencia'] || !$item['notas_frequencia']->status;
                    })->count() }}
                </span>
            </div>
        </div>
    </div>
</div>
