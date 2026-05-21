<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notas_frequencia', function (Blueprint $table) {
            // Drop old 'nota' and 'status' if we're moving to trimester-based
            if (Schema::hasColumn('notas_frequencia', 'nota')) {
                $table->dropColumn('nota');
            }
            if (Schema::hasColumn('notas_frequencia', 'status')) {
                $table->dropColumn('status');
            }

            // Add new columns
            $table->tinyInteger('trimestre')->default(1)->after('ano_lectivo_id'); // 1, 2, 3
            $table->decimal('acs1', 5, 2)->nullable();
            $table->decimal('acs2', 5, 2)->nullable();
            $table->decimal('acs3', 5, 2)->nullable();
            $table->decimal('acp', 5, 2)->nullable();
            $table->decimal('acf', 5, 2)->nullable();
            $table->string('comportamento', 20)->nullable(); // Bom, Mau, Razoável
            $table->integer('faltas')->default(0);
            $table->decimal('media_trimestral', 5, 2)->nullable();
        });

        // Add a new table for final yearly results to avoid mixing with trimester logic
        if (!Schema::hasTable('resultados_finais')) {
            Schema::create('resultados_finais', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('estudante_id');
                $table->unsignedBigInteger('disciplina_id');
                $table->unsignedBigInteger('ano_lectivo_id');
                
                $table->decimal('mt1', 5, 2)->nullable();
                $table->decimal('mt2', 5, 2)->nullable();
                $table->decimal('mt3', 5, 2)->nullable();
                $table->decimal('media_frequencia', 5, 2)->nullable(); // (MT1+MT2+MT3)/3
                
                $table->decimal('nota_exame', 5, 2)->nullable();
                $table->decimal('media_final', 5, 2)->nullable(); // CF
                
                // Excluído, Admitido, Dispensado, Aprovado, Reprovado
                $table->enum('classificacao_final', ['Admitido', 'Excluído', 'Dispensado', 'Aprovado', 'Reprovado'])->nullable(); 
                
                $table->timestamps();

                $table->foreign('estudante_id')->references('id')->on('estudantes');
                $table->foreign('disciplina_id')->references('id')->on('disciplinas');
                $table->foreign('ano_lectivo_id')->references('id')->on('anos_lectivos');
            });
        }
    }

    public function down()
    {
        Schema::table('notas_frequencia', function (Blueprint $table) {
            $table->decimal('nota', 5, 2)->nullable();
            $table->enum('status', ['Admitido', 'Excluído', 'Dispensado'])->nullable();

            $table->dropColumn(['trimestre', 'acs1', 'acs2', 'acs3', 'acp', 'acf', 'comportamento', 'faltas', 'media_trimestral']);
        });

        Schema::dropIfExists('resultados_finais');
    }
};
