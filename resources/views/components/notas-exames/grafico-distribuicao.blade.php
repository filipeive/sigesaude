{{-- resources/views/components/grafico-distribuicao.blade.php --}}

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Distribuição de Notas
                </h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-chart-pie"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item chart-type" data-type="bar">
                                <i class="fas fa-chart-bar mr-2"></i> Gráfico de Barras
                            </a>
                            <a href="#" class="dropdown-item chart-type" data-type="pie">
                                <i class="fas fa-chart-pie mr-2"></i> Gráfico de Pizza
                            </a>
                            <a href="#" class="dropdown-item chart-type" data-type="line">
                                <i class="fas fa-chart-line mr-2"></i> Gráfico de Linha
                            </a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="notasChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Média da turma: <span id="media-turma" class="font-weight-bold">--</span></small>
                    </div>
                    <div>
                        <small class="text-muted">Nota mais alta: <span id="nota-maxima" class="font-weight-bold">--</span></small>
                    </div>
                    <div>
                        <small class="text-muted">Nota mais baixa: <span id="nota-minima" class="font-weight-bold">--</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>