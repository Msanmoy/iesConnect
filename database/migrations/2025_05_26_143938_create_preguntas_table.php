<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuestionario_id')->constrained()->onDelete('cascade');
            $table->string('nivel')->nullable(); // 'sencillo', 'intermedio', 'avanzado' o null
            $table->unsignedInteger('orden')->default(0);
            $table->text('enunciado');
            $table->decimal('puntos', 5, 2)->default(1);
            $table->string('tipo')->default('test');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('preguntas');
    }
};
