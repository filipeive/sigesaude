<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Desempenho da Turma
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart">
                            <canvas id="desempenhoChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Melhor Nota</span>
                                <span class="info-box-number" id="melhor-nota">Calculando...</span>
                            </div>
                        </div>
                        <div class="info-box mb-3 bg-danger">
                            <span class="info-box-icon"><i class="fas fa-thumbs-down"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-number" id="pior-nota">Calculando...</span>
                            </div>
                        </div>
                        <div class="info-box mb-3 bg-info">
                            <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Média da Turma</span>
                                <span class="info-box-number" id="media-turma">Calculando...</span>
                            </div>
                        </div>
                        <div class="info-box mb-3 bg-warning">
                            <span class="info-box-icon"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total de Alunos</span>
                                <span class="info-box-number" id="total-alunos">Calculando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração do gráfico
            const ctx = document.getElementById('desempenhoChart').getContext('2d');

            // Função para atualizar estatísticas
            function atualizarEstatisticas() {
                let notas = [];
                $('.nota-input').each(function() {
                    let nota = parseFloat($(this).val());
                    if (!isNaN(nota)) notas.push(nota);
                });

                if (notas.length > 0) {
                    $('#melhor-nota').text(Math.max(...notas).toFixed(1));
                    $('#pior-nota').text(Math.min(...notas).toFixed(1));
                    $('#media-turma').text((notas.reduce((a, b) => a + b) / notas.length).toFixed(1));
                    $('#total-alunos').text(notas.length);
                } else {
                    $('#melhor-nota').text('N/A');
                    $('#pior-nota').text('N/A');
                    $('#media-turma').text('N/A');
                    $('#total-alunos').text('0');
                }

                return notas;
            }

            // Inicializar gráfico
            const dados = atualizarEstatisticas();
            const distribuicao = [0, 0, 0, 0, 0]; // [0-4, 5-9, 10-13, 14-16, 17-20]

            dados.forEach(nota => {
                if (nota >= 0 && nota <= 4) distribuicao[0]++;
                else if (nota <= 9) distribuicao[1]++;
                else if (nota <= 13) distribuicao[2]++;
                else if (nota <= 16) distribuicao[3]++;
                else if (nota <= 20) distribuicao[4]++;
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['0-4', '5-9', '10-13', '14-16', '17-20'],
                    datasets: [{
                        label: 'Distribuição de Notas',
                        data: distribuicao,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(255, 159, 64, 0.5)',
                            'rgba(255, 205, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(54, 162, 235, 0.5)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Distribuição de Notas da Turma'
                        }
                    }
                }
            });

            // Atualizar gráfico quando as notas mudarem
            $('.nota-input').on('change', function() {
                const dados = atualizarEstatisticas();
                const distribuicao = [0, 0, 0, 0, 0];

                dados.forEach(nota => {
                    if (nota >= 0 && nota <= 4) distribuicao[0]++;
                    else if (nota <= 9) distribuicao[1]++;
                    else if (nota <= 13) distribuicao[2]++;
                    else if (nota <= 16) distribuicao[3]++;
                    else if (nota <= 20) distribuicao[4]++;
                });

                chart.data.datasets[0].data = distribuicao;
                chart.update();
            });
        });
    </script>
@endpush
