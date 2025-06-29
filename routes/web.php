<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PerfilController as PerfilAdminController;
use App\Http\Controllers\Admin\EstudanteController as AdminEstudanteController;
use App\Http\Controllers\Admin\DocenteController as AdminDocenteController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\DisciplinaController;
use App\Http\Controllers\Admin\MatriculaController;
use App\Http\Controllers\Admin\InscricaoController;
use App\Http\Controllers\Admin\PagamentoController;
use App\Http\Controllers\Estudante\EstudanteController;
use App\Http\Controllers\Estudante\PerfilController as PerfilEstudanteController;
use App\Http\Controllers\Docente\DocenteController;
use App\Http\Controllers\Secretaria\SecretariaController;
use App\Http\Controllers\Admin\FinanceiroController;
use App\Http\Controllers\Admin\UserController as AdminUsersController;
use App\Http\Controllers\Docente\PerfilController as PerfilDocenteController;
use App\Http\Controllers\Estudante\NotasFrequenciaController;
use App\Http\Controllers\Estudante\NotasExameController;
use App\Http\Controllers\Estudante\NotasDetalhadasController;
use App\Http\Controllers\Estudante\InscricaoController as InscricaoEstudanteController;



// Rotas Públicas (Autenticação)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rotas para Admin
Route::middleware(['auth', 'check.tipo:admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/perfil', [PerfilAdminController::class, 'index'])->name('admin.perfil');
    Route::put('/admin/perfil', [PerfilAdminController::class, 'updateProfile'])->name('admin.perfil.update');
    Route::resource('/admin/users', AdminUsersController::class);

    // Estudantes
    Route::prefix('admin/estudantes')->group(function () {
        Route::get('/', [AdminEstudanteController::class, 'index'])->name('admin.estudantes.index');
        Route::get('/create', [AdminEstudanteController::class, 'create'])->name('admin.estudantes.create');
        Route::post('/', [AdminEstudanteController::class, 'store'])->name('admin.estudantes.store');
        Route::get('/{id}', [AdminEstudanteController::class, 'show'])->name('admin.estudantes.show');
        Route::get('/{id}/edit', [AdminEstudanteController::class, 'edit'])->name('admin.estudantes.edit');
        Route::put('/{id}', [AdminEstudanteController::class, 'update'])->name('admin.estudantes.update');
        Route::delete('/{id}', [AdminEstudanteController::class, 'destroy'])->name('admin.estudantes.destroy');
    });

    // Docentes
    Route::prefix('admin/docentes')->group(function () {
        Route::get('/', [AdminDocenteController::class, 'index'])->name('admin.docentes.index');
        Route::get('/create', [AdminDocenteController::class, 'create'])->name('admin.docentes.create');
        Route::post('/', [AdminDocenteController::class, 'store'])->name('admin.docentes.store');
        Route::get('/{id}', [AdminDocenteController::class, 'show'])->name('admin.docentes.show');
        Route::get('/{id}/edit', [AdminDocenteController::class, 'edit'])->name('admin.docentes.edit');
        Route::put('/{id}', [AdminDocenteController::class, 'update'])->name('admin.docentes.update');
        Route::delete('/{id}', [AdminDocenteController::class, 'destroy'])->name('admin.docentes.destroy');
    });

    // Cursos
    Route::prefix('admin/cursos')->group(function () {
        Route::get('/', [CursoController::class, 'index'])->name('admin.cursos.index');
        Route::get('/create', [CursoController::class, 'create'])->name('admin.cursos.create');
        Route::post('/', [CursoController::class, 'store'])->name('admin.cursos.store');
        Route::get('/{id}', [CursoController::class, 'show'])->name('admin.cursos.show');
        Route::get('/{id}/edit', [CursoController::class, 'edit'])->name('admin.cursos.edit');
        Route::put('/{id}', [CursoController::class, 'update'])->name('admin.cursos.update');
        Route::delete('/{id}', [CursoController::class, 'destroy'])->name('admin.cursos.destroy');
    });

    // Disciplinas
    Route::prefix('admin/disciplinas')->group(function () {
        Route::get('/', [DisciplinaController::class, 'index'])->name('admin.disciplinas.index');
        Route::get('/create', [DisciplinaController::class, 'create'])->name('admin.disciplinas.create');
        Route::post('/', [DisciplinaController::class, 'store'])->name('admin.disciplinas.store');
        Route::get('/{id}', [DisciplinaController::class, 'show'])->name('admin.disciplinas.show');
        Route::get('/{id}/edit', [DisciplinaController::class, 'edit'])->name('admin.disciplinas.edit');
        Route::put('/{id}', [DisciplinaController::class, 'update'])->name('admin.disciplinas.update');
        Route::delete('/{id}', [DisciplinaController::class, 'destroy'])->name('admin.disciplinas.destroy');
    });

    // Matrículas
    Route::prefix('admin/matriculas')->group(function () {
        Route::get('/', [MatriculaController::class, 'index'])->name('admin.matriculas.index');
        Route::get('/create', [MatriculaController::class, 'create'])->name('admin.matriculas.create');
        Route::post('/', [MatriculaController::class, 'store'])->name('admin.matriculas.store');
        Route::get('/{id}', [MatriculaController::class, 'show'])->name('admin.matriculas.show');
        Route::get('/{id}/edit', [MatriculaController::class, 'edit'])->name('admin.matriculas.edit');
        Route::put('/{id}', [MatriculaController::class, 'update'])->name('admin.matriculas.update');
        Route::delete('/{id}', [MatriculaController::class, 'destroy'])->name('admin.matriculas.destroy');
    });
    //incricoes
    Route::prefix('admin/incricoes')->group(function () {
        Route::get('/', [InscricaoController::class, 'index'])->name('admin.inscricoes.index');
        Route::get('/create', [InscricaoController::class, 'create'])->name('admin.inscricoes.create');
        Route::get('/edit', [InscricaoController::class, 'create'])->name('admin.inscricoes.edit');
    Route::post('/', [InscricaoController::class, 'store'])->name('admin.inscricoes.store');
        Route::get('/show/{id}', [InscricaoController::class, 'show'])->name('admin.inscricoes.show');
        Route::post('inscricoes/{id}/aprovar',[InscricaoController::class, 'aprovar'])->name('admin.inscricoes.aprovar');
        Route::post('inscricoes/{id}/recusar', [InscricaoController::class,'recusar'])->name('admin.inscricoes.recusar');
    });

    // Pagamentos
    Route::prefix('admin/pagamentos')->name('admin.pagamentos.')->group(function () {
        // Rota para listar pagamentos (index)
        Route::get('/', [PagamentoController::class, 'index'])->name('index');

        // Rota para exibir o formulário de criação (create)
        Route::get('/create', [PagamentoController::class, 'create'])->name('create');

        // Rota para salvar um novo pagamento (store)
        Route::post('/', [PagamentoController::class, 'store'])->name('store');

        // Rota para exibir detalhes de um pagamento (show)
        Route::get('/{pagamento}', [PagamentoController::class, 'show'])->name('show');

        // Rota para exibir o formulário de edição (edit)
        Route::get('/{pagamento}/edit', [PagamentoController::class, 'edit'])->name('edit');

        // Rota para atualizar um pagamento (update)
        Route::put('/{pagamento}', [PagamentoController::class, 'update'])->name('update');

        // Rota para excluir um pagamento (destroy)
        Route::delete('/{pagamento}', [PagamentoController::class, 'destroy'])->name('destroy');

        // Rota para atualizar o status de um pagamento (updateStatus)
        Route::put('/{pagamento}/status', [PagamentoController::class, 'updateStatus'])->name('updateStatus');
        // Rota para exportar pagamentos (exportar)
        Route::get('/exportar', [PagamentoController::class, 'exportar'])->name('exportar');
    });

    // Financeiro
    Route::prefix('admin/financeiro')->group(function () {
        Route::get('/', [FinanceiroController::class, 'index'])->name('admin.financeiro.index');
        Route::get('/create', [FinanceiroController::class, 'create'])->name('admin.financeiro.create');
        Route::post('/', [FinanceiroController::class, 'store'])->name('admin.financeiro.store');
        Route::get('/{id}', [FinanceiroController::class, 'show'])->name('admin.financeiro.show');
        Route::get('/{id}/edit', [FinanceiroController::class, 'edit'])->name('admin.financeiro.edit');
        Route::put('/{id}', [FinanceiroController::class, 'update'])->name('admin.financeiro.update');
        Route::delete('/{id}', [FinanceiroController::class, 'destroy'])->name('admin.financeiro.destroy');
        //relatorio admin.financeiro.relatorios
        Route::get('/relatorios', [FinanceiroController::class, 'relatorios'])->name('admin.financeiro.relatorios');
        // admin.financeiro.configuracoes
        Route::get('/configuracoes', [FinanceiroController::class, 'configuracoes'])->name('admin.financeiro.configuracoes');
    });
    // Notificações
    Route::prefix('admin/notificacoes')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NotificacoesController::class, 'index'])->name('admin.notificacoes.index');
        Route::get('/create', [App\Http\Controllers\Admin\NotificacoesController::class, 'create'])->name('admin.notificacoes.create');
        Route::post('/', [App\Http\Controllers\Admin\NotificacoesController::class, 'store'])->name('admin.notificacoes.store');
        Route::delete('/{id}', [App\Http\Controllers\Admin\NotificacoesController::class, 'destroy'])->name('admin.notificacoes.destroy');
        Route::post('notificacoes/destroy-multiple', [App\Http\Controllers\Admin\NotificacoesController::class, 'destroyMultiple'])
        ->name('admin.notificacoes.destroy-multiple');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\NotificacoesController::class, 'edit'])->name('admin.notificacoes.edit');
    });
});

    // Rotas para Estudantes
    Route::middleware(['auth', 'check.tipo:estudante'])->group(function () {
    // Dashboard e perfil
    Route::get('/estudante', [EstudanteController::class, 'index'])->name('estudante.dashboard');
    Route::get('/create-profile', [EstudanteController::class, 'createProfile'])->name('estudante.create.profile');
    Route::post('/store-profile', [EstudanteController::class, 'storeProfile'])->name('estudante.store.profile');
    Route::get('/estudante/perfil', [PerfilEstudanteController::class, 'index'])->name('estudante.perfil.index');
    Route::put('/estudante/perfil', [PerfilEstudanteController::class, 'update'])->name('estudante.perfil.update');
    // Matrículas
    Route::get('/estudante/matriculas', [EstudanteController::class, 'matriculas'])->name('estudante.matriculas');
    // Inscrições
    Route::prefix('estudante/inscricoes')->group(function () {
        Route::get('/', [InscricaoEstudanteController::class, 'index'])->name('estudante.inscricoes.index');
        Route::get('/create', [InscricaoEstudanteController::class, 'create'])->name('estudante.inscricoes.create');
        Route::post('/', [InscricaoEstudanteController::class, 'store'])->name('estudante.inscricoes.store');
        Route::get('/{id}/edit', [InscricaoEstudanteController::class, 'edit'])->name('estudante.inscricoes.edit');
        Route::put('/{id}', [InscricaoEstudanteController::class, 'update'])->name('estudante.inscricoes.update');
        Route::get('/{id}', [InscricaoEstudanteController::class, 'show'])->name('estudante.inscricoes.show');
        Route::delete('/{id}', [InscricaoEstudanteController::class, 'destroy'])->name('estudante.inscricoes.destroy');
        Route::post('/estudante/inscricoes/{id}/cancelar', [InscricaoEstudanteController::class, 'cancelar'])->name('estudante.inscricoes.cancelar');
    });
    // Pagamentos
    //Route::get('/estudante/pagamentos', [EstudanteController::class, 'pagamentos'])->name('estudante.pagamentos');
     // Nova rota específica para pagamentos com filtro de ano letivo
     Route::get('/estudante/pagamentos', [App\Http\Controllers\Estudante\EstudantePagamentosController::class, 'pagamentos'])->name('estudante.pagamentos');
     Route::get('/estudante/pagamentos/{id}', [App\Http\Controllers\Estudante\PagamentoController::class, 'show'])
    ->name('estudante.pagamentos.show');

     // Rota para registrar comprovante de pagamento
     Route::post('/estudante/pagamentos/registrar', [App\Http\Controllers\Estudante\EstudantePagamentosController::class, 'registrarPagamento'])->name('estudante.registrar.pagamento');
    // Relatórios
    Route::get('/estudante/relatorios', [EstudanteController::class, 'relatorios'])->name('estudante.relatorios');

    // Notificações
    Route::get('/estudante/notificacoes', [EstudanteController::class, 'notificacoes'])->name('estudante.notificacoes');
    Route::get('/notificacoes', [EstudanteController::class, 'notificacoes'])->name('notificacoes');
    Route::post('/notificacoes/{id}/marcar-lida', [EstudanteController::class, 'marcarComoLida'])->name('estudante.notificacoes.marcar-lida');
    Route::post('/notificacoes/marcar-todas-lidas', [EstudanteController::class, 'marcarTodasComoLidas'])->name('estudante.notificacoes.marcar-todas-lidas');
    // Configurações
    Route::get('/estudante/configuracoes', [EstudanteController::class, 'configuracoes'])->name('estudante.configuracoes');

    // Notas e Frequência
    Route::prefix('estudante/notas')->group(function () {
        //Route::get('/frequencia', [NotasFrequenciaController::class, 'index'])->name('estudante.notas_frequencia.index');
        Route::get('/frequencia', [NotasFrequenciaController::class, 'notasFrequencia'])->name('estudante.notas_frequencia.notas');
        Route::get('/frequencia/{id}/detalhes', [NotasDetalhadasController::class, 'index'])->name('estudante.notas_detalhadas.index');
        Route::post('/detalhes', [NotasDetalhadasController::class, 'store'])->name('estudante.notas_detalhadas.store');
        Route::get('/exame', [NotasExameController::class, 'index'])->name('estudante.notas_exame.index');
        Route::get('/exame/notas', [NotasExameController::class, 'notasExame'])->name('estudante.notas_exame.notas');
    });
});


// Rotas para Docentes
Route::middleware(['auth', 'check.tipo:docente'])->prefix('docente')->group(function () {
    //profile
    Route::get('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'index'])->name('docente.perfil');
    Route::get('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'index'])->name('docente.perfil.index');
    Route::put('/perfil', [App\Http\Controllers\Docente\PerfilDocenteController::class, 'update'])->name('docente.perfil.update');
    Route::get('/perfil/criar', [DocenteController::class, 'createProfile'])
        ->name('docente.profile.create');
    Route::post('/perfil/store', [DocenteController::class, 'storeProfile'])
        ->name('docente.profile.store');
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Docente\DocenteController::class, 'index'])->name('docente.dashboard');    
    
    // Disciplinas
    Route::get('/disciplinas', [App\Http\Controllers\Docente\DocenteController::class, 'disciplinas'])->name('docente.disciplinas');
    Route::get('/disciplinas/{id}', [App\Http\Controllers\Docente\DocenteController::class, 'show'])->name('docente.disciplina');
    
    // Notas de Frequência
    Route::get('/notas-frequencia', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'index'])->name('docente.notas_frequencia.index');
    Route::get('/notas-frequencia/{disciplina}', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'show'])->name('docente.notas_frequencia.show');
    Route::post('/notas-frequencia/{disciplina}/store', [App\Http\Controllers\Docente\NotasFrequenciaController::class, 'store'])->name('docente.notas_frequencia.store');
    //notas export
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
    Route::post('/notificacoes/marcar-todas-como-lidas', [NotificacaoController::class, 'marcarTodasComoLidas'])->name('docente.notificacoes.marcar_todas_como_lidas');
    Route::delete('/notificacoes/{id}', [NotificacaoController::class, 'excluir'])->name('docente.notificacoes.excluir');
    Route::get('/notificacoes/contador', [NotificacaoController::class, 'contadorNaoLidas'])->name('docente.notificacoes.contador');
    Route::get('/notificacoes/filtrar', [NotificacaoController::class, 'filtrar'])->name('docente.notificacoes.filtrar');

    // Configurações
   // Route::get('/perfil', [DocenteController::class, 'perfil'])->name('docente.perfil');
});

// Rotas para Secretaria
Route::middleware(['auth', 'check.tipo:secretaria'])->group(function () {
    Route::get('/secretaria', [SecretariaController::class, 'index'])->name('secretaria.dashboard');
    //Route::resource('estudantes', EstudanteController::class);
    Route::resource('matriculas', MatriculaController::class);
    Route::get('/secretaria/pagamentos', [SecretariaController::class, 'pagamentos'])->name('secretaria.pagamentos');
    Route::get('/secretaria/relatorios', [SecretariaController::class, 'relatorios'])->name('secretaria.relatorios');
    Route::get('/secretaria/notificacoes', [SecretariaController::class, 'notificacoes'])->name('secretaria.notificacoes');
    Route::get('/secretaria/configuracoes', [SecretariaController::class, 'configuracoes'])->name('secretaria.configuracoes');
});

// Rotas para Financeiro
Route::middleware(['auth', 'check.tipo:financeiro'])->group(function () {
    Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.dashboard');
    Route::get('/financeiro/pagamentos', [FinanceiroController::class, 'pagamentos'])->name('financeiro.pagamentos');
});

// Rota Home (Após Login)
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});
//logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Página de erro 404 (Not Found) e 500 (Internal Server Error) fallback
Route::fallback(function () {
    return view('errors.404');
})->name('fallback');