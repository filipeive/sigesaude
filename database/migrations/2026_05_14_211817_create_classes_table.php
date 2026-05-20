<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar tabela de Classes (níveis/graus escolares)
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');           // Ex: "10ª Classe"
            $table->integer('nivel');          // Ex: 10
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // 2. Atualizar turmas: adicionar classe_id e ano_lectivo_id
        Schema::table('turmas', function (Blueprint $table) {
            $table->foreignId('classe_id')->nullable()->after('id')
                  ->constrained('classes')->onDelete('set null');
            $table->foreignId('ano_lectivo_id')->nullable()->after('classe_id')
                  ->constrained('anos_lectivos')->onDelete('set null');
        });

        // 3. Atualizar disciplinas: trocar turma_id por classe_id
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn('turma_id');
        });

        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreignId('classe_id')->nullable()->after('docente_id')
                  ->constrained('classes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign(['classe_id']);
            $table->dropColumn('classe_id');
        });

        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()
                  ->constrained('turmas')->onDelete('set null');
        });

        Schema::table('turmas', function (Blueprint $table) {
            $table->dropForeign(['classe_id']);
            $table->dropForeign(['ano_lectivo_id']);
            $table->dropColumn(['classe_id', 'ano_lectivo_id']);
        });

        Schema::dropIfExists('classes');
    }
};
