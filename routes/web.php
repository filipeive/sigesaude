<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DisciplinaController;
use App\Http\Controllers\Admin\DocenteController as AdminDocenteController;
use App\Http\Controllers\Admin\EstudanteController as AdminEstudanteController;
use App\Http\Controllers\Admin\FinanceiroController;
use App\Http\Controllers\Admin\MatriculaController;
use App\Http\Controllers\Admin\NotasController;
use App\Http\Controllers\Admin\PagamentoController;
use App\Http\Controllers\Admin\PerfilController as PerfilAdminController;
use App\Http\Controllers\Admin\ProgressoAcademicoController;
use App\Http\Controllers\Admin\TurmaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Estudante\EstudanteController;
use App\Http\Controllers\Estudante\EstudantePagamentosController;
use App\Http\Controllers\Estudante\NotasDetalhadasController;
use App\Http\Controllers\Estudante\NotasExameController;
use App\Http\Controllers\Estudante\NotasFrequenciaController;
use App\Http\Controllers\Estudante\PerfilController as PerfilEstudanteController;
use Illuminate\Support\Facades\Route;

// Rotas Públicas (Autenticação)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ══════════════════════════════════════
// Rotas para Admin
// ══════════════════════════════════════
Route::middleware(['auth', 'check.tipo:admin'])->group(function () {

    // Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Perfil
    Route::get('/admin/perfil', [PerfilAdminController::class, 'index'])->name('admin.perfil');
    Route::put('/admin/perfil', [PerfilAdminController::class, 'updateProfile'])->name('admin.perfil.update');

    // Users
    Route::resource('/admin/users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Estudantes
    Route::prefix('admin/estudantes')->name('admin.estudantes.')->group(function () {
        Route::get('/', [AdminEstudanteController::class, 'index'])->name('index');
        Route::get('/create', [AdminEstudanteController::class, 'create'])->name('create');
        Route::post('/', [AdminEstudanteController::class, 'store'])->name('store');
        Route::get('/{estudante}', [AdminEstudanteController::class, 'show'])->name('show');
        Route::get('/{estudante}/edit', [AdminEstudanteController::class, 'edit'])->name('edit');
        Route::put('/{estudante}', [AdminEstudanteController::class, 'update'])->name('update');
        Route::delete('/{estudante}', [AdminEstudanteController::class, 'destroy'])->name('destroy');
    });

    // Docentes
    Route::prefix('admin/docentes')->name('admin.docentes.')->group(function () {
        Route::get('/', [AdminDocenteController::class, 'index'])->name('index');
        Route::get('/create', [AdminDocenteController::class, 'create'])->name('create');
        Route::post('/', [AdminDocenteController::class, 'store'])->name('store');
        Route::get('/{docente}', [AdminDocenteController::class, 'show'])->name('show');
        Route::get('/{docente}/edit', [AdminDocenteController::class, 'edit'])->name('edit');
        Route::put('/{docente}', [AdminDocenteController::class, 'update'])->name('update');
        Route::delete('/{docente}', [AdminDocenteController::class, 'destroy'])->name('destroy');
    });

    // Cursos
    Route::prefix('admin/cursos')->name('admin.cursos.')->group(function () {
        Route::get('/', [CursoController::class, 'index'])->name('index');
        Route::get('/create', [CursoController::class, 'create'])->name('create');
        Route::post('/', [CursoController::class, 'store'])->name('store');
        Route::get('/{curso}', [CursoController::class, 'show'])->name('show');
        Route::get('/{curso}/edit', [CursoController::class, 'edit'])->name('edit');
        Route::put('/{curso}', [CursoController::class, 'update'])->name('update');
        Route::delete('/{curso}', [CursoController::class, 'destroy'])->name('destroy');
    });

    // Classes (Níveis: 8ª, 9ª, 10ª, 11ª, 12ª)
    Route::prefix('admin/classes')->name('admin.classes.')->group(function () {
        Route::get('/', [ClasseController::class, 'index'])->name('index');
        Route::get('/create', [ClasseController::class, 'create'])->name('create');
        Route::post('/', [ClasseController::class, 'store'])->name('store');
        Route::get('/{classe}', [ClasseController::class, 'show'])->name('show');
        Route::get('/{classe}/edit', [ClasseController::class, 'edit'])->name('edit');
        Route::put('/{classe}', [ClasseController::class, 'update'])->name('update');
        Route::delete('/{classe}', [ClasseController::class, 'destroy'])->name('destroy');
    });

    // Turmas
    Route::prefix('admin/turmas')->name('admin.turmas.')->group(function () {
        Route::get('/', [TurmaController::class, 'index'])->name('index');
        Route::get('/create', [TurmaController::class, 'create'])->name('create');
        Route::post('/', [TurmaController::class, 'store'])->name('store');
        Route::get('/{turma}', [TurmaController::class, 'show'])->name('show');
        Route::get('/{turma}/edit', [TurmaController::class, 'edit'])->name('edit');
        Route::put('/{turma}', [TurmaController::class, 'update'])->name('update');
        Route::delete('/{turma}', [TurmaController::class, 'destroy'])->name('destroy');
    });

    // Disciplinas
    Route::prefix('admin/disciplinas')->name('admin.disciplinas.')->group(function () {
        Route::get('/', [DisciplinaController::class, 'index'])->name('index');
        Route::get('/create', [DisciplinaController::class, 'create'])->name('create');
        Route::post('/', [DisciplinaController::class, 'store'])->name('store');
        Route::get('/{id}', [DisciplinaController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DisciplinaController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DisciplinaController::class, 'update'])->name('update');
        Route::delete('/{id}', [DisciplinaController::class, 'destroy'])->name('destroy');
    });

    // ── GESTÃO DE NOTAS ────────────────────────────────────────────────────
    Route::prefix('admin/notas')->name('admin.notas.')->group(function () {
        Route::get('/', [NotasController::class, 'index'])->name('index');
        Route::get('/create', [NotasController::class, 'create'])->name('create');
        Route::post('/', [NotasController::class, 'store'])->name('store');
        Route::get('/ver', [NotasController::class, 'show'])->name('show');
        Route::get('/frequencia/{id}/edit', [NotasController::class, 'editFrequencia'])->name('edit_frequencia');
        Route::put('/frequencia/{notaFrequencia}', [NotasController::class, 'updateFrequencia'])->name('update_frequencia');
        Route::get('/exame/{id}/edit', [NotasController::class, 'editExame'])->name('edit_exame');
        Route::put('/exame/{notaExame}', [NotasController::class, 'updateExame'])->name('update_exame');
        Route::post('/calcular-medias', [NotasController::class, 'calcularMedias'])->name('calcular_medias');
        Route::get('/boletim', [NotasController::class, 'pdfBoletim'])->name('pdf_boletim');
        Route::delete('/frequencia/{notaFrequencia}', [NotasController::class, 'destroyFrequencia'])->name('destroy_frequencia');
        Route::delete('/exame/{notaExame}', [NotasController::class, 'destroyExame'])->name('destroy_exame');
    });

    // ── PROGRESSO ACADÊMICO ────────────────────────────────────────────────
    Route::prefix('admin/progresso-academico')->name('admin.progresso_academico.')->group(function () {
        Route::get('/', [ProgressoAcademicoController::class, 'index'])->name('index');
        Route::get('/turma/{turma}', [ProgressoAcademicoController::class, 'porTurma'])->name('turma');
        Route::get('/disciplina/{disciplina}', [ProgressoAcademicoController::class, 'porDisciplina'])->name('disciplina');
    });

    // Matrículas
    Route::prefix('admin/matriculas')->name('admin.matriculas.')->group(function () {
        Route::get('/', [MatriculaController::class, 'index'])->name('index');
        Route::get('/create', [MatriculaController::class, 'create'])->name('create');
        Route::post('/', [MatriculaController::class, 'store'])->name('store');
        Route::get('/{matricula}', [MatriculaController::class, 'show'])->name('show');
        Route::get('/{matricula}/edit', [MatriculaController::class, 'edit'])->name('edit');
        Route::put('/{matricula}', [MatriculaController::class, 'update'])->name('update');
        Route::delete('/{matricula}', [MatriculaController::class, 'destroy'])->name('destroy');
        Route::get('/{matricula}/pdf', [MatriculaController::class, 'downloadPdf'])->name('pdf');
        Route::post('/{matricula}/confirmar', [MatriculaController::class, 'confirmar'])->name('confirmar');
        Route::post('/{matricula}/comprovativo', [MatriculaController::class, 'uploadComprovativo'])->name('comprovativo');
    });

    // Pré-Inscrições (Admin)
    Route::get('admin/pre-inscricoes', [App\Http\Controllers\PreInscricaoController::class, 'index'])->name('admin.pre-inscricoes.index');

    // Pagamentos
    Route::prefix('admin/pagamentos')->name('admin.pagamentos.')->group(function () {
        Route::get('/', [PagamentoController::class, 'index'])->name('index');
        Route::get('/create', [PagamentoController::class, 'create'])->name('create');
        Route::post('/', [PagamentoController::class, 'store'])->name('store');
        Route::get('/{pagamento}', [PagamentoController::class, 'show'])->name('show');
        Route::get('/{pagamento}/edit', [PagamentoController::class, 'edit'])->name('edit');
        Route::put('/{pagamento}', [PagamentoController::class, 'update'])->name('update');
        Route::delete('/{pagamento}', [PagamentoController::class, 'destroy'])->name('destroy');
        Route::put('/{pagamento}/status', [PagamentoController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/exportar', [PagamentoController::class, 'exportar'])->name('exportar');
        Route::get('/{pagamento}/recibo', [PagamentoController::class, 'downloadRecibo'])->name('recibo');
    });

    // Financeiro
    Route::prefix('admin/financeiro')->name('admin.financeiro.')->group(function () {
        Route::get('/', [FinanceiroController::class, 'index'])->name('index');
        Route::get('/create', [FinanceiroController::class, 'create'])->name('create');
        Route::post('/', [FinanceiroController::class, 'store'])->name('store');
        Route::get('/relatorios', [FinanceiroController::class, 'relatorios'])->name('relatorios');
        Route::post('/relatorios/gerar', [FinanceiroController::class, 'gerarRelatorio'])->name('relatorios.gerar');
        Route::get('/relatorios/{id}', [FinanceiroController::class, 'showRelatorio'])->name('relatorios.show');
        Route::get('/relatorios/{id}/download', [FinanceiroController::class, 'downloadRelatorio'])->name('relatorios.download');
        Route::delete('/relatorios/{id}', [FinanceiroController::class, 'destroyRelatorio'])->name('relatorios.destroy');
        Route::get('/configuracoes', [FinanceiroController::class, 'configuracoes'])->name('configuracoes');
        Route::put('/configuracoes', [FinanceiroController::class, 'atualizarConfiguracoes'])->name('configuracoes.update');
        Route::get('/ajax-grafico', [FinanceiroController::class, 'ajaxGrafico'])->name('ajax.grafico');
        Route::get('/{id}', [FinanceiroController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FinanceiroController::class, 'edit'])->name('edit');
        Route::put('/{id}', [FinanceiroController::class, 'update'])->name('update');
        Route::delete('/{id}', [FinanceiroController::class, 'destroy'])->name('destroy');
    });

    // Notificações
    Route::prefix('admin/notificacoes')->name('admin.notificacoes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NotificacoesController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\NotificacoesController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\NotificacoesController::class, 'store'])->name('store');
        Route::delete('/{id}', [App\Http\Controllers\Admin\NotificacoesController::class, 'destroy'])->name('destroy');
        Route::post('destroy-multiple', [App\Http\Controllers\Admin\NotificacoesController::class, 'destroyMultiple'])->name('destroy-multiple');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\NotificacoesController::class, 'edit'])->name('edit');
    });

    // Anos Lectivos
    Route::resource('admin/anos-lectivos', App\Http\Controllers\Admin\AnoLectivoController::class)->names([
        'index' => 'admin.anos-lectivos.index',
        'create' => 'admin.anos-lectivos.create',
        'store' => 'admin.anos-lectivos.store',
        'show' => 'admin.anos-lectivos.show',
        'edit' => 'admin.anos-lectivos.edit',
        'update' => 'admin.anos-lectivos.update',
        'destroy' => 'admin.anos-lectivos.destroy',
    ]);
});

// ══════════════════════════════════════
// Rotas para Estudantes
// ══════════════════════════════════════
Route::middleware(['auth', 'check.tipo:estudante'])->group(function () {
    Route::get('/estudante', [EstudanteController::class, 'index'])->name('estudante.dashboard');
    Route::get('/create-profile', [EstudanteController::class, 'createProfile'])->name('estudante.create.profile');
    Route::post('/store-profile', [EstudanteController::class, 'storeProfile'])->name('estudante.store.profile');
    Route::get('/estudante/perfil', [PerfilEstudanteController::class, 'index'])->name('estudante.perfil.index');
    Route::put('/estudante/perfil', [PerfilEstudanteController::class, 'update'])->name('estudante.perfil.update');
    Route::get('/estudante/matriculas', [EstudanteController::class, 'matriculas'])->name('estudante.matriculas');
    Route::get('/estudante/pagamentos', [EstudantePagamentosController::class, 'pagamentos'])->name('estudante.pagamentos');
    Route::get('/estudante/pagamentos/{pagamento}', [EstudantePagamentosController::class, 'show'])->name('estudante.pagamentos.show');
    Route::post('/estudante/pagamentos/registrar', [EstudantePagamentosController::class, 'registrarPagamento'])->name('estudante.registrar.pagamento');
    Route::get('/estudante/pagamentos/{pagamento}/recibo', [EstudantePagamentosController::class, 'downloadRecibo'])->name('estudante.pagamentos.recibo');

    Route::prefix('estudante/notas')->group(function () {
        Route::get('/frequencia', [NotasFrequenciaController::class, 'notasFrequencia'])->name('estudante.notas_frequencia.notas');
        Route::get('/frequencia/{id}/detalhes', [NotasDetalhadasController::class, 'index'])->name('estudante.notas_detalhadas.index');
        Route::post('/detalhes', [NotasDetalhadasController::class, 'store'])->name('estudante.notas_detalhadas.store');
        Route::get('/exame', [NotasExameController::class, 'index'])->name('estudante.notas_exame.index');
        Route::get('/exame/notas', [NotasExameController::class, 'notasExame'])->name('estudante.notas_exame.notas');
    });

    Route::get('/estudante/relatorios', [EstudanteController::class, 'relatorios'])->name('estudante.relatorios');
    Route::get('/estudante/notificacoes', [EstudanteController::class, 'notificacoes'])->name('estudante.notificacoes');
    Route::post('/notificacoes/{id}/marcar-lida', [EstudanteController::class, 'marcarComoLida'])->name('estudante.notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [EstudanteController::class, 'marcarTodasComoLidas'])->name('estudante.notificacoes.marcar-todas-lidas');
    Route::get('/estudante/configuracoes', [EstudanteController::class, 'configuracoes'])->name('estudante.configuracoes');
});

// ══════════════════════════════════════
// Rotas para Docentes
// ══════════════════════════════════════
Route::middleware(['auth', 'check.tipo:docente'])->prefix('docente')->group(function () {

    // Perfil
    Route::get('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'index'])->name('docente.perfil');
    Route::get('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'index'])->name('docente.perfil.index');
    Route::put('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'update'])->name('docente.perfil.update');
    Route::get('/perfil/criar', [App\Http\Controllers\Docente\DocenteController::class, 'createProfile'])->name('docente.profile.create');
    Route::post('/perfil/store', [App\Http\Controllers\Docente\DocenteController::class, 'storeProfile'])->name('docente.profile.store');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Docente\DocenteController::class, 'index'])->name('docente.dashboard');

    // Disciplinas Lecionadas
    Route::get('/disciplinas', [App\Http\Controllers\Docente\DocenteController::class, 'disciplinas'])->name('docente.disciplinas');
    Route::get('/disciplinas/{id}', [App\Http\Controllers\Docente\DocenteController::class, 'show'])->name('docente.disciplina');
    Route::get('/turmas', [App\Http\Controllers\Docente\DocenteController::class, 'turmas'])->name('docente.turmas');

    // Notas de Frequência
    Route::get('/notas-frequencia', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'index'])->name('docente.notas_frequencia.index');
    Route::get('/notas-frequencia/{disciplina}', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'show'])->name('docente.notas_frequencia.show');
    Route::post('/notas-frequencia/{disciplina}/store', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'store'])->name('docente.notas_frequencia.store');
    Route::get('/notas-frequencia/exportar', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'exportar'])->name('docente.notas_frequencia.export');

    // Notas de Exames
    Route::get('/notas-exames', [App\Http\Controllers\Docente\NotasExamesController::class, 'index'])->name('docente.notas_exames.index');
    Route::get('/notas-exames/{disciplina}', [App\Http\Controllers\Docente\NotasExamesController::class, 'show'])->name('docente.notas_exames.show');
    Route::post('/notas-exames/salvar', [App\Http\Controllers\Docente\NotasExamesController::class, 'salvar'])->name('docente.notas_exames.salvar');

    // Notificações
    Route::get('/notificacoes', [App\Http\Controllers\Docente\NotificacaoController::class, 'index'])->name('docente.notificacoes.index');
    Route::get('/notificacoes/enviar', [App\Http\Controllers\Docente\NotificacaoController::class, 'create'])->name('docente.notificacoes.create');
    Route::post('/notificacoes/enviar', [App\Http\Controllers\Docente\NotificacaoController::class, 'store'])->name('docente.notificacoes.store');
    Route::post('/notificacoes/{id}/marcar-como-lida', [App\Http\Controllers\Docente\NotificacaoController::class, 'marcarComoLida'])->name('docente.notificacoes.marcar_como_lida');
    Route::post('/notificacoes/marcar-todas-como-lidas', [App\Http\Controllers\Docente\NotificacaoController::class, 'marcarTodasComoLidas'])->name('docente.notificacoes.marcar_todas_como_lidas');
    Route::delete('/notificacoes/{id}', [App\Http\Controllers\Docente\NotificacaoController::class, 'excluir'])->name('docente.notificacoes.excluir');
    Route::get('/notificacoes/contador', [App\Http\Controllers\Docente\NotificacaoController::class, 'contadorNaoLidas'])->name('docente.notificacoes.contador');
    Route::get('/notificacoes/filtrar', [App\Http\Controllers\Docente\NotificacaoController::class, 'filtrar'])->name('docente.notificacoes.filtrar');

    // Configurações
});

// ── Rotas Públicas ────────────────────────────────────────────────────────
Route::get('/', function () {
    $classes = \App\Models\Classe::orderBy('nivel')->get();

    return view('welcome', compact('classes'));
});

Route::post('/pre-inscricao', [App\Http\Controllers\PreInscricaoController::class, 'store'])->name('pre-inscricao.store');
Route::get('/pre-inscricao/sucesso/{codigo}', [App\Http\Controllers\PreInscricaoController::class, 'sucesso'])->name('pre-inscricao.sucesso');
Route::get('/pre-inscricao/pdf/{codigo}', [App\Http\Controllers\PreInscricaoController::class, 'downloadPdf'])->name('pre-inscricao.pdf');

Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/')->with('success', 'Sessão terminada com sucesso.');
})->name('logout');

Route::fallback(function () {
    return view('errors.404');
})->name('fallback');
