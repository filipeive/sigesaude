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
        // Redundante: o campo 'turma_id' já foi adicionado na migration '2026_05_19_add_turma_id_to_docentes_table'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
