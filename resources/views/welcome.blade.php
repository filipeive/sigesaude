<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituto de Saúde - Sistema de Gestão Escolar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h1>Instituto de Saúde - Sistema de Gestão Escolar</h1>
        </div>
    </header>

        <!-- Navigation -->
        <nav class="container-fluid">
            <div class="nav-links">
                <a href="#sobre"><i class="fas fa-notes-medical"></i> Sobre</a>
                <a href="#contatos"><i class="fas fa-hospital-user"></i> Contatos</a>
            </div>
            <a href="/login" class="btn-login"><i class="fas fa-user-md"></i> Entrar no Painel</a>
        </nav>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Bem-vindo ao Sistema de Gestão Escolar</h2>
            <p>Uma plataforma completa para gerenciamento de instituições de ensino na área da saúde</p>
            <div class="hero-buttons">
                <a href="/login" class="hero-btn btn-primary">Acessar Sistema</a>
                <a href="#sobre" class="hero-btn btn-secondary">Saiba Mais</a>
            </div>
        </div>
    </section>
    <!-- Main Content -->
    <div class="container">
        <!-- Features Section -->
        <div class="features">
            <!-- Feature: Gestão Acadêmica -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <h3>Gestão Acadêmica</h3>
                <p>Gerencie matrículas, notas, frequência, turmas e currículos com facilidade. Nossa plataforma
                    simplifica todo o processo acadêmico para instituições de ensino na área da saúde.</p>
            </div>

            <!-- Feature: Agendamento -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <h3>Agendamento</h3>
                <p>Organize aulas, laboratórios, atividades práticas e eventos. Sistema integrado que permite coordenar
                    horários de professores, alunos e recursos de maneira eficiente.</p>
            </div>

            <!-- Feature: Relatórios e Análises -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-first-aid"></i>
                </div>
                <h3>Relatórios e Análises</h3>
                <p>Tenha acesso a dados e métricas importantes sobre desempenho acadêmico, frequência e outros
                    indicadores essenciais para a tomada de decisões na gestão educacional em saúde.</p>
            </div>
        </div>

        <!-- About Section -->
        <section id="sobre" class="section" style="background-color: #f8fafc; padding:20px; border-radius:15px">
            <div class="section-header">
                <h2>Sobre o Sistema</h2>
                <p>Conheça mais sobre nossa plataforma de gestão escolar para institutos de saúde</p>
            </div>

            <div class="about-content">
                <div class="about-image">
                    <img src="https://idesolucoes.com.br/wp-content/uploads/2022/11/62e3fabbeaf1397f2f08ecff_clico-responde-o-que-e-sistema-de-gestao.svg"
                        alt="Sistema de Gestão Escolar">
                </div>

                <div class="about-text">
                    <h3>Transformando a Gestão Educacional em Saúde</h3>
                    <p>O Sistema de Gestão Escolar foi desenvolvido especificamente para atender às necessidades das
                        instituições de ensino na área da saúde, oferecendo ferramentas especializadas que facilitam o
                        gerenciamento acadêmico, administrativo e pedagógico.</p>
                    <p>Nossa plataforma combina tecnologia avançada com uma interface intuitiva, permitindo que
                        coordenadores, professores e funcionários administrativos realizem suas tarefas com eficiência e
                        precisão, economizando tempo e recursos.</p>
                    <p>Com módulos integrados para gestão de alunos, professores, cursos, disciplinas, avaliações e
                        muito mais, nosso sistema se adapta às necessidades específicas do seu instituto de saúde.</p>
                </div>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Instituições Atendidas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfação dos Usuários</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5 anos</div>
                    <div class="stat-label">de Experiência no Mercado</div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contatos" class="section">
            <div class="section-header">
                <h2>Contatos</h2>
                <p>Entre em contato conosco para mais informações ou suporte técnico</p>
            </div>

            <div class="contact-grid">
                <div class="contact-info">
                    <h3><i class="fas fa-laptop-medical"></i> Nossos Contatos</h3>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-detail">
                            <h4>Telefone</h4>
                            <p>(11) 3456-7890</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-detail">
                            <h4>Email</h4>
                            <a href="mailto:contato@institutosaude.com.br">contato@institutosaude.com.br</a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-detail">
                            <h4>Endereço</h4>
                            <p>Av. Paulista, 1000 - Bela Vista, São Paulo - SP, 01310-100</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-detail">
                            <h4>Horário de Atendimento</h4>
                            <p>Segunda a Sexta: 8h às 18h</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h3>Envie-nos uma Mensagem</h3>
                    <form>
                        <div class="form-group">
                            <label for="name">Nome Completo</label>
                            <input type="text" class="form-control" id="name" placeholder="Seu nome">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Seu email">
                        </div>
                        <div class="form-group">
                            <label for="subject">Assunto</label>
                            <input type="text" class="form-control" id="subject" placeholder="Assunto da mensagem">
                        </div>
                        <div class="form-group">
                            <label for="message">Mensagem</label>
                            <textarea class="form-control" id="message" placeholder="Digite sua mensagem"></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <div class="footer-logo-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Instituto de Saúde</h3>
                </div>
                <p>Sistema de gestão escolar especializado para instituições de ensino na área da saúde.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Início</a></li>
                    <li><a href="#sobre"><i class="fas fa-chevron-right"></i> Sobre</a></li>
                    <li><a href="#contatos"><i class="fas fa-chevron-right"></i> Contatos</a></li>
                    <li><a href="/login"><i class="fas fa-chevron-right"></i> Login</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Recursos</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Suporte Técnico</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Documentação</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Tutoriais</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Contato</h3>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <p>Av. Paulista, 1000 - Bela Vista, São Paulo - SP</p>
                </div>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <p>(11) 3456-7890</p>
                </div>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <p>contato@institutosaude.com.br</p>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Instituto de Saúde. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>
