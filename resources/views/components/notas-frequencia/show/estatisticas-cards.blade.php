@props(['estudantes' => []])

<div class="row mb-4">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-info shadow-sm elevation-1">
            <span class="info-box-icon"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total de Estudantes</span>
                <span class="info-box-number">{{ count($estudantes) }}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    Matriculados na disciplina
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-success shadow-sm elevation-1">
            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Aprovados</span>
                <span class="info-box-number" id="total-aprovados">Calculando...</span>
                <div class="progress">
                    <div class="progress-bar" id="progress-aprovados" style="width: 0%"></div>
                </div>
                <span class="progress-description" id="percent-aprovados">
                    0% dos estudantes
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-danger shadow-sm elevation-1">
            <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Reprovados</span>
                <span class="info-box-number" id="total-reprovados">Calculando...</span>
                <div class="progress">
                    <div class="progress-bar" id="progress-reprovados" style="width: 0%"></div>
                </div>
                <span class="progress-description" id="percent-reprovados">
                    0% dos estudantes
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-warning shadow-sm elevation-1">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pendentes</span>
                <span class="info-box-number" id="total-pendentes">Calculando...</span>
                <div class="progress">
                    <div class="progress-bar" id="progress-pendentes" style="width: 0%"></div>
                </div>
                <span class="progress-description" id="percent-pendentes">
                    0% dos estudantes
                </span>
            </div>
        </div>
    </div>
</div>