<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OrdenarMigracoes extends Command
{
    /**
     * O nome e a assinatura do comando Artisan.
     *
     * @var string
     */
    protected $signature = 'migrate:ordered';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Executa as migrações em uma ordem específica';

    /**
     * Ordem correta das migrações.
     */
    protected $migrations = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table',
        '0001_01_01_000002_create_jobs_table',
        '2025_02_21_141755_create_anos_lectivos_table',
        '2025_02_21_141823_create_niveis_table',
        '2025_02_21_135812_create_cursos_table',
        '2025_02_21_145615_create_permission_tables', // Adicione a migration do Spatie/Permission aqui
        '2025_02_21_135415_create_estudantes_table',
        '2025_02_21_135455_create_docentes_table',
        '2025_02_21_135530_create_disciplinas_table',
        '2025_02_21_135555_create_matriculas_table',
        '2025_02_21_135622_create_pagamentos_table',
        '2025_02_21_140257_create_curso_docente_table',
    ];

    /**
     * Executa o comando.
     */
    public function handle()
    {
        $this->info("Iniciando migração na ordem correta...");

        foreach ($this->migrations as $migration) {
            $this->info("Migrando: $migration");
            Artisan::call("migrate --path=database/migrations/{$migration}.php");
            $this->info(Artisan::output());
        }

        $this->info("Migração concluída!");
    }
}