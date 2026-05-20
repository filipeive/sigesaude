<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escola dos Visionários - Sistema de Gestão Escolar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1>Escola dos Visionários - Sistema de Gestão Escolar</h1>
        </div>
    </header>

        <!-- Navigation -->
        <nav class="container-fluid">
            <div class="nav-links">
                <a href="#sobre"><i class="fas fa-info-circle"></i> Sobre</a>
                <a href="#contatos"><i class="fas fa-phone"></i> Contatos</a>
            </div>
            <a href="/login" class="btn-login"><i class="fas fa-sign-in-alt"></i> Entrar no Painel</a>
        </nav>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="vagas-badge" style="display: inline-block; background: #28a745; color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-bottom: 15px; animation: pulse 2s infinite;">
                <i class="fas fa-bullhorn mr-2"></i> VAGAS ABERTAS PARA 2026
            </div>
            <h2>Bem-vindo à Escola dos Visionários</h2>
            <p>Sistema de Gestão Escolar — Ensino Secundário. Garanta a sua vaga para o ano lectivo de 2026.</p>
            <div class="hero-buttons">
                <a href="#inscricao" class="hero-btn btn-warning" style="background: #ffc107; color: #212529; border: none; font-size: 1.2rem; padding: 15px 30px;">
                    <i class="fas fa-user-plus mr-1"></i> Fazer Pré-Inscrição Agora
                </a>
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
                    <i class="fas fa-school"></i>
                </div>
                <h3>Gestão Acadêmica</h3>
                <p>Gerencie matrículas, notas, frequência, turmas e currículos com facilidade. Nossa plataforma
                    simplifica todo o processo acadêmico para instituições de ensino.</p>
            </div>

            <!-- Feature: Agendamento -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Agendamento</h3>
                <p>Organize aulas, laboratórios, atividades práticas e eventos. Sistema integrado que permite coordenar
                    horários de professores, alunos e recursos de maneira eficiente.</p>
            </div>

            <!-- Feature: Relatórios e Análises -->
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Relatórios e Análises</h3>
                <p>Tenha acesso a dados e métricas importantes sobre desempenho acadêmico, frequência e outros
                    indicadores essenciais para a tomada de decisões na gestão educacional.</p>
            </div>
        </div>

        <!-- Pre-Inscrição Section -->
        <section id="inscricao" class="section" style="margin-top: 40px;">
            <div class="section-header">
                <h2>Garanta a sua Vaga</h2>
                <p>Faça a sua pré-inscrição online, pague a taxa de inscrição e confirme na secretaria da escola.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="contact-grid">
                <div class="contact-form" style="grid-column: span 2; max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <h3 style="text-align: center; margin-bottom: 25px;">Formulário de Pré-Inscrição</h3>
                    <p style="text-align: center; margin-bottom: 20px; color: #666; font-size: 0.9rem;">
                        Após a inscrição, você receberá uma referência de pagamento. Efectue o pagamento e envie o comprovativo para confirmar a sua vaga.
                    </p>
                    <form action="{{ route('pre-inscricao.store') }}" method="POST">
                        @csrf
                        <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px;">
                                <label for="nome_completo" style="display: block; margin-bottom: 8px; font-weight: 600;">Nome Completo</label>
                                <input type="text" class="form-control" name="nome_completo" id="nome_completo" placeholder="Seu nome completo" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                            </div>
                            <div style="flex: 1; min-width: 250px;">
                                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600;">Email (opcional)</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Seu email" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                            </div>
                        </div>
                        <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px;">
                                <label for="telefone" style="display: block; margin-bottom: 8px; font-weight: 600;">Telefone</label>
                                <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Seu telefone" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                            </div>
                            <div style="flex: 1; min-width: 250px;">
                                <label for="classe_id" style="display: block; margin-bottom: 8px; font-weight: 600;">Classe Desejada</label>
                                <select class="form-control" name="classe_id" id="classe_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;">
                                    <option value="">Selecione uma classe</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}">{{ $classe->nome }} ({{ $classe->nivel }}º Ano)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit" style="width: 100%; padding: 15px; background: #007bff; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s;">
                            <i class="fas fa-user-plus mr-1"></i> Realizar Pré-Inscrição
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Métodos de Pagamento -->
        <section class="section" style="background-color: #e3f2fd; padding: 30px; border-radius: 15px; border-left: 4px solid #1976d2;">
            <h2 style="text-align: center; margin-bottom: 25px;"><i class="fas fa-credit-card mr-2"></i>Métodos de Pagamento</h2>
            <p style="text-align: center; color: #546e7a; margin-bottom: 20px;">
                Após fazer a sua pré-inscrição, você receberá um código de referencia. Para confirmar a sua vaga, 
                efectue o pagamento da taxa de inscrição e envie o comprovativo.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h4 style="color: #1976d2; margin-bottom: 12px;"><i class="fas fa-atm mr-1"></i> ATM</h4>
                    <p>Dirija-se a qualquer ATM dos bancos parceiros, selecione <strong>Pagamentos &gt; Pagamento de Serviços</strong>, 
                    informe a entidade <strong>11151</strong>, digite a referência e confirme o pagamento.</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h4 style="color: #1976d2; margin-bottom: 12px;"><i class="fas fa-laptop mr-1"></i> Internet Banking</h4>
                    <p>Acesse a sua conta bancária online, procure por <strong>Pagamento de Serviços</strong>, 
                    informe a entidade <strong>11151</strong>, a referência e o valor para efectuar o pagamento.</p>
                </div>
                <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h4 style="color: #1976d2; margin-bottom: 12px;"><i class="fas fa-university mr-1"></i> Depósito Bancário</h4>
                    <p>Pode efectuar o depósito ou transferência diretamente na conta da escola no banco parceiro. 
                    Não se esqueça de mencionar o seu nome completo e a referência no comprovante.</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px; color: #d32f2f; font-weight: 600;">
                <i class="fas fa-exclamation-circle mr-1"></i>
                Não efectue o pagamento sem antes confirmar a sua pré-inscrição. Envie o comprovante pela plataforma após o pagamento.
            </div>
        </section>

        <!-- About Section -->
        <section id="sobre" class="section" style="background-color: #f8fafc; padding:20px; border-radius:15px">
            <div class="section-header">
                <h2>Sobre o Sistema</h2>
                <p>Conheça mais sobre nossa plataforma de gestão escolar</p>
            </div>

            <div class="about-content">
                <div class="about-image">
                    <img src="https://idesolucoes.com.br/wp-content/uploads/2022/11/62e3fabbeaf1397f2f08ecff_clico-responde-o-que-e-sistema-de-gestao.svg"
                        alt="Sistema de Gestão Escolar">
                </div>

                <div class="about-text">
                    <h3>Transformando a Gestão Educacional</h3>
                    <p>O Sistema de Gestão Escolar da Escola dos Visionários foi desenvolvido especificamente para atender às necessidades das instituições de ensino do ensino secundário, oferecendo ferramentas especializadas que facilitam a gestão académica, financeira e pedagógica.</p>
                    <p>Nossa plataforma combina tecnologia moderna com uma interface intuitiva, permitindo que gestores, professores, alunos e encarregados realizem suas tarefas com eficiência e precisão.</p>
                    <p>Com módulos integrados para gestão de matrículas, propinas mensais, notas, frequência e muito mais, nosso sistema se adapta às necessidades específicas de cada escola. </p>
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
                    <h3><i class="fas fa-id-card"></i> Nossos Contatos</h3>

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
                            <a href="mailto:contato@escolavisionarios.edu">contato@escolavisionarios.edu</a>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-detail">
                            <h4>Endereço</h4>
                            <p>Cidade de Maputo, Moçambique</p>
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
                    <h3>Escola dos Visionários</h3>
                </div>
                <p>Sistema de gestão escolar especializado para ensino secundário — Ensino Geral e Técnico.</p>
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
                    <p>Cidade de Maputo, Moçambique</p>
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
                    <p>contato@escolavisionarios.edu</p>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Escola dos Visionários. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>
