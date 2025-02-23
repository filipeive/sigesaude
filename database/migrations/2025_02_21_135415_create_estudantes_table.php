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
        Schema::create('estudantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relacionamento com a tabela users
            $table->string('matricula')->unique(); // Número de matrícula do estudante
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); // Curso do estudante
            $table->foreignId('ano_lectivo_id')->constrained('anos_lectivos')->onDelete('cascade'); // Ano letivo do estudante
            $table->date('data_nascimento')->nullable(); // Data de nascimento do estudante
            $table->enum('genero', ['Masculino', 'Feminino', 'Outro'])->nullable(); // Gênero
            $table->year('ano_ingresso'); // Ano em que o estudante ingressou na instituição
            $table->enum('turno', ['Diurno', 'Noturno']); // Turno do curso
            $table->enum('status', ['Ativo', 'Inativo', 'Concluído', 'Desistente'])->default('Ativo'); // Status do estudante
            $table->string('contato_emergencia')->nullable(); // Contato de emergência (responsável)
            $table->timestamps();
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudantes');
    }
};
