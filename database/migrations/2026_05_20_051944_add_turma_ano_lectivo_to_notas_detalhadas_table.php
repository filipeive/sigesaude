<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notas_detalhadas', function (Blueprint $table) {
            if (!Schema::hasColumn('notas_detalhadas', 'turma_id')) {
                $table->foreignId('turma_id')->nullable()->after('tipo')->constrained('turmas');
            }
            if (!Schema::hasColumn('notas_detalhadas', 'ano_lectivo_id')) {
                $table->foreignId('ano_lectivo_id')->nullable()->after('turma_id')->constrained('anos_lectivos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notas_detalhadas', function (Blueprint $table) {
            foreach (['ano_lectivo_id', 'turma_id'] as $col) {
                if (Schema::hasColumn('notas_detalhadas', $col)) {
                    $table->dropForeign([$col]);
                    $table->dropColumn($col);
                }
            }
        });
    }
};
