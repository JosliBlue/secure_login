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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tipo de autenticación: 'login' o 'otp' o 'question'
            $table->string('auth_type');

            // Campos para OTP
            $table->string('provided_otp_code')->nullable();
            $table->string('real_otp_code')->nullable();

            // Campos para pregunta de seguridad
            $table->foreignId('question_id')->nullable()->constrained('questions')->onDelete('set null');
            $table->text('provided_answer')->nullable();

            // Estado del intento
            $table->boolean('success')->default(false);

            // Información adicional
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
