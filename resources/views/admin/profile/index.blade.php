{{-- resources/views/admin/profile/index.blade.php --}}
@extends('adminlte::page')
@section('title', 'Meu Perfil')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="m-0 text-dark">Meu Perfil</h1>
            <ol class="breadcrumb mt-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active">Meu Perfil</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <!-- Notificações com animações -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="d-flex align-items-center">
                <div class="alert-icon-container mr-2">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                </div>
                <div>
                    <h5 class="alert-heading">Sucesso!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="d-flex align-items-center">
                <div class="alert-icon-container mr-2">
                    <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                </div>
                <div>
                    <h5 class="alert-heading">Erro!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <div class="d-flex align-items-center">
                <div class="alert-icon-container mr-2">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                </div>
                <div>
                    <h5 class="alert-heading">Atenção!</h5>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Perfil do Usuário -->
        <div class="col-md-4 mb-4">
            <div class="card card-profile">
                <div class="card-header profile-header">
                    <div class="profile-cover"></div>
                    <div class="profile-avatar-container">
                        <div class="profile-avatar">
                            @if ($user->foto_perfil)
                                <img src="{{ asset($user->foto_perfil) }}" alt="Foto de perfil"
                                    class="img-circle elevation-3">
                            @else
                                <div class="profile-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body text-center pt-5">
                    <h3 class="profile-name">{{ $user->name }}</h3>
                    <p class="profile-role">{{ ucfirst($user->tipo ?? 'Usuário') }}</p>
                    <div class="profile-info">
                        <div class="profile-info-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                        @if ($user->telefone)
                            <div class="profile-info-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $user->telefone }}</span>
                            </div>
                        @endif
                        @if ($user->genero)
                            <div class="profile-info-item">
                                <i class="fas fa-venus-mars"></i>
                                <span>{{ $user->genero }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-center">
                    <p class="text-muted">
                        <small>Membro desde {{ $user->created_at->format('d/m/Y') }}</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulário de Edição -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-3">
                    <ul class="nav nav-tabs card-header-tabs" id="profile-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab"
                                aria-controls="personal" aria-selected="true">
                                <i class="fas fa-user mr-1"></i> Informações Pessoais
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab"
                                aria-controls="security" aria-selected="false">
                                <i class="fas fa-lock mr-1"></i> Segurança
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.perfil.update') }}" enctype="multipart/form-data"
                        id="profile-form">
                        @csrf
                        @method('PUT')

                        <div class="tab-content" id="profile-content">
                            <!-- Informações Pessoais -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                aria-labelledby="personal-tab">
                                <h5 class="text-muted mb-4">Atualize suas informações pessoais</h5>

                                <div class="form-group">
                                    <label for="name">Nome Completo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="Seu nome completo">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        @if ($user->tipo === 'admin')
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                name="email" value="{{ old('email', $user->email) }}"
                                                placeholder="seu.email@exemplo.com">
                                        @else
                                            <input type="email" class="form-control" id="email"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="seu.email@exemplo.com" disabled>
                                            <input type="hidden" name="email"
                                                value="{{ old('email', $user->email) }}">
                                        @endif
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if ($user->tipo !== 'admin')
                                        <small class="form-text text-muted">Apenas administradores podem alterar o
                                            email.</small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                            id="telefone" name="telefone"
                                            value="{{ old('telefone', $user->telefone) }}" placeholder="(00) 00000-0000">
                                        @error('telefone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="genero">Gênero</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                        </div>
                                        <select class="form-control @error('genero') is-invalid @enderror" id="genero"
                                            name="genero">
                                            <option value="">Selecione o gênero</option>
                                            @foreach (['Masculino', 'Feminino', 'Outro'] as $genero)
                                                <option value="{{ $genero }}"
                                                    {{ old('genero', $user->genero) == $genero ? 'selected' : '' }}>
                                                    {{ $genero }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('genero')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="foto_perfil">Foto de Perfil</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('foto_perfil') is-invalid @enderror"
                                            id="foto_perfil" name="foto_perfil">
                                        <label class="custom-file-label" for="foto_perfil">
                                            {{ $user->foto_perfil ? basename($user->foto_perfil) : 'Escolher arquivo' }}
                                        </label>
                                        @error('foto_perfil')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Formatos suportados: JPG, PNG. Tamanho máximo:
                                        2MB.</small>
                                </div>
                            </div>

                            <!-- Segurança -->
                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <h5 class="text-muted mb-4">Atualização de senha</h5>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Preencha os campos abaixo apenas se deseja alterar sua senha atual.
                                </div>

                                <div class="form-group">
                                    <label for="current_password">Senha Atual</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password"
                                            placeholder="Digite sua senha atual">
                                        <div class="input-group-append password-toggle-btn"
                                            data-target="current_password">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Nova Senha</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Digite sua nova senha">
                                        <div class="input-group-append password-toggle-btn" data-target="password">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Mínimo de 8 caracteres, incluindo uma letra
                                        maiúscula, uma minúscula e um número.</small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Nova Senha</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Confirme sua nova senha">
                                        <div class="input-group-append password-toggle-btn"
                                            data-target="password_confirmation">
                                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="password-strength mt-3 d-none" id="password-strength-meter">
                                    <label>Força da senha:</label>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="password-strength-text mt-1"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Variáveis globais */
        :root {
            --primary-color: #007bff;
            --primary-hover: #0069d9;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --white: #ffffff;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
        }

        /* Reset do AdminLTE para estilização personalizada */
        .content-wrapper {
            background-color: #f4f6f9;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        /* Cabeçalho da página */
        .content-header h1 {
            font-weight: 600;
            font-size: 1.75rem;
        }

        /* Alerta personalizado */
        .alert {
            border: none;
            border-radius: 0.5rem;
        }

        .alert-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .alert-success .alert-icon-container {
            background-color: rgba(40, 167, 69, 0.2);
        }

        .alert-danger .alert-icon-container {
            background-color: rgba(220, 53, 69, 0.2);
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .alert-success .alert-icon {
            color: #28a745;
        }

        .alert-danger .alert-icon {
            color: #dc3545;
        }

        .alert-heading {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        /* Cartão de perfil */
        .card-profile {
            overflow: hidden;
        }

        .profile-header {
            position: relative;
            padding: 0;
            height: 120px;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            overflow: hidden;
        }

        .profile-cover {
            background: linear-gradient(135deg, #007bff, #6610f2);
            height: 100%;
            width: 100%;
        }

        .profile-avatar-container {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid var(--white);
            overflow: hidden;
            background-color: var(--gray-200);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--gray-300);
            color: var(--gray-700);
            font-size: 2.5rem;
        }

        .profile-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--gray-800);
        }

        .profile-role {
            color: var(--gray-600);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .profile-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-700);
        }

        .profile-info-item i {
            color: var(--primary-color);
            width: 20px;
        }

        /* Abas */
        .nav-tabs .nav-link {
            color: var(--gray-600);
            border: none;
            border-bottom: 2px solid transparent;
            border-radius: 0;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: color 0.2s, border-color 0.2s;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-color: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
            border-bottom: 2px solid var(--primary-color);
        }

        /* Formulário */
        .form-group label {
            font-weight: 500;
            color: var(--gray-700);
        }

        .input-group-text {
            background-color: var(--gray-100);
            border-color: var(--gray-300);
        }

        .form-control {
            border-color: var(--gray-300);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-text {
            font-size: 0.75rem;
        }

        /* Visualizador de senha */
        .password-toggle-btn {
            cursor: pointer;
        }

        .password-toggle-btn:hover {
            background-color: var(--gray-200);
        }

        /* Medidor de força da senha */
        .password-strength {
            margin-top: 1rem;
        }

        .password-strength .progress {
            height: 8px;
            border-radius: 4px;
        }

        .password-strength .progress-bar {
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .password-strength-weak .progress-bar {
            background-color: var(--danger-color);
        }

        .password-strength-medium .progress-bar {
            background-color: var(--warning-color);
        }

        .password-strength-strong .progress-bar {
            background-color: var(--success-color);
        }

        /* Botões */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.25);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Transições e animações */
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos responsivos */
        @media (max-width: 767.98px) {
            .row {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Atualizar nome do arquivo no input file
            $('#foto_perfil').on('change', function(e) {
                var fileName = e.target.files[0].name;
                $(this).next('.custom-file-label').html(fileName);
                
                // Pré-visualização da imagem
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if ($('.profile-avatar img').length) {
                            $('.profile-avatar img').attr('src', e.target.result);
                        } else {
                            $('.profile-avatar-placeholder').replaceWith('<img src="' + e.target.result + '" class="img-circle elevation-3">');
                        }
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Máscara para telefone
            $('#telefone').on('input', function() {
                var phone = $(this).val().replace(/\D/g, '');
                var formattedPhone = '';

                if (phone.length > 0) {
                    formattedPhone += '(' + phone.substring(0, 2);

                    if (phone.length > 2) {
                        formattedPhone += ') ' + phone.substring(2, 7);

                        if (phone.length > 7) {
                            formattedPhone += '-' + phone.substring(7, 11);
                        }
                    }
                }

                $(this).val(formattedPhone);
            });

            // Toggle de visibilidade da senha
            $('.password-toggle-btn').on('click', function() {
                var targetId = $(this).data('target');
                var passwordInput = $('#' + targetId);
                var icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Medidor de força da senha
            $('#password').on('input', function() {
                var password = $(this).val();

                if (password.length > 0) {
                    $('#password-strength-meter').removeClass('d-none');

                    // Verificar força da senha
                    var strength = 0;
                    var progressBar = $('.password-strength .progress-bar');
                    var strengthText = $('.password-strength-text');

                    // Comprimento
                    if (password.length >= 8) strength += 1;

                    // Letras minúsculas
                    if (password.match(/[a-z]/)) strength += 1;

                    // Letras maiúsculas
                    if (password.match(/[A-Z]/)) strength += 1;

                    // Números
                    if (password.match(/[0-9]/)) strength += 1;

                    // Caracteres especiais
                    if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

                    // Atualizar a visualização
                    var percentage = (strength / 5) * 100;
                    progressBar.css('width', percentage + '%');
                    progressBar.attr('aria-valuenow', percentage);

                    $('.password-strength').removeClass(
                        'password-strength-weak password-strength-medium password-strength-strong');

                    if (strength <= 2) {
                        $('.password-strength').addClass('password-strength-weak');
                        strengthText.text('Fraca');
                        progressBar.css('background-color', '#dc3545');
                    } else if (strength <= 4) {
                        $('.password-strength').addClass('password-strength-medium');
                        strengthText.text('Média');
                        progressBar.css('background-color', '#ffc107');
                    } else {
                        $('.password-strength').addClass('password-strength-strong');
                        strengthText.text('Forte');
                        progressBar.css('background-color', '#28a745');
                    }
                } else {
                    $('#password-strength-meter').addClass('d-none');
                }
            });

            // Validação de formulário ao enviar
            $('#profile-form').on('submit', function(e) {
                var password = $('#password').val();
                var passwordConfirmation = $('#password_confirmation').val();

                if (password && password !== passwordConfirmation) {
                    e.preventDefault();

                    // Criar alerta de erro
                    var errorAlert = $(
                        '<div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        '<div class="d-flex align-items-center">' +
                        '<div class="alert-icon-container mr-2">' +
                        '<span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>' +
                        '</div>' +
                        '<div>' +
                        '<h5 class="alert-heading">Erro!</h5>' +
                        '<p class="mb-0">As senhas não conferem. Por favor, verifique.</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>').insertBefore('#profile-form');

                    // Destacar os campos com erro
                    $('#password, #password_confirmation').addClass('is-invalid');

                    // Scrollar até o topo para mostrar a mensagem
                    $('html, body').animate({
                        scrollTop: 0
                    }, 200);

                    return false;
                }
            });

            // Fechar alertas automaticamente após 5 segundos
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Animar elementos ao carregar a página
            $('.card').addClass('animate__animated animate__fadeIn');
        });
    </script>
@stop
