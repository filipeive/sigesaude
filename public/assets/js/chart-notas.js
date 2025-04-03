document.addEventListener('DOMContentLoaded', function() {
    // Configuração inicial do gráfico
    let chartType = 'bar';
    let chartInstance = null;
    
    // Inicializar o gráfico
    inicializarGrafico();
    
    // Atualizar estatísticas quando a página carrega
    atualizarEstatisticas();
    
    // Atualizar quando uma nota é alterada
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('change', function() {
            const tr = this.closest('tr');
            tr.classList.add('highlight');
            
            // Atualizar estatísticas e gráfico
            atualizarEstatisticas();
            atualizarGrafico();
            
            // Remover destaque após animação
            setTimeout(() => {
                tr.classList.remove('highlight');
            }, 1000);
        });
    });
    
    // Alternar tipo de gráfico
    document.querySelectorAll('.chart-type').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            chartType = this.getAttribute('data-type');
            atualizarGrafico();
        });
    });
    
    // Botões de ação rápida
    document.getElementById('btn-salvar-todas').addEventListener('click', function() {
        salvarTodasNotas();
    });
    
    document.getElementById('btn-aprovar-todos').addEventListener('click', function() {
        Swal.fire({
            title: 'Aprovar todos os estudantes?',
            text: "Esta ação irá definir a nota mínima de aprovação para todos os estudantes pendentes.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Sim, aprovar todos!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                aprovarTodosEstudantes();
            }
        });
    });
    
    // Função para inicializar o gráfico
    function inicializarGrafico() {
        const ctx = document.getElementById('notasChart').getContext('2d');
        const dados = coletarDadosGrafico();
        
        chartInstance = new Chart(ctx, configurarGrafico(dados));
    }
    
    // Função para atualizar o gráfico
    function atualizarGrafico() {
        const dados = coletarDadosGrafico();
        
        if (chartInstance) {
            chartInstance.destroy();
        }
        
        const ctx = document.getElementById('notasChart').getContext('2d');
        chartInstance = new Chart(ctx, configurarGrafico(dados));
    }
    
    // Função para configurar o gráfico
    function configurarGrafico(dados) {
        const config = {
            type: chartType,
            data: {
                labels: ['0-4', '5-9', '10-13', '14-16', '17-20'],
                datasets: [{
                    label: 'Distribuição de Notas',
                    data: dados.faixas,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
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
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribuição de Notas dos Alunos'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = dados.total;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${value} estudantes (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };
        
        // Configurações específicas para cada tipo de gráfico
        if (chartType === 'bar') {
            config.options.scales = {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Estudantes'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Intervalo de Notas'
                    }
                }
            };
        }
        
        return config;
    }
    
    // Função para coletar dados do gráfico
    function coletarDadosGrafico() {
        const faixas = [0, 0, 0, 0, 0]; // [0-4, 5-9, 10-13, 14-16, 17-20]
        let total = 0;
        let soma = 0;
        let min = 20;
        let max = 0;
        
        document.querySelectorAll('.nota-input').forEach(input => {
            const nota = parseFloat(input.value);
            if (!isNaN(nota)) {
                // Incrementar faixa correspondente
                if (nota >= 0 && nota <= 4) faixas[0]++;
                else if (nota >= 5 && nota <= 9) faixas[1]++;
                else if (nota >= 10 && nota <= 13) faixas[2]++;
                else if (nota >= 14 && nota <= 16) faixas[3]++;
                else if (nota >= 17 && nota <= 20) faixas[4]++;
                
                // Atualizar estatísticas
                total++;
                soma += nota;
                min = Math.min(min, nota);
                max = Math.max(max, nota);
            }
        });
        
        // Calcular média
        const media = total > 0 ? (soma / total).toFixed(1) : 0;
        
        // Atualizar rodapé do gráfico
        if (total > 0) {
            document.getElementById('media-turma').textContent = media;
            document.getElementById('nota-maxima').textContent = max.toFixed(1);
            document.getElementById('nota-minima').textContent = min.toFixed(1);
        }
        
        return {
            faixas: faixas,
            total: total,
            media: media,
            min: min,
            max: max
        };
    }
    
    // Função para atualizar contadores e estatísticas
    function atualizarEstatisticas() {
        let aprovados = 0;
        let reprovados = 0;
        let pendentes = 0;
        let total = 0;
        
        document.querySelectorAll('#tabela-notas tbody tr').forEach(tr => {
            const status = tr.querySelector('td:nth-child(6)').textContent.trim();
            if (status === 'Aprovado') {
                aprovados++;
            } else if (status === 'Reprovado') {
                reprovados++;
            } else {
                pendentes++;
            }
            total++;
        });
        
        // Atualizar contadores
        document.getElementById('total-aprovados').textContent = aprovados;
        document.getElementById('total-reprovados').textContent = reprovados;
        document.getElementById('total-pendentes').textContent = pendentes;
        
        // Atualizar barras de progresso
        if (total > 0) {
            const percAprovados = ((aprovados / total) * 100).toFixed(1);
            const percReprovados = ((reprovados / total) * 100).toFixed(1);
            const percPendentes = ((pendentes / total) * 100).toFixed(1);
            
            document.getElementById('progress-aprovados').style.width = percAprovados + '%';
            document.getElementById('progress-reprovados').style.width = percReprovados + '%';
            document.getElementById('progress-pendentes').style.width = percPendentes + '%';
            
            document.getElementById('percent-aprovados').textContent = percAprovados + '% dos estudantes';
            document.getElementById('percent-reprovados').textContent = percReprovados + '% dos estudantes';
            document.getElementById('percent-pendentes').textContent = percPendentes + '% dos estudantes';
        }
    }
    
    // Outras funções de utilitário
    function salvarTodasNotas() {
        // Implementar lógica para salvar todas as notas
        Swal.fire({
            title: 'Salvando notas...',
            text: 'Por favor, aguarde',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulação de salvamento
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Notas salvas com sucesso!',
                showConfirmButton: false,
                timer: 1500
            });
        }, 1500);
    }
    
    function aprovarTodosEstudantes() {
        // Implementar lógica para aprovar todos os estudantes
        const notaMinima = 10; // Nota mínima de aprovação
        
        document.querySelectorAll('#tabela-notas tbody tr').forEach(tr => {
            const status = tr.querySelector('td:nth-child(6)').textContent.trim();
            if (status !== 'Aprovado' && status !== 'Reprovado') {
                const inputNota = tr.querySelector('.nota-input');
                if (parseFloat(inputNota.value) < notaMinima) {
                    inputNota.value = notaMinima;
                    // Disparar evento de alteração para atualizar o status
                    const event = new Event('change');
                    inputNota.dispatchEvent(event);
                }
            }
        });
        
        Swal.fire({
            icon: 'success',
            title: 'Todos os estudantes foram aprovados!',
            showConfirmButton: false,
            timer: 1500
        });
    }
});