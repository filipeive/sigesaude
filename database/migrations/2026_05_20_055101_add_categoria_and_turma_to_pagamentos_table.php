<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona coluna 'tipo' (categoria do pagamento) e 'turma_id' à tabela pagamentos.
     * Tipo: propina | matricula | taxa | inscricao
     */
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->enum('tipo', ['propina', 'matricula', 'taxa', 'inscricao'])
                ->nullable()
                ->after('referencia')
                ->comment('Categoria do pagamento');
            $table->foreignId('turma_id')
                ->nullable()
                ->after('tipo')
                ->constrained('turmas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn(['turma_id', 'tipo']);
        });
    }
};
