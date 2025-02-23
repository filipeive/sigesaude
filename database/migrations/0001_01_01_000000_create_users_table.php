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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome completo do usuário
            $table->string('email')->unique(); // E-mail único para login
            $table->timestamp('email_verified_at')->nullable(); // Verificação de e-mail
            $table->string('password'); // Senha criptografada
            $table->string('telefone')->nullable(); // Número de telefone para contato
            $table->string('foto_perfil')->nullable(); // Caminho da foto de perfil
            $table->enum('genero', ['Masculino', 'Feminino', 'Outro'])->nullable(); // Gênero do usuário
            $table->enum('tipo', ['admin', 'docente', 'estudante', 'financeiro', 'secretaria'])->default('estudante'); // Tipo de usuário
            $table->rememberToken();
            $table->timestamps();
        });        

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
