<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasDetalhadasTable extends Migration
{
    public function up()
    {
        Schema::create('notas_detalhadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notas_frequencia_id');
            $table->enum('tipo', ['Teste 1', 'Teste 2', 'Teste 3', 'Trabalho 1', 'Trabalho 2', 'Trabalho 3']);
            $table->decimal('nota', 5, 2);
            $table->timestamps();

            // Chave estrangeira
            $table->foreign('notas_frequencia_id')
                  ->references('id')
                  ->on('notas_frequencia')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas_detalhadas');
    }
}