<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasExameTable extends Migration
{
    public function up()
    {
        Schema::create('notas_exame', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudante_id');
            $table->unsignedBigInteger('disciplina_id');
            $table->unsignedBigInteger('ano_lectivo_id');
            $table->enum('tipo_exame', ['Normal', 'Recorrência']);
            $table->decimal('nota', 5, 2);
            $table->timestamps();

            $table->foreign('estudante_id')->references('id')->on('estudantes');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->foreign('ano_lectivo_id')->references('id')->on('anos_lectivos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas_exame');
    }
}
