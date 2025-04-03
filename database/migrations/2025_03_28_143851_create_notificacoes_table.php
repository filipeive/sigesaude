<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id(); // Adiciona o campo id
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com users
            $table->string('titulo'); // Título da notificação
            $table->text('mensagem'); // Mensagem da notificação
            $table->enum('tipo', ['academico', 'financeiro', 'administrativo', 'geral']); // Tipo da notificação
            $table->string('icone')->nullable(); // Ícone da notificação (opcional)
            $table->string('cor')->nullable(); // Cor da notificação (opcional)
            $table->string('link')->nullable(); // Link opcional
            $table->boolean('lida')->default(false); // Se a notificação foi lida ou não
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacoes');
    }
}
