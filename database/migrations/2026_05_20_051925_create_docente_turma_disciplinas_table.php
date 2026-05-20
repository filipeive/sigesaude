<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabela pivot: Vincula um docente a uma ou mais disciplinas em turmas específicas num ano lectivo.
     */
    public function up(): void
    {
        Schema::create('docente_turma_disciplina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['docente_id', 'turma_id', 'disciplina_id', 'ano_lectivo_id'],
                'unique_alocacao'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente_turma_disciplina');
    }
};
