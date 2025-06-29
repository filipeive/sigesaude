<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacoesTable extends Migration
{
    public function up()
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('mensagem');
            $table->enum('tipo', [
                'academico',
                'avaliacao',
                'exame',
                'presenca',
                'financeiro',
                'administrativo',
                'geral'
            ]);
            $table->string('icone')->nullable();
            $table->string('cor')->nullable();
            $table->string('link')->nullable();
            $table->boolean('lida')->default(false);
            $table->nullableMorphs('origem');
            $table->json('dados_adicionais')->nullable(); // Para metadados extras
            $table->timestamp('agendada_para')->nullable(); // Para notificações programadas
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['user_id', 'lida']);
            $table->index(['tipo', 'created_at']);
        });
    }
    public function down()
    {
        Schema::dropIfExists('notificacoes');
    }
}