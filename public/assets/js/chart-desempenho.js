document.addEventListener('DOMContentLoaded', function() {
    // Configuração inicial
    let desempenhoChart = null;
    
    // Cores para os datasets
    const cores = {
        frequencia: {
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)'
        },
        trabalho: {
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)'
        },
        notaFinal: {
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)'
        },
        aprovados: {
            backgroundColor: 'rgba(40, 167, 69, 0.6)',
            borderColor: 'rgba(40, 167, 69, 1)'
        },
        reprovados: {
            backgroundColor: 'rgba(220, 53, 69, 0.6)',
            borderColor: 'rgba(220, 53, 69, 1)'
        }
    };
    
    // Função para inicializar o gráfico
    function inicializarGraficoDesempenho() {
        const ctx = document.getElementById('desempenhoChart').getContext('2d');
        const dados = coletarDadosDesempenho();
        
        // Definir datasets com base no tipo de gráfico
        const datasets = getDatasetsPorTipo(dados);
        
        desempenhoChart = new Chart(ctx, {
            type: window.chartType || 'bar',
            data: {
                labels: ['0-4', '5-9', '10-13', '14-16', '17-20'],
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Análise de Desempenho da Turma'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0) || 1;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Função para obter datasets conforme o tipo de gráfico
    function getDatasetsPorTipo(dados) {
        const tipo = window.chartType || 'bar';
        
        // Datasets padrão para gráfico de barras ou linhas
        if (tipo === 'bar' || tipo === 'line') {
            return [
                {
                    label: 'Frequência',
                    data: dados.faixasFrequencia,
                    backgroundColor: cores.frequencia.backgroundColor,
                    borderColor: cores.frequencia.borderColor,
                    borderWidth: 1
                },
                {
                    label: 'Trabalhos',
                    data: dados.faixasTrabalho,
                    backgroundColor: cores.trabalho.backgroundColor,
                    borderColor: cores.trabalho.borderColor,
                    borderWidth: 1
                },
                {
                    label: 'Nota Final',
                    data: dados.faixasNotaFinal,
                    backgroundColor: cores.notaFinal.backgroundColor,
                    borderColor: cores.notaFinal.borderColor,
                    borderWidth: 1
                }
            ];
        } 
        // Dataset para gráfico de pizza (mostra apenas aprovados/reprovados)
        else if (tipo === 'pie') {
            return [{
                data: [dados.reprovados, dados.aprovados],
                backgroundColor: [
                    cores.reprovados.backgroundColor,
                    cores.aprovados.backgroundColor
                ],
                borderColor: [
                    cores.reprovados.borderColor,
                    cores.aprovados.borderColor
                ],
                borderWidth: 1
            }];
        }
    }
    
    // Função para coletar dados para o gráfico
    function coletarDadosDesempenho() {
        // Código semelhante à função coletarDadosGrafico
        // Mas adaptado para o contexto específico deste gráfico
        const dados = {
            faixasFrequencia: [0, 0, 0, 0, 0],
            faixasTrabalho: [0, 0, 0, 0, 0],
            faixasNotaFinal: [0, 0, 0, 0, 0],
            aprovados: 0,
            reprovados: 0,
            mediaFrequencia: 0,
            mediaTrabalho: 0,
            mediaNotaFinal: 0
        };
        
        // Processamento das notas...
        // Em uma aplicação real, este código seria mais elaborado
        // e processaria os dados diretamente das tabelas
        
        return dados;
    }
    
    // Iniciar o gráfico quando a página carregar
    inicializarGraficoDesempenho();
});