<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inbox_item_id')->nullable()->constrained('inbox_items')->cascadeOnDelete();
            $table->string('entidad_tipo')->nullable(); // 'evento', 'tarea', 'inbox'
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('campo_faltante'); // 'lugar', 'hora', 'duracion', etc.
            $table->text('pregunta');
            $table->text('respuesta')->nullable();
            $table->enum('estado', ['pendiente', 'respondida', 'descartada'])->default('pendiente')->index();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_questions');
    }
};
