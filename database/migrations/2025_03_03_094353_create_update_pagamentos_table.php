<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            // Modificar o enum de status para incluir mais opções
            DB::statement("ALTER TABLE pagamentos MODIFY COLUMN status ENUM('pendente', 'pago', 'em_analise', 'cancelado') NOT NULL DEFAULT 'pendente'");
            
            // Adicionar coluna para armazenar caminho do comprovante
            $table->string('comprovante')->nullable()->after('status');
            
            // Adicionar coluna para descrição do pagamento
            $table->string('descricao')->nullable()->after('estudante_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            // Reverter o enum de status
            DB::statement("ALTER TABLE pagamentos MODIFY COLUMN status ENUM('pendente', 'pago') NOT NULL DEFAULT 'pendente'");
            
            // Remover colunas
            $table->dropColumn('comprovante');
            $table->dropColumn('descricao');
        });
    }
};