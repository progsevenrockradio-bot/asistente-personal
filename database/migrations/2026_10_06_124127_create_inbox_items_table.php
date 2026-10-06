<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbox_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('source', 50)->default('texto'); // texto, voz, importacion, quick_action
            $table->enum('status', [
                'pendiente_analisis',
                'analizado',
                'necesita_informacion',
                'convertido_evento',
                'convertido_tarea',
                'archivado',
            ])->default('pendiente_analisis')->index();
            $table->json('analysis_payload')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbox_items');
    }
};
