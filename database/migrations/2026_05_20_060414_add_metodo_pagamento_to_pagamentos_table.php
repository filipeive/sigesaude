<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona metodo_pagamento à tabela pagamentos (dinheiro, transferencia, mpesa, emola, etc).
     */
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->enum('metodo_pagamento', [
                    'dinheiro',
                    'transferencia',
                    'mpesa',
                    'emola',
                    'mkesh',
                    'cheque',
                    'outro',
                ])
                ->nullable()
                ->after('tipo')
                ->comment('Método pelo qual o pagamento foi/foi efectuado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['metodo_pagamento']);
        });
    }
};
