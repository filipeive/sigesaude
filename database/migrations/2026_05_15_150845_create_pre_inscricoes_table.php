<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pre_inscricoes', function (Blueprint $row) {
            $row->id();
            $row->string('nome_completo');
            $row->string('email')->nullable();
            $row->string('telefone');
            $row->string('documento_identificacao')->nullable();
            $row->date('data_nascimento')->nullable();
            $row->enum('genero', ['Masculino', 'Feminino', 'Outro'])->nullable();
            $row->foreignId('classe_id')->constrained('classes');
            $row->foreignId('ano_lectivo_id')->constrained('anos_lectivos');
            $row->string('codigo_pre_inscricao')->unique();
            $row->dateTime('data_limite');
            $row->enum('status', ['Pendente', 'Confirmada', 'Expirada'])->default('Pendente');
            $row->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_inscricoes');
    }
};
