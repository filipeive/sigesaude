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
        // Redundante: as colunas já são criadas na migration de criação da tabela 'medias_finais'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
