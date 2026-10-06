<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('remindable'); // Event, Task, Meal, Medication, etc.
            $table->enum('tipo', [
                'diario',
                'dia_anterior',
                'dos_horas_antes',
                'hora_concreta',
                'antes_salir',
                'tarea_pendiente',
                'personalizado',
            ])->default('dos_horas_antes');
            $table->dateTime('programado_para')->index();
            $table->enum('estado', ['pendiente', 'disparado', 'cancelado'])->default('pendiente')->index();
            $table->json('configuracion')->nullable();
            $table->timestamps();
        });

        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reminder_id')->nullable()->constrained('reminders')->nullOnDelete();
            $table->string('canal', 50)->default('web'); // web, push, email, internal
            $table->string('titulo');
            $table->text('mensaje');
            $table->json('payload')->nullable();
            $table->enum('estado', ['programada', 'enviada', 'entregada', 'fallida', 'cancelada'])->default('programada')->index();
            $table->timestamp('fecha_envio')->nullable();
            $table->text('error_mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
        Schema::dropIfExists('reminders');
    }
};
