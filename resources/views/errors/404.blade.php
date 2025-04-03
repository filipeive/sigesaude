{{-- resources/views/errors/404.blade.php --}}
@extends('adminlte::page')

@section('title', 'Página Não Encontrada - 404')

@section('content_header')
@stop

@section('content')
<div class="error-container">
    <div class="error-content">
        <div class="error-illustration">
            <svg viewBox="0 0 200 200" class="astronaut">
                <circle cx="100" cy="100" r="90" fill="#f0f6ff" />
                <path d="M100,15 A85,85 0 1,1 15,100 A85,85 0 1,1 100,15" fill="none" stroke="#3490dc" stroke-width="4" />
                <g class="astronaut-figure">
                    <circle cx="100" cy="80" r="25" fill="#ffffff" stroke="#3490dc" stroke-width="2" />
                    <rect x="85" y="105" width="30" height="40" rx="10" fill="#ffffff" stroke="#3490dc" stroke-width="2" />
                    <circle cx="92" cy="77" r="5" fill="#3490dc" />
                    <circle cx="108" cy="77" r="5" fill="#3490dc" />
                    <path d="M95,90 C95,93 105,93 105,90" stroke="#3490dc" stroke-width="2" fill="none" />
                    <line x1="70" y1="90" x2="85" y2="110" stroke="#ffffff" stroke-width="10" stroke-linecap="round" />
                    <line x1="130" y1="90" x2="115" y2="110" stroke="#ffffff" stroke-width="10" stroke-linecap="round" />
                    <line x1="70" y1="90" x2="85" y2="110" stroke="#3490dc" stroke-width="2" stroke-linecap="round" />
                    <line x1="130" y1="90" x2="115" y2="110" stroke="#3490dc" stroke-width="2" stroke-linecap="round" />
                    <line x1="90" y1="145" x2="90" y2="165" stroke="#ffffff" stroke-width="10" stroke-linecap="round" />
                    <line x1="110" y1="145" x2="110" y2="165" stroke="#ffffff" stroke-width="10" stroke-linecap="round" />
                    <line x1="90" y1="145" x2="90" y2="165" stroke="#3490dc" stroke-width="2" stroke-linecap="round" />
                    <line x1="110" y1="145" x2="110" y2="165" stroke="#3490dc" stroke-width="2" stroke-linecap="round" />
                </g>
                <g class="stars">
                    <circle cx="40" cy="30" r="2" fill="#3490dc" />
                    <circle cx="150" cy="40" r="2" fill="#3490dc" />
                    <circle cx="170" cy="110" r="2" fill="#3490dc" />
                    <circle cx="30" cy="130" r="2" fill="#3490dc" />
                    <circle cx="130" cy="160" r="2" fill="#3490dc" />
                    <circle cx="60" cy="165" r="2" fill="#3490dc" />
                </g>
            </svg>
        </div>
        <div class="error-message-container">
            <h1 class="error-title">404</h1>
            <h2 class="error-subtitle">Página não encontrada</h2>
            <p class="error-description">
                Parece que você se perdeu no espaço digital. 
                A página que você está procurando pode ter sido movida ou não existe.
            </p>
            <div class="error-actions">
                <a href="{{ url('/admin') }}" class="btn-home">
                    <i class="fas fa-home"></i> Página Inicial
                </a>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
    <div class="floating-elements"></div>
</div>
@stop

@section('css')
<style>
    /* Cores personalizadas */
    :root {
        --primary-color: #3490dc;
        --secondary-color: #9561e2;
        --accent-color: #f66d9b;
        --background-color: #f8fafc;
        --text-color: #2d3748;
        --light-text: #718096;
    }

    /* Reset do layout para a página de erro */
    .content-wrapper {
        background-color: var(--background-color) !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
    }

    .content {
        padding: 0 !important;
        margin: 0 !important;
        height: 100% !important;
    }

    /* Container principal */
    .error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    /* Conteúdo do erro */
    .error-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
        max-width: 1200px;
        width: 100%;
        z-index: 10;
    }

    @media (min-width: 768px) {
        .error-content {
            flex-direction: row;
            text-align: left;
            gap: 3rem;
        }
    }

    /* Ilustração de erro */
    .error-illustration {
        width: 100%;
        max-width: 300px;
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .error-illustration {
            margin-bottom: 0;
        }
    }

    .astronaut {
        width: 100%;
        height: auto;
    }

    .astronaut-figure {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-5px) rotate(2deg); }
        50% { transform: translateY(0) rotate(0deg); }
        75% { transform: translateY(5px) rotate(-2deg); }
    }

    /* Mensagem de erro */
    .error-message-container {
        flex: 1;
        max-width: 500px;
    }

    .error-title {
        font-size: 6rem;
        font-weight: 800;
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        line-height: 1;
        text-align: center;
    }

    @media (min-width: 768px) {
        .error-title {
            font-size: 8rem;
            text-align: left;
        }
    }

    .error-subtitle {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }

    .error-description {
        font-size: 1.1rem;
        color: var(--light-text);
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    /* Botões de ação */
    .error-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    @media (min-width: 480px) {
        .error-actions {
            flex-direction: row;
            justify-content: center;
        }
    }

    @media (min-width: 768px) {
        .error-actions {
            justify-content: flex-start;
        }
    }

    .btn-home, .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-home {
        background-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 6px rgba(52, 144, 220, 0.3);
    }

    .btn-home:hover {
        background-color: #2779bd;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(52, 144, 220, 0.4);
        color: white;
    }

    .btn-back {
        background-color: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-back:hover {
        background-color: rgba(52, 144, 220, 0.1);
        transform: translateY(-2px);
        color: var(--primary-color);
    }

    .btn-home i, .btn-back i {
        margin-right: 0.5rem;
    }

    /* Elementos flutuantes */
    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .floating-elements::before,
    .floating-elements::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: linear-gradient(45deg, rgba(52, 144, 220, 0.1), rgba(149, 97, 226, 0.1));
        animation: drift 15s infinite linear;
    }

    .floating-elements::before {
        top: -150px;
        left: -150px;
    }

    .floating-elements::after {
        bottom: -150px;
        right: -150px;
        animation-delay: -7.5s;
    }

    @keyframes drift {
        0% { transform: translate(0, 0) rotate(0deg); }
        50% { transform: translate(30px, 30px) rotate(180deg); }
        100% { transform: translate(0, 0) rotate(360deg); }
    }

    /* Animação de estrelas */
    .stars circle {
        animation: twinkle 4s infinite;
    }

    .stars circle:nth-child(2) { animation-delay: 0.5s; }
    .stars circle:nth-child(3) { animation-delay: 1s; }
    .stars circle:nth-child(4) { animation-delay: 1.5s; }
    .stars circle:nth-child(5) { animation-delay: 2s; }
    .stars circle:nth-child(6) { animation-delay: 2.5s; }

    @keyframes twinkle {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 1; transform: scale(1.2); }
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.floating-elements');
        const numberOfParticles = 20;
        
        // Criar partículas aleatórias
        for (let i = 0; i < numberOfParticles; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            const size = Math.random() * 8 + 2;
            const posX = Math.random() * 100;
            const posY = Math.random() * 100;
            const opacity = Math.random() * 0.5 + 0.1;
            const duration = Math.random() * 20 + 10;
            const delay = Math.random() * 10;
            
            particle.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                border-radius: 50%;
                background-color: rgba(52, 144, 220, ${opacity});
                left: ${posX}%;
                top: ${posY}%;
                animation: float ${duration}s ease-in-out ${delay}s infinite;
            `;
            
            container.appendChild(particle);
        }
        
        // Animação do astronauta
        const astronaut = document.querySelector('.astronaut-figure');
        setInterval(() => {
            const randomX = (Math.random() - 0.5) * 5;
            const randomY = (Math.random() - 0.5) * 5;
            const randomRotate = (Math.random() - 0.5) * 5;
            
            astronaut.style.transform = `translate(${randomX}px, ${randomY}px) rotate(${randomRotate}deg)`;
        }, 2000);
    });
</script>
@stop