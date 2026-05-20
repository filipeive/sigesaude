<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Média final do estudante em cada disciplina ao final do ano lectivo.
     */
    public function up(): void
    {
        Schema::create('medias_finais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('turma_id')->nullable()->constrained('turmas');
            $table->foreignId('ano_lectivo_id')->nullable()->constrained('anos_lectivos');
            $table->decimal('media_frequencia', 5, 2)->nullable();
            $table->decimal('media_exame', 5, 2)->nullable();
            $table->decimal('media_final', 5, 2)->nullable();
            $table->string('resultado')->nullable(); // Aprovado / Reprovado / Exame
            $table->integer('epoca_nivel')->default(1);
            $table->timestamps();

            $table->unique(['estudante_id', 'disciplina_id', 'ano_lectivo_id', 'epoca_nivel'], 'unique_media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias_finais');
    }
};
