<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            if (!Schema::hasColumn('notificacoes', 'agendada_para')) {
                $table->timestamp('agendada_para')->nullable()->after('dados_adicionais');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notificacoes', function (Blueprint $table) {
            if (Schema::hasColumn('notificacoes', 'agendada_para')) {
                $table->dropColumn('agendada_para');
            }
        });
    }
};
