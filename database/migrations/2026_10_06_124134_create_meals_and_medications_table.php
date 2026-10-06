<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('fecha')->index();
            $table->enum('tipo', ['desayuno', 'comida', 'merienda', 'cena', 'otro'])->default('comida');
            $table->time('hora')->nullable();
            $table->text('contenido')->nullable();
            $table->text('notas')->nullable();
            $table->text('restricciones')->nullable();
            $table->boolean('recordatorio_activo')->default(false);
            $table->timestamps();
        });

        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->string('cantidad_indicada'); // e.g., "1 pastilla", "10ml"
            $table->time('hora')->nullable();
            $table->string('frecuencia')->nullable(); // e.g., "Cada 8 horas", "Una vez al día"
            $table->string('duracion')->nullable(); // e.g., "7 días", "Tratamiento continuo"
            $table->string('relacion_comidas')->nullable(); // e.g., "Con el desayuno", "En ayunas"
            $table->text('notas')->nullable();
            $table->string('fuente')->nullable(); // e.g., "Receta médica Dr. Gómez", "Documento PDF"
            $table->boolean('activo')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
        Schema::dropIfExists('meals');
    }
};
