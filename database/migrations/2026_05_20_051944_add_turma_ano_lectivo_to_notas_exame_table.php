<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notas_exame', function (Blueprint $table) {
            if (!Schema::hasColumn('notas_exame', 'turma_id')) {
                $table->foreignId('turma_id')->nullable()->after('disciplina_id')->constrained();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notas_exame', function (Blueprint $table) {
            if (Schema::hasColumn('notas_exame', 'turma_id')) {
                $table->dropForeign(['turma_id']);
                $table->dropColumn('turma_id');
            }
        });
    }
};
