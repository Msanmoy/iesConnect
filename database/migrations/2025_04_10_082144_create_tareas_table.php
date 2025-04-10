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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('asignatura_id')->constrained()->onDelete('cascade');
            $table->boolean('visible')->default(true);
            $table->boolean('eliminado')->default(false);
            $table->foreignId('profesor_id')
                ->nullable()
                ->constrained('usuarios')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['profesor_id']);
        });

        Schema::dropIfExists('tareas');
    }
};
