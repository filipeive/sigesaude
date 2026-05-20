# 🏫 Escola dos Visionários — Sistema Integrado de Gestão Escolar

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/AdminLTE-3.x-007bff?style=for-the-badge" alt="AdminLTE">
</p>

## 📋 Descrição

O **Escola dos Visionários** é uma plataforma web completa para a gestão acadêmica e financeira de uma escola secundária. O sistema oferece painéis dedicados para **5 perfis de utilizador** (Administrador, Secretaria, Docente, Financeiro e Estudante), permitindo o gerenciamento de matrículas anuais, propinas mensais, notas e notificações de forma integrada e eficiente.

---

## 🚀 Instalação e Configuração

### Pré-requisitos

- **PHP** >= 8.1
- **Composer** >= 2.x
- **MySQL** / MariaDB
- **Node.js** >= 16.x & NPM

### Passos de Instalação

```bash
# 1. Clonar o repositório
git clone <url-do-repositorio> sigesaude
cd sigesaude

# 2. Instalar dependências PHP
composer install

# 3. Copiar ficheiro de ambiente
cp .env.example .env

# 4. Configurar a base de dados no .env
# DB_DATABASE=institudo_db
# DB_USERNAME=root
# DB_PASSWORD=<sua_senha>

# 5. Criar a base de dados e importar o dump
mysql -u root -p -e "CREATE DATABASE institudo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p institudo_db < institudo_db.sql

# 6. Gerar chave da aplicação
php artisan key:generate

# 7. Criar link simbólico do storage
php artisan storage:link

# 8. Instalar dependências front-end
npm install

# 9. Iniciar o servidor de desenvolvimento
php artisan serve
```

> **Nota:** Se estiver a usar MariaDB, substitua `utf8mb4_0900_ai_ci` por `utf8mb4_unicode_ci` no ficheiro SQL antes de importar.

---

## 🔐 Credenciais de Acesso para Testes

| Perfil | Email | Senha | URL do Painel |
|:---|:---|:---|:---|
| 🛡️ **Administrador** | `admin@example.com` | `password123` | `/admin` |
| 📋 **Secretaria** | `secretaria@example.com` | `password123` | `/secretaria` |
| 👨‍🏫 **Docente** | `docente@example.com` | `password123` | `/docente/dashboard` |
| 💰 **Financeiro** | `financeiro@example.com` | `password123` | `/financeiro` |
| 🎓 **Estudante** | `filipe.dossantos@lifechild.org.za` | `password123` | `/estudante` |

### Fluxo de Login
Após autenticação, o sistema redireciona automaticamente para o painel correto com base no campo `tipo` do utilizador.

---

## 🏗️ Arquitectura do Sistema

### Stack Tecnológica

| Camada | Tecnologia |
|:---|:---|
| **Framework** | Laravel 10.x |
| **Template Engine** | Blade |
| **Admin Panel** | AdminLTE 3.x |
| **Autenticação** | Laravel UI + Spatie Permission |
| **Base de Dados** | MySQL 8.0 / MariaDB |
| **Front-end** | Bootstrap 4, Chart.js, FullCalendar, jQuery |
| **Build Tool** | Vite |

### Estrutura de Módulos

```
app/Http/Controllers/
├── Admin/              → 16 controllers (gestão completa)
│   ├── AdminController         → Dashboard principal
│   ├── EstudanteController     → CRUD estudantes
│   ├── DocenteController       → CRUD docentes
│   ├── CursoController         → CRUD cursos
│   ├── DisciplinaController    → CRUD disciplinas
│   ├── MatriculaController     → Gestão de matrículas
│   ├── InscricaoController     → Inscrições por semestre
│   ├── PagamentoController     → Gestão de pagamentos
│   ├── FinanceiroController    → Relatórios financeiros
│   ├── NotificacoesController  → Notificações em massa
│   └── UserController          → Gestão de utilizadores
├── Docente/            → 6 controllers
│   ├── DocenteController       → Dashboard docente
│   ├── NotasFrequenciaController → Lançamento de notas
│   ├── NotasExamesController   → Notas de exames
│   └── NotificacaoController   → Notificações
├── Estudante/          → 9 controllers
│   ├── EstudanteController     → Dashboard estudante
│   ├── InscricaoController     → Auto-inscrição
│   ├── EstudantePagamentosController → Pagamentos
│   └── NotasFrequenciaController → Consulta de notas
├── Financeiro/         → 1 controller
│   └── FinanceiroController    → Painel financeiro
└── Secretaria/         → 1 controller
    └── SecretariaController    → Painel secretaria
```

### Modelos de Dados (20 modelos)

```
User ──┬── Estudante ──── Curso
       │       ├── Matricula ── Disciplina
       │       ├── Inscricao ── InscricaoDisciplina
       │       ├── Pagamento
       │       ├── NotaFrequencia ── NotaDetalhada
       │       ├── NotaExame
       │       └── MediaFinal
       ├── Docente ──── Disciplina
       │       └── Departamento
       └── Notificacao

AnoLectivo    Nivel    Transacao    RelatorioFinanceiro
```

### Sistema de Permissões

O sistema utiliza **dupla verificação de acesso**:
1. **Middleware `check.tipo`** — verifica o campo `tipo` do utilizador
2. **Spatie Laravel Permission** — gestão de roles e permissões granulares (16 permissões)

**5 Roles:** `admin`, `secretaria`, `docente`, `financeiro`, `estudante`

**16 Permissões CRUD:** Estudantes, Docentes, Disciplinas, Pagamentos (view, create, edit, delete)

---

## 📊 Funcionalidades por Perfil

### 🛡️ Administrador
- ✅ Dashboard com estatísticas (gráficos Chart.js)
- ✅ CRUD completo de estudantes, docentes, cursos e disciplinas
- ✅ Gestão de matrículas e inscrições
- ✅ Gestão de pagamentos com comprovantes
- ✅ Módulo financeiro com relatórios
- ✅ Sistema de notificações em massa
- ✅ Gestão de utilizadores e perfis

### 👨‍🏫 Docente
- ✅ Dashboard com disciplinas e gráficos
- ✅ Visualização de disciplinas atribuídas
- ✅ Lançamento de notas de frequência (testes + trabalhos)
- ✅ Lançamento de notas de exame (normal + recorrência)
- ✅ Envio de notificações aos estudantes
- ✅ Calendário acadêmico

### 🎓 Estudante
- ✅ Dashboard completo com progresso acadêmico
- ✅ Consulta de notas de frequência e exame
- ✅ Auto-inscrição em disciplinas por semestre
- ✅ Visualização de pagamentos e upload de comprovantes
- ✅ Notificações pessoais
- ✅ Calendário acadêmico integrado

### 💰 Financeiro
- ✅ Dashboard financeiro
- ✅ Listagem de pagamentos

### 📋 Secretaria
- ⚠️ Dashboard básico (necessita melhorias)
- ✅ Gestão de matrículas

---

## 🔍 Análise Técnica — Pontos Fortes

1. **Arquitectura modular** — Controllers organizados por perfil de utilizador
2. **Sistema de autenticação robusto** — Middleware dedicado + Spatie Permissions
3. **Dashboard do Estudante muito rico** — Calendário, progresso, atalhos rápidos
4. **Módulo de notas completo** — Frequência, exames, notas detalhadas, médias finais
5. **Gestão financeira** — Pagamentos com comprovantes, referências automáticas
6. **Notificações** — Sistema de notificações segmentadas por tipo

---

## ⚠️ Problemas Identificados e Sugestões de Melhoria

### 🔴 Crítico (Corrigir Antes da Apresentação)

| # | Problema | Localização | Impacto |
|:--|:---|:---|:---|
| 1 | **Ficheiros duplicados/lixo no repositório** — 16 ficheiros `*copy*`, `oscs.php`, ficheiro `.blade.php` dentro de Controllers | Raiz, Controllers, Views | Profissionalismo |
| 2 | **Dashboard Secretaria vazio** — Apenas "Bem-vindo ao painel" sem nenhuma funcionalidade | `secretaria/dashboard.blade.php` | Má impressão na demo |
| 3 | **Secretaria usa layout diferente** — Usa `layouts.app` ao invés de `adminlte::page` | `secretaria/dashboard.blade.php` | Layout inconsistente |
| 4 | **Financeiro sem dashboard próprio** — Reutiliza o controller do Admin | `FinanceiroController.php` | Funcionalidade limitada |
| 5 | **Datas hardcoded nos calendários** — Eventos de 2023 nos dashboards | Docente e Estudante dashboards | Dados desatualizados |
| 6 | **Progresso com `rand()`** — Barra de progresso usa valores aleatórios | Docente dashboard (L124) | Dados incorretos |

### 🟡 Importante (Melhorias de Qualidade)

| # | Sugestão | Descrição |
|:--|:---|:---|
| 7 | **Atividades recentes hardcoded** | O AdminController retorna atividades fictícias (L58-64). Implementar log real de atividades |
| 8 | **Middleware `check.tipo` com `abort(403)` antes do redirect** | O código nunca chega ao redirect (L28) por causa do abort anterior (L25) |
| 9 | **Rota duplicada do docente perfil** | Duas rotas com o mesmo URL `docente/perfil` (L215-216 do web.php) |
| 10 | **Uso misto de `url()` e `route()`** | Algumas views usam `url()` hardcoded ao invés de named routes |
| 11 | **Erros ortográficos nas rotas** | `admin/incricoes` ao invés de `admin/inscricoes` (web.php L94) |
| 12 | **Falta de testes automatizados** | Nenhum teste unitário ou de integração encontrado |

### 🟢 Sugestões UI/UX para Impressionar o Cliente

| # | Melhoria | Descrição | Impacto Visual |
|:--|:---|:---|:---|
| 13 | **Página de Login personalizada** | Adicionar logo do instituto, cores institucionais e imagem de fundo médica | ⭐⭐⭐ |
| 14 | **Tema de cores institucional** | Substituir o azul AdminLTE padrão por um esquema verde/teal (área da saúde) | ⭐⭐⭐ |
| 15 | **Foto de perfil no menu** | Ativar `usermenu_image` e `usermenu_header` no AdminLTE config | ⭐⭐ |
| 16 | **Preloader com logo do instituto** | Substituir o logo padrão AdminLTE pelo logo do instituto | ⭐⭐ |
| 17 | **Dashboard com KPIs reais** | Mostrar taxa de aprovação, inadimplência, ocupação de vagas | ⭐⭐⭐ |
| 18 | **Exportação de relatórios em PDF** | Adicionar geração de boletins, declarações e recibos em PDF | ⭐⭐⭐ |
| 19 | **Modo responsivo mobile** | Testar e otimizar para tablets (secretárias podem usar tablets) | ⭐⭐ |
| 20 | **Loading skeleton** | Adicionar skeleton screens ao invés de spinners genéricos | ⭐ |

---

## 📁 Ficheiros a Limpar (Recomendação)

Os seguintes ficheiros são duplicados/temporários e devem ser removidos antes da apresentação:

```
# Controllers duplicados
app/Http/Controllers/Docente/NotasExamesController copy.php
app/Http/Controllers/Docente/oscs.php
app/Http/Controllers/Estudante/EstudanteController copy.php
app/Http/Controllers/Estudante/notificacoes.blade.php  ← blade dentro de Controllers!

# Views duplicadas
resources/views/docente/disciplinas.blade copy.php
resources/views/admin/docentes copy/               ← pasta inteira
resources/views/admin/pagamentos/index.blade copy.php
resources/views/admin/pagamentos/create.blade copy.php
resources/views/estudante/notificacoes.blade copy.php
resources/views/estudante/dashboard.blade copy.php
resources/views/estudante/create-profile.blade copy.php
resources/views/estudante/pagamentos.blade.copy.php
resources/views/estudante/pagamentos.blade copy.php
resources/views/estudante/inscricoes/index.blade copy.php

# Ficheiros soltos na raiz
ciplina = AppModelsDisciplina::with(...)   ← ficheiros de teste tinker
ciplina);
ciplina->curso->nome;
ciplina->nome . "\n";
curso
curso-
docente
nivel
nome
tst.txt
```

---

## 🗄️ Base de Dados

- **Nome:** `institudo_db`
- **Tabelas:** 35
- **Engine:** InnoDB
- **Charset:** utf8mb4_unicode_ci
- **Moeda:** MZN (Metical Moçambicano)

### Tabelas Principais

| Tabela | Registos | Descrição |
|:---|:---|:---|
| `users` | 18 | Utilizadores do sistema |
| `estudantes` | 7 | Estudantes matriculados |
| `docentes` | 6 | Docentes activos |
| `cursos` | 7 | Cursos disponíveis (Medicina, SMI, Enfermagem...) |
| `disciplinas` | 11 | Disciplinas curriculares |
| `pagamentos` | 48+ | Registos de pagamentos |
| `inscricoes` | 13 | Inscrições por semestre |
| `notas_frequencia` | 12 | Notas de frequência |
| `notas_exame` | 5 | Notas de exame |
| `notificacoes` | 7 | Notificações enviadas |

---

## 📄 Licença

Este projecto é de uso privado para o Instituto de Saúde.

---

## 👨‍💻 Desenvolvedor

**Filipe Domingos dos Santos**

---

> *Documentação gerada em Maio 2026*
