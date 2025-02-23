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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('departamento'); // Departamento do docente
            $table->string('formacao'); // Formação acadêmica
            $table->integer('anos_experiencia')->nullable(); // Tempo de experiência
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo'); // Status do docente
            $table->timestamps();
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
