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
        Schema::table('pre_inscricoes', function (Blueprint $table) {
            $table->string('referencia')->nullable()->after('codigo_pre_inscricao');
            $table->decimal('valor', 10, 2)->nullable()->after('referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_inscricoes', function (Blueprint $table) {
            $table->dropColumn(['referencia', 'valor']);
        });
    }
};
