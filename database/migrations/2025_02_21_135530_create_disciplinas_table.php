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
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Nome da disciplina
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade'); // Docente responsável
            $table->foreignId('curso_id')->constrained()->onDelete('cascade'); // Relacionamento com cur
            $table->foreignId('nivel_id')->constrained('niveis')->onDelete('cascade'); // Nível da disciplina
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinas');
    }
};
