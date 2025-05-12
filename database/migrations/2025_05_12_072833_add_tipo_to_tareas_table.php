<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->enum('tipo', ['tarea', 'cuestionario', 'pregunta', 'material', 'reutilizar', 'tema'])->default('tarea')->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
