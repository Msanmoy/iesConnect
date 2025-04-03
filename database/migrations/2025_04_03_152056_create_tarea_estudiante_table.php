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
        Schema::create('tarea_estudiante', function (Blueprint $table) {
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('usuarios')->onDelete('cascade');
            $table->tinyInteger('fase')->default(1);
            $table->double('basico')->nullable();
            $table->double('intermedio')->nullable();
            $table->double('avanzado')->nullable();
            $table->timestamps();

            $table->primary(['tarea_id', 'estudiante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_estudiante');
    }
};

