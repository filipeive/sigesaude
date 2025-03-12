<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Criação da tabela de inscrições
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained('estudantes')->onDelete('cascade');
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->onDelete('cascade');
            $table->integer('semestre');
            $table->enum('status', ['Pendente', 'Confirmada', 'Cancelada'])->default('Pendente');
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('referencia')->nullable();
            $table->date('data_inscricao');
            $table->timestamps();
        });

        // Criação da tabela de disciplinas selecionadas na inscrição
        Schema::create('inscricao_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscricao_id')->constrained('inscricoes')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->enum('tipo', ['Normal', 'Em Atraso'])->default('Normal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscricao_disciplinas');
        Schema::dropIfExists('inscricoes');
    }
};
