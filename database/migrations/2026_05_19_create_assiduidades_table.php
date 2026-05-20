<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assiduidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained()->cascadeOnDelete();
            $table->foreignId('turma_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ano_lectivo_id')->nullable()->constrained('anos_lectivos')->nullOnDelete();
            $table->unsignedInteger('mes')->nullable();              // 1-12
            $table->unsignedInteger('aulas_ministradas')->default(0); // quantas aulas o docente registou
            $table->unsignedInteger('faltas')->default(0);             // faltas do estudante
            $table->unsignedInteger('faltas_justificadas')->default(0);
            $table->decimal('percentagem_frequencia', 5, 2)->nullable(); // (aulas_ministradas - faltas) / aulas_ministradas
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['estudante_id', 'turma_id', 'ano_lectivo_id', 'mes'], 'assiduidade_estudante_turma_mes_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assiduidades');
    }
};
