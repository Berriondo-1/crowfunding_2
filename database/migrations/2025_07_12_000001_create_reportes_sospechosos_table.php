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
        Schema::create('reportes_sospechosos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colaborador_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('proyecto_id')->constrained()->cascadeOnDelete();
            $table->text('motivo');
            $table->json('evidencias')->nullable();
            $table->string('estado', 30)->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_sospechosos');
    }
};
