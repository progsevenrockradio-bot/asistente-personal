<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('google_account_id')->nullable()->constrained('google_accounts')->nullOnDelete();
            $table->string('nombre');
            $table->string('tipo', 50); // pdf, docx, txt, csv, markdown, imagen
            $table->unsignedBigInteger('tamano')->default(0); // bytes
            $table->string('origen', 50)->default('telefono'); // telefono, google_drive, notebooklm, manual
            $table->string('google_drive_file_id')->nullable()->index();
            $table->string('ubicacion')->nullable(); // storage path
            $table->string('categoria', 50)->default('general')->index(); // general, medico, concierto, trabajo, etc.
            $table->json('etiquetas')->nullable();
            $table->date('fecha')->nullable();
            $table->string('hash', 64)->nullable()->index();
            $table->longText('texto_extraido')->nullable();
            $table->enum('estado_procesamiento', [
                'pendiente',
                'procesando',
                'completado',
                'error',
            ])->default('pendiente')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Relación muchos a muchos o polimórfica para asociar documentos a eventos y tareas
        Schema::create('documentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->morphs('documentable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentables');
        Schema::dropIfExists('documents');
    }
};
