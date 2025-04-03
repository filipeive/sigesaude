{{-- resources/views/components/notas-frequencia/show/acoes-rapidas.blade.php --}}
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Ações Rápidas
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-outline-primary" id="btn-exportar-excel">
                            <i class="fas fa-file-excel"></i> Exportar para Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-outline-danger" id="btn-exportar-pdf">
                            <i class="fas fa-file-pdf"></i> Exportar para PDF
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-block btn-outline-info" id="btn-imprimir">
                            <i class="fas fa-print"></i> Imprimir Pauta
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('docente.notas_frequencia.index') }}" class="btn btn-block btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
