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
        Schema::create('clase_profesor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->onDelete('cascade');
            $table->foreignId('profesor_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['clase_id', 'profesor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clase_profesor');
    }
};

