<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign(['curso_id']);
            $table->dropColumn('curso_id');
        });

        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()->after('docente_id')
                  ->constrained('turmas')->onDelete('set null');
            $table->string('carga_horaria')->nullable()->after('nome');
        });
    }

    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            $table->dropForeign(['turma_id']);
            $table->dropColumn(['turma_id', 'carga_horaria']);
        });

        Schema::table('disciplinas', function (Blueprint $table) {
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
        });
    }
};
