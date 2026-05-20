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
        Schema::table('matriculas', function (Blueprint $table) {
            $table->string('comprovativo')->nullable()->after('status');
            $table->timestamp('data_confirmacao')->nullable()->after('comprovativo');
            $table->string('referencia')->nullable()->change(); // Já existe, mas garantindo consistência se necessário
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['comprovativo', 'data_confirmacao']);
        });
    }
};
