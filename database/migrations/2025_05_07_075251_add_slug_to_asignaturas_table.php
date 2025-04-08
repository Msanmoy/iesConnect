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
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nombre');
            $table->string('imagen')->nullable()->after('slug');
        });

        // Generar slugs para las asignaturas existentes
        $asignaturas = Asignatura::all();
        foreach ($asignaturas as $asignatura) {
            $asignatura->slug = Str::slug($asignatura->nombre);
            $asignatura->save();
        }

        // Hacer el campo slug obligatorio después de generar los slugs
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignaturas', function (Blueprint $table) {
            $table->dropColumn(['slug', 'imagen']);
        });
    }
};
