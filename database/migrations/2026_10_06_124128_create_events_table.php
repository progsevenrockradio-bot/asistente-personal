<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('google_account_id')->nullable()->constrained('google_accounts')->nullOnDelete();
            $table->foreignId('inbox_item_id')->nullable()->constrained('inbox_items')->nullOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha')->index();
            $table->time('hora_inicio')->nullable()->index();
            $table->time('hora_fin')->nullable();
            $table->integer('duracion')->nullable(); // duración en minutos
            $table->string('ubicacion')->nullable();
            $table->enum('prioridad', ['URGENTE', 'IMPORTANTE', 'NORMAL', 'PUEDE_ESPERAR'])->default('NORMAL');
            $table->enum('estado', ['confirmado', 'tentativo', 'cancelado'])->default('confirmado');
            $table->string('fuente')->nullable();
            $table->string('origen')->default('manual'); // manual, ia, google_calendar, documento
            $table->string('google_calendar_id')->nullable()->index();
            $table->string('google_event_id')->nullable()->index();
            $table->text('notas')->nullable();
            $table->json('recordatorios')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
