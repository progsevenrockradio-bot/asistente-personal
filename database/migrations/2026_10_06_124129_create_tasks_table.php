<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inbox_item_id')->nullable()->constrained('inbox_items')->nullOnDelete();
            $table->foreignId('dependencia_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_limite')->nullable()->index();
            $table->time('hora')->nullable();
            $table->enum('prioridad', ['URGENTE', 'IMPORTANTE', 'NORMAL', 'PUEDE_ESPERAR'])->default('NORMAL');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'bloqueada', 'pospuesta'])->default('pendiente')->index();
            $table->integer('duracion_estimada')->nullable(); // en minutos
            $table->string('categoria')->nullable();
            $table->json('recordatorios')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
