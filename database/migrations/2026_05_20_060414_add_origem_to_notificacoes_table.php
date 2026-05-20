<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona colunas de relacionamento polimórfico (origem) à tabela notificacoes.
     */
    public function up(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('origem_id')->nullable()->after('lida');
            $table->string('origem_type')->nullable()->after('origem_id');
            $table->json('dados_adicionais')->nullable()->after('origem_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            $table->dropColumn(['dados_adicionais', 'origem_type', 'origem_id']);
        });
    }
};
