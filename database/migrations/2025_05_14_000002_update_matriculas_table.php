<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropForeign(['disciplina_id']);
            $table->dropColumn('disciplina_id');
        });
        
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('ano_lectivo_id')->nullable()->constrained('anos_lectivos')->onDelete('set null');
            $table->string('tipo_matricula')->default('normal');
            $table->decimal('valor', 10, 2)->nullable();
            $table->date('data_matricula')->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['ano_lectivo_id', 'tipo_matricula', 'valor', 'data_matricula', 'observacoes']);
        });
        
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
        });
    }
};