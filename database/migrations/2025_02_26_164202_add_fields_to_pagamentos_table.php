<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPagamentosTable extends Migration
{
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->string('referencia')->unique()->after('id'); // Referência única
            $table->enum('status', ['pendente', 'pago'])->default('pendente')->after('valor'); // Status do pagamento
            $table->date('data_vencimento')->after('data_pagamento'); // Data de vencimento
        });
    }

    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['referencia', 'status', 'data_vencimento']);
        });
    }
}