<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('respuesta_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained()->onDelete('cascade');
            $table->foreignId('respuesta_id')->nullable()->constrained('respuestas')->onDelete('set null');
            $table->text('respuesta_abierta')->nullable();
            $table->decimal('nota', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('respuesta_estudiantes');
    }
};

