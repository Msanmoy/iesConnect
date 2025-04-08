<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Asignatura;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, hacemos que el campo slug sea nullable temporalmente
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });

        // Generamos slugs para todas las asignaturas existentes
        $asignaturas = Asignatura::whereNull('slug')->get();
        foreach ($asignaturas as $asignatura) {
            $asignatura->slug = Str::slug($asignatura->nombre);
            $asignatura->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir nada aquí
    }
};
