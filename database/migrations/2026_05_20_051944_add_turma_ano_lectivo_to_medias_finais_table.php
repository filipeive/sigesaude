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
        Schema::table('medias_finais', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()->after('disciplina_id')->constrained();
            $table->foreignId('ano_lectivo_id')->nullable()->after('turma_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medias_finais', function (Blueprint $table) {
            $table->dropForeign(['ano_lectivo_id']);
            $table->dropForeign(['turma_id']);
            $table->dropColumn(['ano_lectivo_id', 'turma_id']);
        });
    }
};
