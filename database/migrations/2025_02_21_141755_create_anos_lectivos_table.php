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
        Schema::create('anos_lectivos', function (Blueprint $table) {
            $table->id();
            $table->string('ano', 9)->unique(); // Exemplo: "2024-2025"
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo'); // Define o ano letivo atual
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anos_lectivos');
    }
};
